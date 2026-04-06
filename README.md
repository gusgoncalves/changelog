# Sistema Changelog

## Sistema desenvolvido com CodeIgniter 4 para gerenciamento de changelog.

### Tecnologias utilizadas
- PHP 8+
- CodeIgniter 4
- MySQL
- Bootstrap (se estiver usando)

## Informações Básicas
Se o que precisa é apenas configurar o changelog no sistema Legado, [Configuração](#-configuração)

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

---
## 🔧 Configuração
No sistema Legado crie as tabelas:

### Para Postgres
```sql
CREATE TABLE changelog (
    id SERIAL PRIMARY KEY,
    versao VARCHAR(20) NOT NULL,
    titulo VARCHAR(255),
    descricao TEXT,
    ordem INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_changelog_versao ON changelog (versao);
```
### Para MYSQL
```sql
CREATE TABLE changelog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    versao VARCHAR(20) NOT NULL,
    titulo VARCHAR(255),
    descricao TEXT,
    ordem INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_changelog_versao ON changelog (versao);
```
### Tabela de usuários do sistema Legado

```sql
ALTER TABLE usuarios_do seu_sistema ADD COLUMN versao_visualizada VARCHAR(20);
```
### Adicionar uma constante no sistema legado
caminho codeigniter: ```application/config/constants.php```
```php
define('APP_VERSAO', '1.0.0'); //Número da sua versão que vai ser criado
```
### Criar um model ``` Changelog_model```

```php
<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Changelog_model extends CI_Model {

    public function get_by_versao($versao)
    {
        return $this->db
            ->where('versao', $versao)
            ->order_by('ordem', 'ASC')
            ->get('central_veiculo.changelog')
            ->result_array();
    }
    
    public function atualizar_versao($matricula, $versao)
    {
        $this->db->where('matricula', $matricula);

        return $this->db->update('central_veiculo.usuario', [
            'versao_visualizada' => $versao
        ]);
    }
    public function usuarioPorMatricula($matricula = null)
    {
        return $this->db
            ->where('matricula', $matricula)
            ->get('central_veiculo.usuario')
            ->result_array();
    }
}
```
Perceba que estamos mandando a matricula do usuário para fazer o where do select do banco, geralmente ou está na sessão, mas é melhor pega-la da tabela usuários do seu sistema
### Criar Controller ```Changelog```

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changelog extends MY_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->model('changelog_model');
	}

    public function confirmar()
    {
        $matricula = $this->session->userdata('matricula');
        $this->changelog_model->atualizar_versao($matricula, APP_VERSAO);

        $this->session->set_userdata('versao_visualizada', APP_VERSAO);

        echo json_encode(['status' => 'ok']);
    }
}
```
Perceba que eu estou pegando a matricula de uma sessão do login, mas pode ser de qualquer lugar que referencie o usuário
### Criação do controller Principal
*```em application/core```* crie ```MY_Controller.php``` 
Caso a versão do CodeIgniter for inferior a 4, se for a versão 4 ou superior, aí essa classe é criada no **BaseController**

```php
class MY_Controller extends CI_Controller {

    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('app_model');
        $this->load->model('Changelog_model');
 
        $versaoAtual = APP_VERSAO;
        if(isset($this->session->matricula)){
          $user  = $this->Changelog_model->usuarioPorMatricula($this->session->matricula);
          $versaoUser = $user[0]['versao_visualizada'];
        }else{
          $versaoUser = null;
        }
        $this->data['mostrarChangelog'] = false;
        $this->data['changelog'] = [];
        $this->data['versaoAtual'] = $versaoAtual;
        $this->data['versaoUser'] = $versaoUser;

        if ($versaoUser != $versaoAtual) {
            $this->data['mostrarChangelog'] = true;
            $this->data['changelog'] = $this->Changelog_model->get_by_versao($versaoAtual);
        }
    }

    protected function render_template($view, $dados = [])
    {
        // junta dados locais + globais
        $this->data = array_merge($this->data, $dados);

        // monta layout
        $this->load->view('header', $this->data);
        $this->load->view('menu', $this->data);
        $this->load->view($view, $this->data);
        $this->load->view('footer', $this->data);
    }
}
```

Note que sempre estamos analisando se a matricula do usuário existe, e sempre estou usando o Changelog_model

### No seu Controller index que inicia a aplicação faça a classe principal extender de MY_Controller:
```nota que se for a versão 4 do CodeIgniter não precisa fazer isso, pois as classes já extendem de BaseController```

Sua classe chama-se Comprar:
- class Comprar extends CI_Controller... mude para :
```class Comprar extends MY_Controller...```
Pois a classe MY_Controller extende de CI_Controller
da mesma forma a classe BaseController,

### Ja no seu index do controller principal do seu sistema:
- troque o seu carregamento da view 
```php
 $this->load->view('sua_view', ["variavel1" => $variavel1, 'variavel2' => $variavel2]);
```
Por:
```php
$dados = [
	'variavel1'=> $variavel1,
	'variavel2'=> $variavel2,
	'titulo'=> 'Teste de sistema' 
];

$this->render_template('sua_view',$dados); //a variável $dados vem também lá do MY_Controller
```

### Na sua View inicial "sua_view"
 Adicione o modal na view ou crie uma referencia para o modal ex:
 ```php
	<?php include("modal/modal_chagelog.php"); ?>
```
imaginando que está na pasta * views/modal * e o arquivo se chame * modal_changelog.php *

```modal_changelog.php```

```html
<style>
.changelog-modal {
    border-radius: 8px;
}

.modal-header.bg-primary {
    background-color: #007bff !important;
    color: #fff;
}

.changelog-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.changelog-item:last-child {
    border-bottom: none;
}

.changelog-item h5 {
    font-weight: bold;
    margin-bottom: 5px;
}

.changelog-item p {
    margin-left: 25px;
    color: #555;
}

.badge-success {
    background-color: #28a745;
    margin-right: 5px;
}
</style>
<?php if (!empty($mostrarChangelog)): ?>
<div class="modal fade" id="modalChangelog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content changelog-modal">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    🚀 Novidades da versão <?= $versaoAtual ?>
                </h4>
                <small>Confira o que mudou no sistema</small>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <?php foreach ($changelog as $item): ?>
                    <div class="changelog-item">
                        <h5>
                            <span class="badge badge-success">✔</span>
                            <?= $item['titulo'] ?>
                        </h5>

                        <p><?= nl2br($item['descricao']) ?></p>
                    </div>
                <?php endforeach; ?>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button id="btnConfirmarChangelog" class="btn btn-success">
                    Entendi
                </button>
            </div>

        </div>
    </div>
</div>

<script>
$(function(){
    $('#modalChangelog').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');

    $('#btnConfirmarChangelog').click(function(){
        $.post('<?= base_url("changelog/confirmar") ?>', function(){
            $('#modalChangelog').modal('hide');
        });
    });
});
</script>

<?php endif; ?>
```
## essa informação fica dentro da sua view inicial na sessão dos scripts pois entende que o botão foi clicado em entendi para a execução da página inicial quando clicar  no botão do modal não esqueça de inserir :
```html
$('#btnConfirmarChangelog').click(function() {
			$.post('<?= base_url("changelog/confirmar") ?>', function() {
					$('#modalChangelog').modal('hide');
			});
	});
```

Agora cada vez que houver uma versão nova:
- Basta adicionar importar dos dados no sistema de changelog.
- Exportar o SQL com os ```inserts``` para a tabela do legado.
- Inserir os dados no banco de dados Legado.
- Mudar o valor da constant em DEFINE

Dessa forma todos os usuários que não tiverem clicado no botão ENTENDI verão o modal com as informações

## Licença

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

