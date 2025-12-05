-- Migração: Adiciona soft delete para users
-- Execute: cat db/migration_add_soft_delete.sql | docker exec -i blog-db psql -U bloguser -d blogdb

-- Adiciona coluna deleted_at na tabela users se não existir
DO $$ 
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name = 'users' AND column_name = 'deleted_at'
    ) THEN
        ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL;
        CREATE INDEX IF NOT EXISTS idx_users_deleted_at ON users(deleted_at);
    END IF;
END $$;

-- Cria índice para deleted_at em posts se não existir
CREATE INDEX IF NOT EXISTS idx_posts_deleted_at ON posts(deleted_at);

-- Verifica resultado
SELECT 'Migração concluída com sucesso!' as status;
SELECT table_name, column_name, data_type 
FROM information_schema.columns 
WHERE table_name IN ('users', 'posts') AND column_name = 'deleted_at';
