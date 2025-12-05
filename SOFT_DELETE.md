# Soft Delete - Guia de Implementação

## Resumo da Mudança

O sistema agora usa **soft delete** para usuários e posts em vez de deletar permanentemente do banco de dados.

## O que é Soft Delete?

Em vez de `DELETE FROM table WHERE id = X`, o registro é marcado como deletado com uma data/hora no campo `deleted_at`. Registros com `deleted_at != NULL` são considerados "deletados" mas ainda existem no banco.

**Vantagens:**
- ✅ Recuperação de dados deletados acidentalmente
- ✅ Auditoria e histórico completo
- ✅ Preserva integridade referencial
- ✅ Relatórios sobre dados deletados

## Migração do Banco de Dados

Execute o comando abaixo para adicionar a coluna `deleted_at` na tabela `users`:

```bash
cat db/migration_add_soft_delete.sql | docker exec -i blog-db psql -U bloguser -d blogdb
```

**O que a migração faz:**
1. Adiciona coluna `deleted_at` em `users` (se não existir)
2. Cria índices para melhor performance nas buscas
3. Verifica que tudo foi criado corretamente

## Alterações no Código

### Models

**User.php:**
- ✅ Método `softDelete($id)` - marca usuário como deletado
- ✅ Método `restore($id)` - restaura usuário deletado
- ✅ `getFilteredUsers()` - filtro automático para excluir deletados

**Post.php:**
- ✅ Método `deletePost()` - já usa soft delete
- ✅ Método `restorePost()` - restaura post deletado
- ✅ Todos métodos de busca filtram `deleted_at => null`

### Controllers

**UsersController:**
- ✅ `admin_delete()` - usa `softDelete()` em vez de `delete()`
- ✅ `delete()` - usa `softDelete()` em vez de `delete()`
- ✅ `admin_index()` - filtra usuários deletados

**PostsController:**
- ✅ `delete()` - usa `saveField('deleted_at', ...)` em vez de `delete()`
- ✅ `dashboard()` - filtra posts deletados
- ✅ `admin_index()` - filtra posts deletados
- ✅ `index()` - já tinha filtro `deleted_at => null`

## Como Usar

### Deletar Usuário (Soft Delete)
```php
// No controller
$this->User->softDelete($userId);

// SQL executado:
// UPDATE users SET deleted_at = '2025-12-05 10:30:00' WHERE id = X
```

### Deletar Post (Soft Delete)
```php
// No controller
$this->Post->id = $postId;
$this->Post->saveField('deleted_at', date('Y-m-d H:i:s'));

// SQL executado:
// UPDATE posts SET deleted_at = '2025-12-05 10:30:00' WHERE id = X
```

### Buscar Apenas Não Deletados
```php
// Automático em getFilteredUsers()
$users = $this->User->getFilteredUsers();

// Manual
$posts = $this->Post->find('all', [
    'conditions' => ['Post.deleted_at' => null]
]);
```

### Restaurar Registro Deletado (Admin)
```php
// Usuário
$this->User->restore($userId);

// Post
$this->Post->restorePost($postId, $currentUser);
```

## Testando

### 1. Execute a migração
```bash
cat db/migration_add_soft_delete.sql | docker exec -i blog-db psql -U bloguser -d blogdb
```

### 2. Verifique a coluna foi criada
```bash
docker exec -it blog-db psql -U bloguser -d blogdb -c "\\d users"
docker exec -it blog-db psql -U bloguser -d blogdb -c "\\d posts"
```

Deve aparecer:
```
 deleted_at | timestamp without time zone | | default NULL
```

### 3. Teste deletar um usuário
1. Acesse http://localhost:8182/users/admin_index (como admin)
2. Delete um usuário autor
3. Verifique no banco:
```bash
docker exec -it blog-db psql -U bloguser -d blogdb -c "SELECT id, username, deleted_at FROM users;"
```

### 4. Teste deletar um post
1. Crie um post no dashboard
2. Delete o post
3. Verifique no banco:
```bash
docker exec -it blog-db psql -U bloguser -d blogdb -c "SELECT id, title, deleted_at FROM posts;"
```

## Verificação SQL Direta

```sql
-- Ver todos usuários (incluindo deletados)
SELECT id, username, role, deleted_at FROM users;

-- Ver apenas usuários ativos
SELECT id, username, role FROM users WHERE deleted_at IS NULL;

-- Ver apenas usuários deletados
SELECT id, username, role, deleted_at FROM users WHERE deleted_at IS NOT NULL;

-- Restaurar usuário manualmente (se necessário)
UPDATE users SET deleted_at = NULL WHERE id = X;

-- Mesma lógica para posts
SELECT id, title, status, deleted_at FROM posts;
```

## Rollback (se necessário)

Se precisar voltar atrás:

```sql
-- Remove coluna deleted_at de users
ALTER TABLE users DROP COLUMN IF EXISTS deleted_at;

-- Remove índices
DROP INDEX IF EXISTS idx_users_deleted_at;
DROP INDEX IF EXISTS idx_posts_deleted_at;
```

Depois reverta as mudanças nos arquivos:
- `app/Model/User.php`
- `app/Model/Post.php`
- `app/Controller/UsersController.php`
- `app/Controller/PostsController.php`

## Monitoramento

### Ver registros deletados hoje
```sql
SELECT COUNT(*) as total_deletados_hoje
FROM users 
WHERE deleted_at::date = CURRENT_DATE;
```

### Ver posts deletados por usuário
```sql
SELECT u.username, COUNT(p.id) as posts_deletados
FROM users u
LEFT JOIN posts p ON p.user_id = u.id AND p.deleted_at IS NOT NULL
GROUP BY u.username;
```

## Observações Importantes

⚠️ **Backup**: Faça backup antes da migração
⚠️ **Testes**: Teste em desenvolvimento antes de produção
⚠️ **Espaço**: Soft delete ocupa mais espaço (registros não são removidos)
⚠️ **Performance**: Use índices para manter queries rápidas

✅ **Implementado com sucesso!**
