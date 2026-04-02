# Sistema Changelog

## Sistema desenvolvido com CodeIgniter 4 para gerenciamento de changelog.

### Tecnologias utilizadas
- PHP 8+
- CodeIgniter 4
- MySQL
- Bootstrap (se estiver usando)

## Instalação do Sistema

Execute o comando abaixo para criar o projeto:

```
  composer create-project codeigniter4/appstarter changelog
```

## Caminho do Projeto

Acesse a pasta do projeto e instale as dependências:
  C:\sua_pasta\changelog
```  
  composer install
```

### 💡 Você pode usar o terminal do VSCode ou CMD

## Banco de Dados

Crie o banco de dados:

```
  CREATE DATABASE IF NOT EXISTS changelog;
```

Depois configure o arquivo .env com os dados do seu banco:

```
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'http://localhost/changelog_manager/public/'
app.indexPage = ''
app.uriProtocol = 'REQUEST_URI'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = changelog_manager
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_general_ci

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------
session.driver = 'CodeIgniter\Session\Handlers\FileHandler'
session.savePath = WRITEPATH . 'session'
session.cookieName = ci_session
session.expiration = 7200

#--------------------------------------------------------------------
# LOG
#--------------------------------------------------------------------
logger.threshold = 4
```
## 🔄 Migrations

Execute o comando para criar as tabelas no banco:

```
  php spark migrate
```
## 🔄 Execução do Sistema

Inicie o servidor local:

```
php spark serve
```

Acesse no navegador:

```
http://localhost:8080
```

## 📌 Funcionalidades
- Cadastro de registros de changelog
- Listagem de alterações
- Controle de versões
-- (adicione aqui conforme seu sistema evoluir)
- Configurações adicionais
- Verifique permissões de escrita nas pastas /writable
- Configure corretamente o arquivo .env
- Certifique-se que o PHP possui as extensões necessárias (intl, mbstring, etc.)

### Licença

Este projeto está sob a licença MIT.
