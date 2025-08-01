# Guia de Instalação Local - Aurum

Este guia irá ajudar a configurar o Aurum em um ambiente de desenvolvimento local.

## Pré-requisitosGuia de Instalação Local - Aurum

Este guia irá te ajudar a configurar o Aurum em seu ambiente de desenvolvimento local.

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter os seguintes softwares instalados:

### Requisitos Obrigatórios
- **PHP 8.2+** com as seguintes extensões:
  - `pdo_sqlite` (ou `pdo_mysql`/`pdo_pgsql` se usar MySQL/PostgreSQL)
  - `mbstring`
  - `fileinfo`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
- **Composer 2.x** - Gerenciador de dependências PHP
- **Node.js 18+** - Para compilação de assets
- **npm** ou **yarn** - Gerenciador de pacotes JavaScript
- **Git** - Sistema de controle de versão

### Verificação dos Requisitos

```bash
# Verificar versão do PHP
php --version

# Verificar extensões PHP necessárias
php -m | grep -E "(pdo_sqlite|mbstring|fileinfo|openssl|tokenizer|xml|ctype|json|bcmath)"

# Verificar Composer
composer --version

# Verificar Node.js
node --version

# Verificar npm
npm --version
```

## Instalação

### 1. Clone o Repositório

```bash
git clone https://github.com/ArthurWillers/Aurum.git
cd Aurum
```

### 2. Instalar Dependências PHP

```bash
composer install
```

### 3. Configuração do Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4. Configurar Banco de Dados

O Aurum vem configurado por padrão para usar SQLite, que é perfeito para desenvolvimento local.

#### Opção A: SQLite (Recomendado para desenvolvimento)

```bash
# Criar o arquivo de banco de dados
touch database/database.sqlite
```

O arquivo `.env` já está configurado para SQLite:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

#### Opção B: MySQL (Opcional)

Se preferir usar MySQL, edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aurum
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Executar Migrações

```bash
# Executar migrações para criar as tabelas
php artisan migrate

# (Opcional) Executar seeders para dados de exemplo
php artisan db:seed
```

### 6. Instalar Dependências Frontend

```bash
npm install
```

### 7. Compilar Assets

```bash
# Para desenvolvimento (com hot reload)
npm run dev

# Para produção (compilação otimizada)
npm run build
```

## Executando a Aplicação

### Servidor de Desenvolvimento

```bash
# Iniciar servidor Laravel
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

### Com Hot Reload (Opcional)

Para desenvolvimento com hot reload do frontend:

```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

## Primeiro Acesso

1. Acesse `http://localhost:8000`
2. Clique em "Registrar" para criar uma nova conta
3. Preencha os dados solicitados
4. Faça login com suas credenciais

## Comandos Úteis para Desenvolvimento

### Artisan Commands

```bash
# Limpar cache da aplicação
php artisan cache:clear

# Limpar cache de configuração
php artisan config:clear

# Limpar cache de rotas
php artisan route:clear

# Limpar cache de views
php artisan view:clear

# Recriar autoload do Composer
composer dump-autoload

# Executar testes
php artisan test
# ou
./vendor/bin/pest
```

### Frontend Commands

```bash
# Modo desenvolvimento com watch
npm run dev

# Build para produção
npm run build

# Limpar node_modules e reinstalar
rm -rf node_modules package-lock.json
npm install
```

### Database Commands

```bash
# Reset completo do banco de dados
php artisan migrate:fresh --seed

# Executar uma migração específica
php artisan migrate --path=/database/migrations/nome_da_migration.php

# Reverter última migração
php artisan migrate:rollback

# Verificar status das migrações
php artisan migrate:status
```

## Solução de Problemas

### Problemas Comuns

#### Erro de Permissão
```bash
# Linux/macOS
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

# Ou uma alternativa mais permissiva (apenas para desenvolvimento)
chmod -R 777 storage bootstrap/cache
```

#### Erro de Chave da Aplicação
```bash
php artisan key:generate
```

#### Erro de Database
```bash
# Verificar se o arquivo SQLite existe
ls -la database/database.sqlite

# Se não existir, criar:
touch database/database.sqlite

# Executar migrações novamente
php artisan migrate:fresh
```

#### Erro de Dependências Node.js
```bash
# Limpar cache do npm
npm cache clean --force

# Deletar node_modules e reinstalar
rm -rf node_modules package-lock.json
npm install
```

#### Erro de Memória PHP
Edite o arquivo `php.ini` e aumente:
```ini
memory_limit = 512M
```

### Logs de Erro

```bash
# Visualizar logs da aplicação
tail -f storage/logs/laravel.log

# Limpar logs
> storage/logs/laravel.log
```

## Próximos Passos

Agora que o Aurum está rodando localmente:

1. **Explore a Interface**: Navegue pelas diferentes seções (Dashboard, Receitas, Despesas, Categorias)
2. **Crie Dados de Teste**: Adicione algumas categorias, receitas e despesas
3. **Consulte a Documentação**: Veja [PRODUCTION.md](PRODUCTION.md) para deploy em produção
4. **Customize**: Modifique cores, layouts e funcionalidades conforme necessário

## Suporte

Se encontrar problemas durante a instalação:

1. Verifique os logs: `storage/logs/laravel.log`
2. Confirme se todos os pré-requisitos estão instalados
3. Consulte a documentação oficial do [Laravel](https://laravel.com/docs)
4. Abra uma issue no repositório do GitHub

---

Parabéns! O Aurum está agora rodando no ambiente local.
