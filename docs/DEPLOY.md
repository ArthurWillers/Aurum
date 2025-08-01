# Guia de Deploy - Aurum

Este guia aborda estratégias de deploy para o Aurum, incluindo deploy manual, automatizado e melhores práticas.

## Estratégias de Deploy

### 1. Deploy Manual Simples
### 2. Deploy Automatizado com Scripts
### 3. Deploy com CI/CD (GitHub Actions)
### 4. Deploy Zero-Downtime

## Deploy Manual Simples

Script de deploy para automação básica do processo de atualização:

### Script de Deploy Automatizado

```bash
#!/bin/bash
# Script de deploy do Aurum - Versão Melhorada

# Configurações
APP_DIR="/var/www/Aurum"
BACKUP_DIR="/var/backups/aurum"
DATE=$(date +%Y%m%d_%H%M%S)

echo "🚀 Iniciando deploy do Aurum - $DATE"

# Criar backup antes do deploy
echo "📦 Criando backup..."
mkdir -p $BACKUP_DIR
mysqldump -u aurum_user -p'sua_senha' aurum > $BACKUP_DIR/pre_deploy_$DATE.sql

# Entrar no diretório da aplicação
cd $APP_DIR || exit 1

# Colocar aplicação em modo de manutenção
echo "Ativando modo de manutenção..."
sudo php artisan down --retry=60

# Reset e pull do repositório
echo "Atualizando código..."
sudo git reset --hard
sudo git pull

# Instalar/atualizar dependências
echo "Instalando dependências PHP..."
sudo composer install --optimize-autoloader --no-dev --quiet

echo "Instalando dependências Node.js..."
sudo npm ci --silent

# Compilar assets
echo "Compilando assets..."
sudo npm run build

# Configurar permissões
echo "Configurando permissões..."
sudo chmod -R 777 $APP_DIR

# Otimizar aplicação
echo "Otimizando aplicação..."
sudo php artisan optimize

# Executar migrações
echo "Executando migrações..."
sudo php artisan migrate --force

# Reiniciar serviços
echo "Reiniciando serviços..."
sudo systemctl restart nginx

# Tirar do modo de manutenção
echo "Desativando modo de manutenção..."
sudo php artisan up

echo "Deploy concluído com sucesso!"
```

### Alias Melhorado

Adicione ao seu `~/.bashrc` ou `~/.zshrc`:

```bash
# Deploy do Aurum
alias deploy-aurum='bash /usr/local/bin/deploy-aurum.sh'

# Deploy rápido 
alias quick-deploy-aurum='cd /var/www/Aurum && sudo php artisan down && sudo git reset --hard && sudo git pull && sudo composer install --optimize-autoloader --no-dev && sudo npm ci && sudo npm run build && sudo chmod -R 777 /var/www/Aurum && sudo php artisan optimize && sudo php artisan migrate --force && sudo systemctl restart nginx && sudo php artisan up && echo "Deploy concluído!"'
```

## Deploy Automatizado com Scripts

### Script Completo de Deploy

Crie o arquivo `/usr/local/bin/deploy-aurum.sh`:

```bash
#!/bin/bash

# ===========================================
# Script de Deploy Automatizado - Aurum
# ===========================================

set -e  # Parar em caso de erro

# Configurações
APP_DIR="/var/www/Aurum"
BACKUP_DIR="/var/backups/aurum"
LOG_FILE="/var/log/aurum-deploy.log"
DATE=$(date +%Y%m%d_%H%M%S)
BRANCH="main"

# Configurações do banco
DB_USER="aurum_user"
DB_PASS="sua_senha"
DB_NAME="aurum"

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funções auxiliares
log() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a $LOG_FILE
}

success() {
    echo -e "${GREEN}[OK] $1${NC}" | tee -a $LOG_FILE
}

warning() {
    echo -e "${YELLOW}[WARNING] $1${NC}" | tee -a $LOG_FILE
}

error() {
    echo -e "${RED}[ERROR] $1${NC}" | tee -a $LOG_FILE
    exit 1
}

# Verificar se está sendo executado como root
if [ "$EUID" -ne 0 ]; then
    error "Este script deve ser executado como root (use sudo)"
fi

# Função de rollback
rollback() {
    warning "Executando rollback..."
    
    # Restaurar backup do banco
    if [ -f "$BACKUP_DIR/pre_deploy_$DATE.sql" ]; then
        mysql -u $DB_USER -p$DB_PASS $DB_NAME < $BACKUP_DIR/pre_deploy_$DATE.sql
        success "Banco de dados restaurado"
    fi
    
    # Voltar para commit anterior
    cd $APP_DIR
    git reset --hard HEAD~1
    
    # Reinstalar dependências da versão anterior
    composer install --optimize-autoloader --no-dev --quiet
    npm ci --silent
    npm run build
    
    # Otimizar
    php artisan optimize
    
    # Tirar do modo de manutenção
    php artisan up
    
    error "Rollback executado. Deploy falhou!"
}

# Trap para executar rollback em caso de erro
trap rollback ERR

# Início do deploy
log "Iniciando deploy do Aurum - $DATE"

# Verificar se diretório existe
if [ ! -d "$APP_DIR" ]; then
    error "Diretório da aplicação não encontrado: $APP_DIR"
fi

# Criar diretório de backup se não existir
mkdir -p $BACKUP_DIR

# 1. Backup do banco de dados
log "Criando backup do banco de dados..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/pre_deploy_$DATE.sql
success "Backup criado: pre_deploy_$DATE.sql"

# 2. Entrar no diretório da aplicação
cd $APP_DIR || error "Não foi possível acessar $APP_DIR"

# 3. Verificar se há mudanças no repositório
log "Verificando atualizações..."
git fetch origin $BRANCH

LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/$BRANCH)

if [ "$LOCAL" = "$REMOTE" ]; then
    log "Não há atualizações disponíveis"
    read -p "Continuar mesmo assim? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log "Deploy cancelado pelo usuário"
        exit 0
    fi
fi

# 4. Colocar aplicação em modo de manutenção
log "Ativando modo de manutenção..."
php artisan down --retry=60
success "Modo de manutenção ativado"

# 5. Atualizar código
log "Atualizando código fonte..."
git reset --hard
git pull origin $BRANCH
success "Código atualizado"

# 6. Instalar dependências PHP
log "Instalando dependências PHP..."
composer install --optimize-autoloader --no-dev --quiet
success "Dependências PHP instaladas"

# 7. Instalar dependências Node.js
log "Instalando dependências Node.js..."
npm ci --silent
success "Dependências Node.js instaladas"

# 8. Compilar assets
log "Compilando assets para produção..."
npm run build
success "Assets compilados"

# 9. Configurar permissões
log "Configurando permissões..."
chown -R aurum:www-data $APP_DIR
chmod -R 775 storage bootstrap/cache
success "Permissões configuradas"

# 10. Limpar caches
log "Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
success "Caches limpos"

# 11. Otimizar aplicação
log "Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload -o
success "Aplicação otimizada"

# 12. Executar migrações
log "Executando migrações do banco de dados..."
php artisan migrate --force
success "Migrações executadas"

# 13. Reiniciar serviços
log "Reiniciando serviços..."
systemctl restart nginx

# Verificar se serviços estão rodando
if ! systemctl is-active --quiet nginx; then
    error "Nginx não está rodando após reiniciar"
fi

success "Serviços reiniciados"

# 14. Teste de conectividade
log "Testando aplicação..."
sleep 2

# Testar se a aplicação responde
if curl -f -s -o /dev/null "http://localhost"; then
    success "Aplicação respondendo localmente"
else
    warning "Aplicação pode não estar respondendo corretamente"
fi

# 15. Tirar do modo de manutenção
log "Desativando modo de manutenção..."
php artisan up
success "Aplicação online"

# 16. Limpeza pós-deploy
log "Limpeza pós-deploy..."

# Remover node_modules se não precisar
if [ "$KEEP_NODE_MODULES" != "true" ]; then
    rm -rf node_modules
    success "node_modules removido"
fi

# Remover backups antigos (manter últimos 7 dias)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
success "Backups antigos removidos"

# Relatório final
log "Relatório do Deploy:"
echo "  Data: $DATE"
echo "  Diretório: $APP_DIR"
echo "  Branch: $BRANCH"
echo "  Commit: $(git rev-parse --short HEAD)"
echo "  Backup: $BACKUP_DIR/pre_deploy_$DATE.sql"

success "Deploy concluído com sucesso!"

# Remover trap
trap - ERR
```

### Tornar o Script Executável

```bash
# Copiar script para local do sistema
sudo cp deploy-aurum.sh /usr/local/bin/
sudo chmod +x /usr/local/bin/deploy-aurum.sh

# Criar link simbólico (opcional)
sudo ln -s /usr/local/bin/deploy-aurum.sh /usr/local/bin/deploy-aurum
```

## Deploy com CI/CD (GitHub Actions)

### Configuração do GitHub Actions

Crie `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.7
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        port: ${{ secrets.PORT }}
        script: |
          cd /var/www/Aurum
          sudo /usr/local/bin/deploy-aurum.sh
          
    - name: Notify deployment
      if: always()
      uses: 8398a7/action-slack@v3
      with:
        status: ${{ job.status }}
        text: 'Deploy do Aurum foi ${{ job.status }}'
      env:
        SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK }}
```

### Configurar Secrets no GitHub

No repositório GitHub, acessar Settings > Secrets and Variables > Actions:

- `HOST`: IP do servidor
- `USERNAME`: usuário SSH
- `SSH_KEY`: chave privada SSH
- `PORT`: porta SSH (geralmente 22)
- `SLACK_WEBHOOK`: webhook do Slack (opcional)

## Deploy Zero-Downtime

Para aplicações que precisam de alta disponibilidade:

### Usando Symlinks

```bash
#!/bin/bash
# Deploy Zero-Downtime

APP_NAME="aurum"
DEPLOY_DIR="/var/www/deployments"
CURRENT_DIR="/var/www/current"
DATE=$(date +%Y%m%d_%H%M%S)
RELEASE_DIR="$DEPLOY_DIR/$DATE"

# Criar diretório do release
mkdir -p $RELEASE_DIR

# Clone do código
git clone https://github.com/ArthurWillers/Aurum.git $RELEASE_DIR

cd $RELEASE_DIR

# Instalar dependências
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# Link para arquivos compartilhados
ln -nfs /var/www/shared/.env $RELEASE_DIR/.env
ln -nfs /var/www/shared/storage $RELEASE_DIR/storage

# Cache e otimizações
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrações
php artisan migrate --force

# Atualizar symlink atômicamente
ln -nfs $RELEASE_DIR $CURRENT_DIR

# Reiniciar serviços
systemctl reload nginx
systemctl restart php8.2-fpm

# Limpeza (manter últimos 5 releases)
cd $DEPLOY_DIR
ls -t | tail -n +6 | xargs -d '\n' rm -rf

echo "Deploy zero-downtime concluído!"
```

## Checklist de Deploy

### Pré-Deploy
- [ ] Backup do banco de dados criado
- [ ] Código testado localmente
- [ ] Dependências atualizadas
- [ ] Migrações verificadas
- [ ] Assets compilados

### Durante o Deploy
- [ ] Modo de manutenção ativado
- [ ] Código atualizado
- [ ] Dependências instaladas
- [ ] Assets compilados
- [ ] Permissões configuradas
- [ ] Caches limpos e otimizados
- [ ] Migrações executadas
- [ ] Serviços reiniciados

### Pós-Deploy
- [ ] Aplicação respondendo
- [ ] Modo de manutenção desativado
- [ ] Funcionalidades testadas
- [ ] Logs verificados
- [ ] Performance monitorada

## Comandos Úteis para Deploy

### Verificações Rápidas

```bash
# Status dos serviços
sudo systemctl status nginx php8.2-fpm mysql

# Verificar logs em tempo real
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/www/Aurum/storage/logs/laravel.log

# Verificar espaço em disco
df -h

# Verificar uso de memória
free -h

# Verificar processos PHP
ps aux | grep php

# Testar conectividade
curl -I http://localhost
curl -I https://seu-dominio.com
```

### Rollback Rápido

```bash
# Voltar para commit anterior
cd /var/www/Aurum
sudo git reset --hard HEAD~1
sudo composer install --optimize-autoloader --no-dev
sudo npm ci && sudo npm run build
sudo php artisan optimize
sudo systemctl restart nginx
```

## Troubleshooting

### Problemas Comuns

#### Deploy Falha por Permissões
```bash
sudo chown -R aurum:www-data /var/www/Aurum
sudo chmod -R 775 /var/www/Aurum/storage /var/www/Aurum/bootstrap/cache
```

#### Erro de Dependências
```bash
# Limpar e reinstalar
rm -rf vendor node_modules
composer install --optimize-autoloader --no-dev
npm ci
```

#### Erro de Cache
```bash
sudo php artisan cache:clear
sudo php artisan config:clear
sudo php artisan route:clear
sudo php artisan view:clear
```

#### Erro de Banco de Dados
```bash
# Verificar conexão
mysql -u aurum_user -p aurum

# Executar migrações manualmente
sudo php artisan migrate --force

# Verificar status das migrações
sudo php artisan migrate:status
```

## Monitoramento Pós-Deploy

### Script de Monitoramento

```bash
#!/bin/bash
# monitor-aurum.sh

# Verificar se aplicação está respondendo
if ! curl -f -s -o /dev/null "https://seu-dominio.com"; then
    echo "Aplicação não está respondendo"
    # Notificar via Slack/email
    exit 1
fi

# Verificar uso de memória
MEMORY_USAGE=$(free | grep Mem | awk '{printf "%.1f", $3/$2 * 100.0}')
if (( $(echo "$MEMORY_USAGE > 80" | bc -l) )); then
    echo "Alto uso de memória: $MEMORY_USAGE%"
fi

# Verificar espaço em disco
DISK_USAGE=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "Alto uso de disco: $DISK_USAGE%"
fi

echo "Sistema funcionando normalmente"
```

### Configurar Monitoramento

```bash
# Adicionar ao cron para executar a cada 5 minutos
echo "*/5 * * * * /usr/local/bin/monitor-aurum.sh" | sudo crontab -
```

---

Parabéns! Agora existe um sistema completo de deploy para o Aurum, desde o manual simples até estratégias avançadas de CI/CD.
