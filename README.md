# Sistema Agropecuário

## 🚀 Visão Geral

Sistema completo de gestão agropecuária utilizando **Laravel (backend)** e **Vue**, com conteinerização com Docker e geração de relatórios exportáveis.

## Instalação

Primeiro clone o repositório

```bash
  git clone https://github.com/FalaAiMatheus/agricultural-system.git
  cd agricultural-system
```

Após isso é necessário que você possua o **Docker**, caso não tenha pode ir na documentação oficial _(https://docs.docker.com/engine/install/)_.

## Configuração do banco de dados

Antes de subir os containers, vá até a pasta /conf/postgres e:

- Copie o arquivo .env.example para .env.

- Edite as variáveis de ambiente conforme o seu ambiente.

- Para rodar o ambiente você precisara primeiro configurar as variáveis ambiente.

Primeiro no ambiente **PHP**

No diretório /api, copie o .env.example para .env e configure as variáveis:

`DB_HOST="postgres-db"`

`DB_PORT=5432`

`DB_DATABASE=nome_do_banco`

`DB_USERNAME=nome_do_user`

`DB_PASSWORD=senha`

**Lembrando tem que ser os mesmos valores do /conf/postgres/**

Agora no ambiente web **(front)** você precisara copiar o .env.example e colocar a seguinte variável

No diretório /web, copie o .env.example para .env e adicione:

`VITE_API_URL="http://localhost:8000/api"`

Edite as variáveis de ambiente no .env do PHP:

`BROWSERSHOT_NODE_BINARY="`

`BROWSERSHOT_NPM_BINARY=`

`BROWSERSHOT_CHROME_PATH=`

Por padrão os valores estarão como

`BROWSERSHOT_NODE_BINARY='/root/.nvm/versions/node/v22.20.0/bin/node'`

`BROWSERSHOT_NPM_BINARY='/root/.nvm/versions/node/v22.20.0/bin/npm'`

Já o Chrome Path é necessário que você entre no container php (abaixo o comando)

E baixe os seguintes pacotes

```bash
    docker exec -it api bash
    npm i
    npx puppeteer browsers install chrome
```

Após isso ele vai retornar algo como

```bash
   '/root/.cache/puppeteer/chrome/...'
```

Basta copiar e colocar no `BROWSERSHOT_CHROME_PATH`

Após isso entre dentro do container do PHP usando o mesmo comando acima comando

```bash
    docker exec -it api bash
```

Antes de tudo precisa gerar o APP KEY do Laravel para isso esteja dentro do container e execute

```bash
    php artisan key:generate
```

E logo após rode o seguinte comando

```bash
    php artisan migrate
```

Isso vai rodar as migrações do banco.

## Stack utilizada

**Front-end:** Vue, Vite, TailwindCSS, PrimveVue

**Back-end:** Laravel
