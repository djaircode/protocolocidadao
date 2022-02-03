## Sobre

Sistema de Protocolos. Em desenvolvimento. 

Esse sistema foi desenvolvido usando a framework [Laravel](https://laravel.com/), na versão 8.x e usa como front-end [Bootstrap 4.6](https://getbootstrap.com/).

Faz uso também das seguintes bibliotecas:

- [laravel-fpdf](https://github.com/codedge/laravel-fpdf)
- [typeahead](https://github.com/corejavascript/typeahead.js)
- [bootstrap-datepicker](https://github.com/uxsolutions/bootstrap-datepicker)

## Notas
- Utilizo os temas do site [bootswatch](https://bootswatch.com/), os temas estão dentro da pasta public/css, basta alterar os layouts das páginas blade dentro da pasta resources/layouts
- Na raiz do projeto o arquivo protocolo.mdj pode ser aberto no software [StarUML](https://staruml.io/), esse arquivo contém os diagrams em UML desse projeto.

## Objetivos

- Permitir localizar com facilidade onde se encontram os documentos físicos entre os setores, informando qual funcionário recebeu, quando e pra onte ele tramitou
- Permitir que informações sejam agregadas aos protocolos/tramitações através de textos ou arquivos anexados
- Oferecer uma opção segura para tramitação de documentos digitais entre funcionários e quando possível abolir a tramitação dos documentos físicos
- Oferecer estatísticas sobre todo processo de tramitação de documentos entre setores, podendo, por exemplo, saber a média de tempo de conclusão dos protolos, dentre outros a serem desenvolvidos

# Regras

- A edição do protocolo só pode ser feita pelo funcionário que criou o protocolo
- Somente o funcionário proprietário do protocolo pode concluir e reabrir um protocolo
- Não foi implementado a exclusão do protocolo, para essa tarefa deve-se concluir e escolher a opção cancelado na tela do protocolo, somente o funcionário que criou o protocolo pode fazer isso

- Os anexos só podem ser excluídos pelo funcionário que fez upload do arquivo, tanto no protocolo como na tramitação
- Os anexos podem ser incluídos ao protocolo na tela de edição de protocolo e na tela de edição da tramitação

## Requisitos

Os requisitos para executar esse sistema pode ser encontrado na [documentação oficial do laravel](https://laravel.com/docs/8.x):

- PHP >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Funcionalidades


## Contribuições


## Guia de intalação

Requer:

- Servidor apache com banco de dados MySQL instalado, se aplicável, conforme requisitos mínimos
- [Composer](https://getcomposer.org/download/) instalado
- [Git client](https://git-scm.com/downloads) instalado

Dica: [CMDER](https://cmder.net/) é um substituto do console (prompt) de comandos do windows que já vem com o git client dentre muitas outras funcionalidades

### clonar o reposítório

git clone https://github.com/erisilva/acl80.git

não esquecer de usar o composer update para fazer download das libs do framework

### criar o banco de dados

para mysql

CREATE DATABASE nome_do_banco_de_dados CHARACTER SET utf8 COLLATE utf8_general_ci;

### configurações iniciais

criar o arquivo .env de configurações:

php -r "copy('.env.example', '.env');"

editar o arquivo .env com os dados de configuração com o banco.

gerando a key de segurança:

php artisan key:generate

iniciando o store para os anexos:

php artisan storage:link

### migrações

php artisan migrate --seed

Serão criados 4 usuários de acesso ao sistema, cada um com um perfíl de acesso diferente.

Login: adm@mail.com senha:123456, acesso total.
Login: gerente@mail.com senha:123456, acesso restrito.
Login: operador@mail.com senha:123456, acesso restrito, não pode excluir registros.
Login: leitor@mail.com senha: 123456, somente consulta.

### executando

php artisan serve

## Licenças

Código aberto licenciado sob a [licença MIT](https://opensource.org/licenses/MIT).