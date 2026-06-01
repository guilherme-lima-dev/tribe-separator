#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

COMPOSE_FILE="docker-compose.prod.yml"
ENV_FILE=".env"
DB_FILE="database/database.sqlite"

log() { printf '\n[%s] %s\n' "$(date '+%H:%M:%S')" "$*"; }
die() { printf '\n[ERRO] %s\n' "$*" >&2; exit 1; }

require_command() {
    command -v "$1" >/dev/null 2>&1 || die "Comando obrigatório não encontrado: $1"
}

detect_compose() {
    if docker compose version >/dev/null 2>&1; then
        echo "docker compose"
    elif command -v docker-compose >/dev/null 2>&1; then
        echo "docker-compose"
    else
        die "Instale Docker Compose (plugin: docker compose ou pacote docker-compose)"
    fi
}

ensure_docker_access() {
    if ! docker info >/dev/null 2>&1; then
        die "Sem acesso ao Docker. Adicione o usuário ao grupo docker e faça login de novo: sudo usermod -aG docker \$USER"
    fi
}

ec2_public_url() {
    local token public_host public_ip
    token="$(curl -sf -X PUT "http://169.254.169.254/latest/api/token" \
        -H "X-aws-ec2-metadata-token-ttl-seconds: 21600" 2>/dev/null || true)"
    if [ -z "$token" ]; then
        return 1
    fi
    public_host="$(curl -sf -H "X-aws-ec2-metadata-token: $token" \
        "http://169.254.169.254/latest/meta-data/public-hostname" 2>/dev/null || true)"
    public_ip="$(curl -sf -H "X-aws-ec2-metadata-token: $token" \
        "http://169.254.169.254/latest/meta-data/public-ipv4" 2>/dev/null || true)"
    if [ -n "$public_host" ]; then
        echo "http://${public_host}"
    elif [ -n "$public_ip" ]; then
        echo "http://${public_ip}"
    else
        return 1
    fi
}

create_env_if_missing() {
    if [ -f "$ENV_FILE" ]; then
        return 0
    fi
    log "Criando $ENV_FILE padrão de produção..."
    cat >"$ENV_FILE" <<'EOF'
APP_NAME="Separador de Tribos"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
EOF
}

patch_env() {
    local app_url="${1:-http://localhost}"

    create_env_if_missing

    set_env() {
        local key="$1"
        local value="$2"
        if grep -q "^${key}=" "$ENV_FILE" 2>/dev/null; then
            sed -i "s|^${key}=.*|${key}=${value}|" "$ENV_FILE"
        else
            printf '%s=%s\n' "$key" "$value" >>"$ENV_FILE"
        fi
    }

    set_env APP_NAME '"Separador de Tribos"'
    set_env APP_ENV production
    set_env APP_DEBUG false
    set_env APP_URL "$app_url"
    set_env DB_CONNECTION sqlite
    set_env DB_DATABASE /var/www/html/database/database.sqlite
    set_env SESSION_DRIVER file
    set_env CACHE_STORE file
    set_env QUEUE_CONNECTION sync
    set_env LOG_LEVEL error
}

has_app_key() {
    grep -qE '^APP_KEY=base64:.+' "$ENV_FILE" 2>/dev/null
}

ensure_sqlite() {
    mkdir -p database
    if [ ! -f "$DB_FILE" ]; then
        log "Criando banco SQLite em $DB_FILE"
        touch "$DB_FILE"
    fi
    chmod 664 "$DB_FILE" 2>/dev/null || true
}

stop_host_nginx_if_needed() {
    if command -v systemctl >/dev/null 2>&1 && systemctl is-active --quiet nginx 2>/dev/null; then
        log "Parando nginx do host (libera porta 80)..."
        sudo systemctl stop nginx || true
    fi
}

generate_app_key() {
    local compose="$1"
    local key

    if has_app_key; then
        log "APP_KEY já configurada."
        return 0
    fi

    log "Gerando APP_KEY..."
    $compose -f "$COMPOSE_FILE" build --quiet app 2>/dev/null \
        || $compose -f "$COMPOSE_FILE" build app

    key="$($compose -f "$COMPOSE_FILE" run --rm --no-deps app php artisan key:generate --show | tail -n1)"
    [ -n "$key" ] || die "Falha ao gerar APP_KEY"

    if grep -q '^APP_KEY=' "$ENV_FILE" 2>/dev/null; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${key}|" "$ENV_FILE"
    else
        printf 'APP_KEY=%s\n' "$key" >>"$ENV_FILE"
    fi
}

wait_for_container() {
    local compose="$1"
    local attempts=30
    local i=1

    while [ "$i" -le "$attempts" ]; do
        if $compose -f "$COMPOSE_FILE" ps --status running 2>/dev/null | grep -q tribe-separator; then
            return 0
        fi
        sleep 2
        i=$((i + 1))
    done
    die "Container tribe-separator não entrou em execução a tempo."
}

print_status() {
    local compose="$1"
    local app_url="$2"

    log "Status dos containers:"
    $compose -f "$COMPOSE_FILE" ps

    log "Últimas linhas do log:"
    $compose -f "$COMPOSE_FILE" logs --tail=20 app || true

    printf '\n========================================\n'
    printf ' Deploy concluído.\n'
    printf ' Acesse: %s\n' "$app_url"
    printf ' Caminho: %s\n' "$ROOT_DIR"
    printf ' Comandos úteis:\n'
    printf '   %s -f %s logs -f app\n' "$compose" "$COMPOSE_FILE"
    printf '   %s -f %s restart\n' "$compose" "$COMPOSE_FILE"
    printf '========================================\n\n'
}

main() {
    local compose app_url

    log "Deploy em $ROOT_DIR"
    require_command docker
    require_command curl
    compose="$(detect_compose)"
    ensure_docker_access

    if app_url="$(ec2_public_url)"; then
        log "URL detectada na EC2: $app_url"
    else
        app_url="http://localhost"
        log "Não foi possível detectar URL da EC2; usando $app_url (ajuste APP_URL no .env se necessário)"
    fi

    patch_env "$app_url"
    ensure_sqlite
    stop_host_nginx_if_needed
    generate_app_key "$compose"

    log "Build e subida do container (modo daemon)..."
    $compose -f "$COMPOSE_FILE" up -d --build --remove-orphans

    wait_for_container "$compose"
    print_status "$compose" "$app_url"
}

main "$@"
