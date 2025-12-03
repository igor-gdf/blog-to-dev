-- Schema SQL para PostgreSQL (blog-to-dev)
-- Ajuste o nome do banco de dados conforme necessário antes de importar

-- Cria tipos ENUM usados nas tabelas
DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'role_type') THEN
        CREATE TYPE role_type AS ENUM ('admin','author');
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'post_status') THEN
        CREATE TYPE post_status AS ENUM ('draft','published','deleted');
    END IF;
END$$;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role role_type NOT NULL DEFAULT 'author',
  created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  modified TIMESTAMP WITHOUT TIME ZONE NOT NULL
);

-- Tabela de posts
CREATE TABLE IF NOT EXISTS posts (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  status post_status NOT NULL DEFAULT 'draft',
  created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  modified TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  deleted_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS idx_posts_user_id ON posts(user_id);
CREATE INDEX IF NOT EXISTS idx_posts_created ON posts(created);

-- Tabela de sessões (CakePHP)
CREATE TABLE IF NOT EXISTS cake_sessions (
  id VARCHAR(255) PRIMARY KEY,
  data TEXT,
  expires INTEGER
);

-- Observações:
-- - Execute `CREATE DATABASE blog;` antes de importar, ou ajuste para o nome do seu DB.
-- - Para importar via psql: `psql -h <host> -U <user> -d <db> -f db/schema_postgres.sql`
-- - Se o serviço Postgres estiver em container Docker, use `docker exec -i <container> psql -U <user> -d <db> < db/schema_postgres.sql`

docker-compose ps
# ou
docker ps --filter "ancestor=postgres" --format "table {{.ID}}\t{{.Names}}\t{{.Ports}}"

# lista tabelas (antes da importação)
docker-compose exec -T db psql -U bloguser -d blogdb -c "\dt"

# importar (redireciona o arquivo local para o psql dentro do container)
docker-compose exec -T db psql -U bloguser -d blogdb < db/schema_postgres.sql

# lista tabelas (depois da importação)
docker-compose exec -T db psql -U bloguser -d blogdb -c "\dt"