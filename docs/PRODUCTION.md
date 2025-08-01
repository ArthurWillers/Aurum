# Guia de Instalação em Produção - Aurum

Este guia fornece instruções detalhadas para instalar e configurar o Aurum em ambiente de produção.

## Visão Geral

Este documento cobre:
- Requisitos de servidor
- Configuração de ambiente
- Instalação e configuração
- Segurança e otimizações
- Monitoramento

## Requisitos do Servidor

### Especificações Mínimas Recomendadas

#### Hardware
- **CPU**: 2 cores (4 cores recomendado)
- **RAM**: 2GB (4GB recomendado)
- **Armazenamento**: 10GB SSD
- **Rede**: Conexão estável à internet

#### Software
- **Sistema Operacional**: Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- **Servidor Web**: Nginx 1.18+ ou Apache 2.4+
- **PHP**: 8.2+ com FPM
- **Banco de Dados**: MySQL 8.0+ / PostgreSQL 13+ / MariaDB 10.6+
- **Node.js**: 18.x LTS
- **Composer**: 2.x
- **Git**: Para deploy automatizado
- **SSL**: Certificado válido (Let's Encrypt recomendado)

### Extensões PHP Obrigatórias

```bash
# Instalar extensões PHP no Ubuntu/Debian
sudo apt install php8.2-cli php8.2-fpm php8.2-mysql php8.2-pgsql \
    php8.2-sqlite3 php8.2-xml php8.2-mbstring php8.2-curl \
    php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl \
    php8.2-redis php8.2-opcache

# Verificar extensões instaladas
php -m | grep -E "(pdo|mysql|pgsql|sqlite|mbstring|xml|curl|zip|bcmath|gd|intl|redis|opcache)"
```

## Configuração do Banco de Dados

### MySQL (Recomendado)

```sql
-- Conectar como root
mysql -u root -p

-- Criar banco de dados
CREATE DATABASE aurum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar usuário dedicado
CREATE USER 'aurum_user'@'localhost' IDENTIFIED BY 'senha_segura_aqui';

-- Conceder permissões
GRANT ALL PRIVILEGES ON aurum.* TO 'aurum_user'@'localhost';
FLUSH PRIVILEGES;

-- Verificar criação
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'aurum_user';
```

### PostgreSQL (Alternativa)

```sql
-- Conectar como postgres
sudo -u postgres psql

-- Criar banco de dados
CREATE DATABASE aurum;

-- Criar usuário
CREATE USER aurum_user WITH ENCRYPTED PASSWORD 'senha_segura_aqui';

-- Conceder permissões
GRANT ALL PRIVILEGES ON DATABASE aurum TO aurum_user;

-- Verificar
\l
\du
```

## Instalação da Aplicação

### 1. Preparação do Servidor

```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependências básicas
sudo apt install -y curl wget git unzip software-properties-common

# Adicionar repositório PHP (Ubuntu)
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Instalar PHP e extensões
sudo apt install -y php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml \
    php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath \
    php8.2-gd php8.2-intl php8.2-redis php8.2-opcache

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Configuração de Usuário e Diretórios

```bash
# Criar usuário dedicado para a aplicação
sudo adduser --system --group --home /var/www aurum

# Criar estrutura de diretórios
sudo mkdir -p /var/www/aurum
sudo chown -R aurum:aurum /var/www/aurum
```

### 3. Clone e Instalação

```bash
# Mudar para usuário aurum
sudo su - aurum

# Navegar para diretório
cd /var/www

# Clone do repositório
git clone https://github.com/ArthurWillers/Aurum.git aurum-app
cd aurum-app

# Instalar dependências PHP
composer install --optimize-autoloader --no-dev

# Instalar dependências Node.js
npm ci --only=production

# Compilar assets para produção
npm run build

# Remover arquivos desnecessários
rm -rf node_modules
rm package*.json
```

### 4. Configuração de Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Editar configurações
nano .env
```

#### Configurações Essenciais (.env)

```env
# Aplicação
APP_NAME="Aurum"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Banco de Dados (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aurum
DB_USERNAME=aurum_user
DB_PASSWORD=senha_segura_aqui

# Cache (Redis recomendado para produção)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email (configurar conforme provedor)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"

# Logs
LOG_CHANNEL=daily
LOG_LEVEL=error

# Sessão
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=seu-dominio.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### 5. Configurações Finais da Aplicação

```bash
# Gerar chave da aplicação
php artisan key:generate

# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar autoloader
composer dump-autoload -o

# Executar migrações
php artisan migrate --force

# (Opcional) Seeders para dados iniciais
php artisan db:seed --force

# Configurar permissões
sudo chown -R aurum:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Configuração do Servidor Web

### Nginx (Recomendado)

```nginx
# /etc/nginx/sites-available/aurum
server {
    listen 80;
    listen [::]:80;
    server_name seu-dominio.com www.seu-dominio.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name seu-dominio.com www.seu-dominio.com;
    root /var/www/aurum-app/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/seu-dominio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/seu-dominio.com/privkey.pem;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;

    # Modern configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # HSTS
    add_header Strict-Transport-Security "max-age=63072000" always;

    # Security headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php index.html index.htm;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static files caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    location ~ ^/(login|register) {
        limit_req zone=login burst=5 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

```bash
# Ativar site
sudo ln -s /etc/nginx/sites-available/aurum /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache (Alternativa)

```apache
# /etc/apache2/sites-available/aurum.conf
<VirtualHost *:80>
    ServerName seu-dominio.com
    ServerAlias www.seu-dominio.com
    Redirect permanent / https://seu-dominio.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName seu-dominio.com
    ServerAlias www.seu-dominio.com
    DocumentRoot /var/www/aurum-app/public

    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/seu-dominio.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/seu-dominio.com/privkey.pem

    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000"

    <Directory /var/www/aurum-app/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/aurum_error.log
    CustomLog ${APACHE_LOG_DIR}/aurum_access.log combined
</VirtualHost>
```

```bash
# Ativar módulos e site
sudo a2enmod ssl rewrite headers
sudo a2ensite aurum
sudo systemctl reload apache2
```

## SSL/TLS com Let's Encrypt

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obter certificado (Nginx)
sudo certbot --nginx -d seu-dominio.com -d www.seu-dominio.com

# Para Apache
# sudo certbot --apache -d seu-dominio.com -d www.seu-dominio.com

# Testar renovação automática
sudo certbot renew --dry-run

# Configurar renovação automática
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

## Otimizações de Performance

### PHP-FPM

```ini
# /etc/php/8.2/fpm/pool.d/aurum.conf
[aurum]
user = aurum
group = www-data
listen = /run/php/php8.2-fpm-aurum.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 20
pm.start_servers = 4
pm.min_spare_servers = 2
pm.max_spare_servers = 6
pm.process_idle_timeout = 60s
pm.max_requests = 1000
```

### Redis (Cache)

```bash
# Instalar Redis
sudo apt install redis-server

# Configurar Redis
sudo nano /etc/redis/redis.conf
```

```ini
# /etc/redis/redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### OPcache

```ini
# /etc/php/8.2/fpm/conf.d/10-opcache.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=1
opcache.validate_timestamps=0
```

## Monitoramento e Logs

### Configuração de Logs

```bash
# Criar diretório de logs
sudo mkdir -p /var/log/aurum
sudo chown aurum:adm /var/log/aurum

# Configurar logrotate
sudo nano /etc/logrotate.d/aurum
```

```ini
# /etc/logrotate.d/aurum
/var/log/aurum/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    copytruncate
}
```

### Monitoramento com Systemd

```ini
# /etc/systemd/system/aurum-queue.service
[Unit]
Description=Aurum Queue Worker
After=network.target

[Service]
Type=simple
User=aurum
Group=aurum
Restart=always
RestartSec=3
ExecStart=/usr/bin/php /var/www/aurum-app/artisan queue:work --sleep=3 --tries=3 --timeout=90

[Install]
WantedBy=multi-user.target
```

```bash
# Ativar serviço
sudo systemctl enable aurum-queue
sudo systemctl start aurum-queue
sudo systemctl status aurum-queue
```

## Segurança

### Firewall

```bash
# Configurar UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

### Backup Automatizado

```bash
# Script de backup
sudo nano /usr/local/bin/backup-aurum.sh
```

```bash
#!/bin/bash
# Backup script para Aurum

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/aurum"
APP_DIR="/var/www/aurum-app"

mkdir -p $BACKUP_DIR

# Backup do banco de dados
mysqldump -u aurum_user -p'senha_segura_aqui' aurum > $BACKUP_DIR/database_$DATE.sql

# Backup de arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C /var/www aurum-app --exclude=node_modules --exclude=vendor

# Manter apenas últimos 7 backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
# Tornar executável
sudo chmod +x /usr/local/bin/backup-aurum.sh

# Configurar cron para backup diário
echo "0 2 * * * /usr/local/bin/backup-aurum.sh" | sudo crontab -
```

## Manutenção

### Comandos de Manutenção Regular

```bash
# Script de manutenção
sudo nano /usr/local/bin/maintain-aurum.sh
```

```bash
#!/bin/bash
# Script de manutenção do Aurum

cd /var/www/aurum-app

# Limpar caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
composer dump-autoload -o

# Limpar logs antigos
find storage/logs -name "*.log" -mtime +30 -delete

echo "Maintenance completed"
```

### Atualização da Aplicação

Ver documentação específica em [DEPLOY.md](DEPLOY.md).

## Lista de Verificação Pós-Instalação

- [ ] Aplicação acessível via HTTPS
- [ ] SSL/TLS configurado corretamente
- [ ] Banco de dados funcionando
- [ ] Cache Redis operacional
- [ ] Logs sendo gerados
- [ ] Backup automatizado configurado
- [ ] Firewall ativo
- [ ] Monitoramento funcionando
- [ ] Performance otimizada
- [ ] Segurança implementada

## Solução de Problemas

### Problemas Comuns

#### Erro 500 - Internal Server Error
```bash
# Verificar logs
sudo tail -f /var/log/nginx/error.log
tail -f /var/www/aurum-app/storage/logs/laravel.log

# Verificar permissões
sudo chown -R aurum:www-data /var/www/aurum-app
sudo chmod -R 775 /var/www/aurum-app/storage /var/www/aurum-app/bootstrap/cache
```

#### Erro de Conexão com Banco
```bash
# Testar conexão
mysql -u aurum_user -p aurum

# Verificar configurações .env
grep DB_ /var/www/aurum-app/.env
```

#### Cache não Funcionando
```bash
# Verificar Redis
redis-cli ping

# Limpar todos os caches
php artisan cache:clear
php artisan config:clear
```

---

Parabéns! A instalação do Aurum em produção está completa e otimizada.
