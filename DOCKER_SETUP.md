# Docker Setup para Blog-to-Dev

## Problema: Incompatibilidade nftables x iptables

Este projeto enfrenta um problema de compatibilidade no Docker com sistemas que usam `nftables` em vez de `iptables`. O Docker Compose tenta criar redes bridge usando iptables, o que falha em sistemas com nftables.

**Erro típico:**
```
failed to create network: Error response from daemon: add inter-network communication rule: 
(iptables failed: iptables --wait -t filter -A DOCKER-ISOLATION-STAGE-1 -i br-... ! -o br-... 
-j DOCKER-ISOLATION-STAGE-2: iptables v1.8.10 (nf_tables): Chain 'DOCKER-ISOLATION-STAGE-2' does not exist
```

## Solução: Usar Docker Run em vez de Docker Compose

Em vez de usar `docker-compose`, execute os containers manualmente:

### 1. PostgreSQL (Banco de dados)
```bash
docker run -d --name blog-db \
  -e POSTGRES_USER=bloguser \
  -e POSTGRES_PASSWORD=blogpassword \
  -e POSTGRES_DB=blogdb \
  -v /caminho/para/data/pgdata:/var/lib/postgresql/data \
  -p 5433:5432 \
  postgres:12
```

**Notas:**
- A porta 5433 do host mapeia para 5432 do container (evita conflito com PostgreSQL do host, se houver)
- O volume persiste dados entre reinícios

### 2. Web (CakePHP)
```bash
# Primeiro, build da imagem
cd /caminho/para/blog-to-dev
docker build -t blog-web .

# Depois, rodar o container
docker run -d --name blog-web \
  -p 8182:80 \
  -v /caminho/para/blog-to-dev:/var/www/html \
  blog-web
```

**Notas:**
- Acesso: http://localhost:8182
- O container consegue acessar o PostgreSQL via `172.17.0.1:5433` (gateway do Docker)

### 3. Importar Schema do Banco

```bash
cat db/schema_postgres.sql | docker exec -i blog-db psql -U bloguser -d blogdb
```

Isto criará as tabelas necessárias:
- `users`
- `posts`

### 4. Criar Usuário Admin

```bash
docker exec -it blog-db psql -U bloguser -d blogdb -c \
"INSERT INTO users (username, password, role, created, modified) 
VALUES ('admin', '\$2y\$10\$ZV3W5xjfwAMl5yUQrqOqU.ww.UBJWnEjD8g5WlBnFq9gZJR2d/8TK', 'admin', NOW(), NOW());"
```

- Usuário: `admin`
- Senha: `password`
- Hash: bcrypt (compatível com CakePHP)

## Parar e Limpar

```bash
# Parar containers
docker stop blog-db blog-web

# Remover containers
docker rm blog-db blog-web

# Remover volume (cuidado! Apaga dados do banco)
docker volume prune -f
```

## Verificação

### Status dos Containers
```bash
docker ps -a
```

### Logs
```bash
docker logs blog-db
docker logs blog-web
```

### Teste de Conectividade
```bash
# Do container web para o banco
docker exec blog-web php -r "
\$pdo = new PDO('pgsql:host=172.17.0.1;port=5433;dbname=blogdb', 'bloguser', 'blogpassword');
echo 'Conectado!\n';
"
```

## Acesso à Aplicação

- **URL:** http://localhost:8182
- **Login:** admin / password
- **Dashboard:** http://localhost:8182/posts/dashboard

## Notas Importantes

1. O arquivo `app/Config/database.php` foi configurado para usar `172.17.0.1:5433` (gateway do Docker para host)
2. O container web foi configurado com entrypoint script que corrige permissões de cache
3. Caso o banco esteja corrompido, remova `data/pgdata` e recrie do zero
