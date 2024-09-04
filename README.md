
## Sobre a aplicação

Projeto IP4Y Task Manager API, é uma API REST com objetido de organizar projetos e as tarefas resultante de cada projeto.



# Pré requisito 

PHP 8.2 ou Maior
MySql

# Ferramentas utilizadas

> Para desenvolvimento (codagem) foi utilizado o VSCode de custo zero, maior familiaridade, recursos adcionais de fácil compreeção e vasta comunidade
> SGBD Mysql (vide desvios)
> Client SQBD visual foi usitlizado o Dbeaver, de utilização intuitiva, consome pouca mpemoria em comparação ao Workbench e atende as necessidades
> Core de consumo da API  utilizado foi o insomnia , de menor consumo de memória e fácil utilização.  

# Instalação

para baixar o projeto basta executar o seguinte comendo : 

>> git clone https://github.com/M2fSolucoes/ip4y-task-manager-api.git

a pos a clonagem acesse a pasta ip4y-task-manager-api e execute o composer para baixar as dependencias do projeto :

>> composer install

Após a o término do processo do composer efetua as configurações das váriáveis de ambiente

# Configuração da conexão com banco de dados 

Efetue a configuração de conexão com SGBD, para isso faça uma cópia o arquivo .env.example para .env também da raíz co projeto. 
Nele encontre os parâmetros abaixo e preecha os com os dados do servidor do SGBD.

> DB_CONNECTION=mysql
> DB_HOST= <HOST_DO_SERVIDOR_MYSQL>
> DB_PORT= <PORTA_DO_MYSQL>
> DB_DATABASE= <NOME_DO_BANCO_DE_DADOS>
> DB_USERNAME= <USUÁRIO_DO_BANCO_DE_DADOS>
> DB_PASSWORD= <SENHA_USUÁRIO_DO_BANCO_DE_DADOS>

# Cofiguração do servidor SMTP

Também deve ser feito a configuração de conexão com o servidor SMTP para envio de e-mail para recuperação de senha, atribuição de tarefas e alteração de status de uma tarefa

> MAIL_MAILER=smtp
> MAIL_HOST=<HOST_DO_SERVIDOR_SMTP>
> MAIL_PORT=<PORTA_DO_SMTPL>
> MAIL_USERNAME=<<USUÁRIO_SMTP>
> MAIL_PASSWORD=<SENHA_USUÁRIO_SMTP>
> MAIL_ENCRYPTION=<TIPO_DE_CRIPTOGRAFIA_SE_HOUVER>

# Fila 

Altere o parâmentro de fila para database para que os e-mails sejam processados em fila no segundo plano 

> QUEUE_CONNECTION=database

Para a execução das filas de e-mails é necessário : 

Se servidor de aplicação linux , instale o supervisor conforme documentação ofocial Laravel, leia https://laravel.com/docs/9.x/queues#supervisor-configuration , ou como alternativa crie um crontab.

> * * * * * php <pasta_do_projeto>/artisan schedule:run >> /dev/null 2>&1   (*)

Se servidor MS Windows crie uma Task Scheduler "cron" com execução a cada segundo para o comando :

> php <pasta_do_projeto>/artisan schedule:run >> /dev/null 2>&1 (*)

Dessa forma o Scheduler do Laravel (app/Console/Kernel.php) será executado a cada segundo e nele há uma instrução para executar a fila caso haja "JOB" aguardando 


# Criando banco de dados, tabelas e iniciando usuários padrões

Após as configurações efetuadas execute o migrate para criar o banco de dados e suas tabelas

>> php artisan migrate

Crie os usuários padrões para teste da aplicação utilizando a Seeder 

>> php artisan seed:db --class=UserSeeder

# Envio de email em segundo plano

Os e-mails de notificação de tarefas e mudança de status de tarefa são gerenciado por filas e  desde sua origem é executado em segundo plano atráves de um Observador. 

Observador (Observer -  TaskObserver) monitora a model TaskUsers e todo evento de atribuição de uma tarefa ao usuário o mesmo invoca o envio de e-mail através de uma classe do tipo JOB (MailTaskUserJob) no método dispatch.

Da mesma forma é monitorada a model Tasks no evento update na classe Observer (TaskUserObserver), toda ves que a field "status" é alterada  o mesmo invoca o envio de e-mail através de uma classe do tipo JOB (MailTaskStatusJob) e no método dispatch.

Dessa forma o ennvio de e-mail fica trasparente e elegante diminuindo o processamento em tempo de requisição da classe TaskRepository.

# Camadas de processamento

Será verificado que nenhuma interação ao banco de dados é executada na camada do controlador, essa responsábilidade é passada para o repositório (app/Repositories), dessa forma cria-se organização e fascilita a manutenção além de tornar o código mais elegante.


# Desvios 

    SGBD MySQL foi utilizado em substituição ao requerido MSSQL SERVER devido : 
    
    1) A falta da ferramenta no ambiente de de desenvolvimento;
    2) Falta de familiaridade com o SGBD 


# Collection 

A collection com os endpoints encontra-se na pasta raiz do projeto **ip4y-tark-manager-api.har**

# Bugs não corrigidos nessa versão 

Ao criar  um novo usuário e houver algum tipo de divergencia a execeção é redirecionada para uma página WEB Padrão , requer analise da falha.

# Documetação da API

https://documenter.getpostman.com/view/7985438/2sAXjNYWHS
