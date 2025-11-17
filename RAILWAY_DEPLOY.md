# Deploy no Railway

## Configuração Inicial

1. **Conectar repositório no Railway**
   - Vá para Railway.app
   - Clique em "New Project"
   - Selecione "Deploy from GitHub repo"
   - Escolha este repositório

2. **Configurar Variáveis de Ambiente**

   No Railway, adicione as seguintes variáveis de ambiente:

   ```
   APP_NAME="Tribe Separator"
   APP_ENV=production
   APP_KEY=base64:... (gere com: php artisan key:generate --show)
   APP_DEBUG=false
   APP_URL=https://seu-projeto.railway.app
   
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   
   DB_CONNECTION=mysql (ou postgresql)
   DB_HOST=containers-us-west-xxx.railway.app
   DB_PORT=3306 (ou 5432 para PostgreSQL)
   DB_DATABASE=railway
   DB_USERNAME=root
   DB_PASSWORD=senha_do_railway
   ```

3. **Adicionar Banco de Dados MySQL/PostgreSQL**
   - No Railway, clique em "New" → "Database"
   - Escolha MySQL ou PostgreSQL
   - Railway criará automaticamente as variáveis de ambiente

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

### Erro: "Database connection failed"
- Verifique se o banco de dados está criado
- Verifique as variáveis de ambiente `DB_*`
- Certifique-se de que o banco aceita conexões externas

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

