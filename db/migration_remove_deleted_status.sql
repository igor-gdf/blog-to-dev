-- Migração: Remove status 'deleted' do ENUM post_status
-- Execute: cat db/migration_remove_deleted_status.sql | docker exec -i blog-db psql -U bloguser -d blogdb

-- Primeiro, atualiza posts com status 'deleted' para usar deleted_at
UPDATE posts 
SET deleted_at = modified, status = 'draft'
WHERE status = 'deleted' AND deleted_at IS NULL;

-- Cria novo ENUM sem 'deleted'
DO $$ 
BEGIN
    -- Remove default temporariamente
    ALTER TABLE posts ALTER COLUMN status DROP DEFAULT;
    
    -- Cria tipo temporário
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'post_status_new') THEN
        CREATE TYPE post_status_new AS ENUM ('draft', 'published');
    END IF;
    
    -- Altera coluna para usar novo tipo
    ALTER TABLE posts 
        ALTER COLUMN status TYPE post_status_new 
        USING status::text::post_status_new;
    
    -- Remove tipo antigo e renomeia novo
    DROP TYPE IF EXISTS post_status CASCADE;
    ALTER TYPE post_status_new RENAME TO post_status;
    
    -- Restaura default
    ALTER TABLE posts ALTER COLUMN status SET DEFAULT 'draft'::post_status;
    
END $$;

-- Verifica resultado
SELECT 'Migração concluída! Status deleted removido.' as status;
SELECT DISTINCT status FROM posts;
