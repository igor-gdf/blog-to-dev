# Configuração de Ambiente (.env)

## Visão Geral

O projeto utiliza variáveis de ambiente para gerenciar configurações sensíveis, especialmente credenciais de banco de dados. Isso aumenta a segurança e facilita deploy em diferentes ambientes.

## Configuração Inicial

1. **Copie o arquivo de exemplo:**
   ```bash
   cp .env.example .env
   ```

2. **Edite o arquivo `.env` com suas configurações:**
   ```bash
   nano .env
   ```

3. **Configure as variáveis de banco de dados:**
   ```env
   DB_HOST=172.17.0.1
   DB_PORT=5433
   DB_DATABASE=blogdb
   DB_USERNAME=bloguser
   DB_PASSWORD=sua_senha_aqui
   ```

## Variáveis Disponíveis

### Banco de Dados

| Variável | Descrição | Valor Padrão |
|----------|-----------|--------------|
| `DB_DATASOURCE` | Driver do banco | `Database/Postgres` |
| `DB_HOST` | Host do banco | `localhost` |
| `DB_PORT` | Porta do banco | `5432` |
| `DB_DATABASE` | Nome do banco | `blogdb` |
| `DB_USERNAME` | Usuário do banco | `bloguser` |
| `DB_PASSWORD` | Senha do banco | - |
| `DB_ENCODING` | Encoding | `utf8` |
| `DB_PERSISTENT` | Conexão persistente | `false` |
| `DB_PREFIX` | Prefixo de tabelas | (vazio) |

### Aplicação

| Variável | Descrição | Valor Padrão |
|----------|-----------|--------------|
| `APP_ENV` | Ambiente | `development` |
| `DEBUG` | Modo debug | `true` |

## Segurança

⚠️ **IMPORTANTE:**
- O arquivo `.env` **NÃO** deve ser commitado no Git
- Está listado no `.gitignore`
- Use `.env.example` como template para novos ambientes
- Nunca compartilhe senhas em código ou documentação pública

## Como Funciona

1. **env.php**: Carrega variáveis do arquivo `.env`
2. **database.php**: Lê variáveis usando a função `env()`
3. **Fallback**: Se `.env` não existir, usa valores hardcoded

### Exemplo de Uso

```php
// No database.php
'host' => env('DB_HOST', 'localhost'),
'password' => env('DB_PASSWORD', 'default_password'),
```

## Diferentes Ambientes

### Desenvolvimento (Local)
```env
DB_HOST=172.17.0.1
DB_PORT=5433
DEBUG=true
```

### Produção
```env
DB_HOST=seu-servidor-producao.com
DB_PORT=5432
DEBUG=false
APP_ENV=production
```

## Troubleshooting

### Erro de conexão ao banco
1. Verifique se o arquivo `.env` existe
2. Confirme as credenciais no `.env`
3. Teste conexão: `docker exec -it blog-db psql -U bloguser -d blogdb`

### Variáveis não carregam
1. Limpe cache do CakePHP: `rm -rf app/tmp/cache/*`
2. Reinicie o container web: `docker restart blog-web`

## Referências

- [The Twelve-Factor App - Config](https://12factor.net/config)
- [PHP dotenv](https://github.com/vlucas/phpdotenv)
