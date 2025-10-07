# Blog CakePHP 2.x

Sistema de blog desenvolvido em CakePHP 2.x com sistema de autenticação e controle de acesso.

## 🚀 Funcionalidades

- **Sistema de Posts**
  - CRUD completo (Create, Read, Update, Delete)
  - Status: rascunho, publicado, excluído
  - Soft delete (exclusão lógica)
  - Controle de acesso por usuário

- **Sistema de Usuários**
  - Autenticação (login/logout)
  - Cadastro de novos usuários
  - Controle de acesso baseado em roles (admin/user)
  - Hash seguro de senhas

- **Controle de Permissões**
  - Admins podem ver e gerenciar todos os posts
  - Usuários comuns só podem gerenciar seus próprios posts
  - Listagem de usuários restrita para admins

## 🛠️ Tecnologias

- **PHP**: 5.6 (legado)
- **Framework**: CakePHP 2.x
- **Banco de Dados**: PostgreSQL 12
- **Containerização**: Docker + Docker Compose
- **Servidor Web**: Apache

## 📦 Instalação com Docker

1. Clone o repositório:
```bash
git clone https://github.com/igor-gdf/blog-to-dev.git
cd blog-to-dev
```

2. Suba os containers:
```bash
docker-compose up -d
```

3. Acesse a aplicação:
- **URL**: http://localhost:8080
- **Banco de Dados**: localhost:5432

## 🗄️ Configuração do Banco

O projeto usa PostgreSQL com as seguintes configurações:
- **Host**: db (dentro do Docker)
- **Usuário**: bloguser
- **Senha**: blogpassword
- **Database**: blogdb

## 📁 Estrutura do Projeto

```
app/
├── Controller/          # Controllers da aplicação
│   ├── PostsController.php
│   └── UsersController.php
├── Model/              # Models da aplicação
│   ├── Post.php
│   └── User.php
├── View/               # Views da aplicação
│   ├── Posts/
│   │   ├── index.ctp
│   │   ├── view.ctp
│   │   └── add.ctp
│   └── Users/
│       ├── login.ctp
│       └── add.ctp
└── Config/             # Configurações
    ├── database.php
    ├── core.php
    └── routes.php
```

## 🔧 Desenvolvimento

### Executar comandos no container:
```bash
docker exec -it blog_cakephp_web bash
```

### Ver logs:
```bash
docker-compose logs -f
```

### Parar containers:
```bash
docker-compose down
```

## ⚠️ Notas Importantes

- Este projeto usa tecnologias legadas (PHP 5.6, CakePHP 2.x)
- Recomenda-se migração para versões mais recentes para produção
- Debug está habilitado para desenvolvimento


## Some Handy Links

[CakePHP](https://cakephp.org) - The rapid development PHP framework

[CookBook](https://book.cakephp.org) - THE CakePHP user documentation; start learning here!

[API](https://api.cakephp.org) - A reference to CakePHP's classes

[Plugins](https://plugins.cakephp.org) - A repository of extensions to the framework

[The Bakery](https://bakery.cakephp.org) - Tips, tutorials and articles

[Community Center](https://community.cakephp.org) - A source for everything community related

[Training](https://training.cakephp.org) - Join a live session and get skilled with the framework

[CakeFest](https://cakefest.org) - Don't miss our annual CakePHP conference

[Cake Software Foundation](https://cakefoundation.org) - Promoting development related to CakePHP


## Get Support!

[#cakephp](https://webchat.freenode.net/?channels=#cakephp) on irc.freenode.net - Come chat with us, we have cake

[Google Group](https://groups.google.com/group/cake-php) - Community mailing list and forum

[GitHub Issues](https://github.com/cakephp/cakephp/issues) - Got issues? Please tell us!

[Roadmaps](https://github.com/cakephp/cakephp/wiki#roadmaps) - Want to contribute? Get involved!


## Contributing

[CONTRIBUTING.md](CONTRIBUTING.md) - Quick pointers for contributing to the CakePHP project

[CookBook "Contributing" Section (2.x)](https://book.cakephp.org/2.0/en/contributing.html) [(3.x)](https://book.cakephp.org/3.0/en/contributing.html) - Version-specific details about contributing to the project
