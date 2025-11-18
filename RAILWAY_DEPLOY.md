# Deploy no Railway

## Configuração Inicial

1. **Conectar repositório no Railway**
   - Vá para Railway.app
   - Clique em "New Project"
   - Selecione "Deploy from GitHub repo"
   - Escolha este repositório

2. **Adicionar Banco de Dados PostgreSQL**
   - No Railway, clique em "New" → "Database"
   - Escolha PostgreSQL
   - Railway criará automaticamente as variáveis de ambiente para o serviço PostgreSQL
   - **Anote o nome exato do serviço PostgreSQL** (aparece no topo do serviço, pode ser "Postgres", "PostgreSQL", etc.)

3. **Configurar Variáveis de Ambiente**

   No serviço da sua aplicação (não no PostgreSQL), adicione as seguintes variáveis de ambiente:

   ```
   APP_NAME="Tribe Separator"
   APP_ENV=production
   APP_KEY=base64:... (gere com: php artisan key:generate --show)
   APP_DEBUG=false
   APP_URL=https://seu-projeto.railway.app
   
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   
   DB_CONNECTION=pgsql
   DB_PORT=5432
   ```

   **Configurar variáveis do PostgreSQL:**
   
   Substitua `Postgres` pelo nome exato do seu serviço PostgreSQL (case-sensitive):
   
   ```
   DB_HOST=${{Postgres.PGHOST}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   DATABASE_URL=${{Postgres.DATABASE_URL}}
   DB_URL=${{Postgres.DATABASE_URL}}
   ```
   
   **⚠️ CRÍTICO: NÃO coloque aspas ao redor das referências de variáveis!**
   
   ❌ ERRADO: `DATABASE_URL="${{Postgres.DATABASE_URL}}"`
   ✅ CORRETO: `DATABASE_URL=${{Postgres.DATABASE_URL}}`

4. **Configurar Build**
   - O Railway detectará automaticamente o Dockerfile
   - Ou você pode usar o buildpack PHP do Railway (mais rápido)

## Opção Alternativa: Usar Buildpack PHP

Se preferir não usar Dockerfile, você pode:

1. Remover ou renomear o Dockerfile
2. O Railway usará automaticamente o buildpack PHP
3. Configure o `Procfile`:
   ```
   web: php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

## Comandos Úteis

- **Ver logs**: Railway Dashboard → Deployments → Logs
- **Executar comandos**: Railway Dashboard → Service → Shell
- **Variáveis de ambiente**: Railway Dashboard → Variables

## Troubleshooting

### Erro: "APP_KEY não definido"
- Execute: `php artisan key:generate --show`
- Copie a chave e adicione como variável `APP_KEY` no Railway

### Erro: "Database connection failed" ou valores padrão aparecendo nos logs

**Problema:** O Laravel não está conseguindo ler as variáveis de ambiente do Railway.

**Solução:**

1. **Verificar o nome do serviço PostgreSQL no Railway:**
   - Vá para o Dashboard do Railway
   - Clique no serviço PostgreSQL
   - Veja o nome do serviço (pode ser "Postgres", "PostgreSQL", "postgres", etc.)
   - Anote o nome exato (case-sensitive)

2. **Configurar as variáveis de ambiente corretamente:**
   - No serviço da sua aplicação (não no PostgreSQL), vá em "Variables"
   - Adicione as seguintes variáveis, substituindo `Postgres` pelo nome exato do seu serviço:
   
   ```
   DB_CONNECTION=pgsql
   DB_PORT=5432
   DB_HOST=${{Postgres.PGHOST}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   DATABASE_URL=${{Postgres.DATABASE_URL}}
   DB_URL=${{Postgres.DATABASE_URL}}
   ```
   
   **⚠️ IMPORTANTE:**
   - **NÃO coloque aspas** ao redor das referências de variáveis
   - ❌ ERRADO: `DATABASE_URL="${{Postgres.DATABASE_URL}}"`
   - ✅ CORRETO: `DATABASE_URL=${{Postgres.DATABASE_URL}}`
   - O nome do serviço deve corresponder exatamente ao nome no Railway (case-sensitive)

3. **Verificar se as variáveis estão sendo lidas:**
   - Após o deploy, verifique os logs do Railway
   - Você verá uma seção "=== Variáveis de Ambiente do Banco ==="
   - Se aparecer "não definido", significa que a variável não está sendo injetada corretamente
   - Verifique o nome do serviço e se não há aspas nas variáveis

4. **Se ainda não funcionar:**
   - Verifique se o serviço PostgreSQL está no mesmo projeto
   - Tente usar o nome do serviço em minúsculas: `${{postgres.PGHOST}}`
   - Ou use o nome completo que aparece no dashboard

### Erro: "Assets não carregam"
- Verifique se o build do Vite foi executado
- Os assets devem estar em `public/build`

### Erro: "Permission denied" no storage
- O Railway deve configurar permissões automaticamente
- Se necessário, adicione no startCommand: `chmod -R 755 storage bootstrap/cache`

## Notas

- O Railway usa a variável `PORT` automaticamente
- O deploy é automático a cada push no repositório
- Use `APP_DEBUG=false` em produção
- Configure `APP_URL` com o domínio do Railway

