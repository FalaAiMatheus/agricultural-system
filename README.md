# Sistema Agropecuário

## 🚀 Visão Geral

Sistema completo de gestão agropecuária utilizando **Laravel (backend)** e **Vue**, com containerização com Docker e geração de relatórios exportáveis.

## Instalação

Primeiro clone o repositorio

```bash
  git clone https://github.com/FalaAiMatheus/agricultural-system.git
  cd agricultural-system
```

Após isso é necessário que você possua o **Docker**, caso não tenha pode ir na documentação oficial _(https://docs.docker.com/engine/install/)_.

Mas antes de rodar esse comando é necessário que você vá na pasta /conf/postgres e clone o .env.example e coloque as informações para que o container do banco seja rodado.

```bash
    docker compose up -d
```

Para rodar o ambiente você precisara primeiro configurar as variaveis ambiente.

Primeiro no ambiente **PHP**

Copie o .env.example e adicione os valores nas variaveis a seguir

`DB_HOST="postgres-db"`

`DB_PORT=5432`

`DB_DATABASE=nome_do_banco`

`DB_USERNAME=nome_do_user`

`DB_PASSWORD=senha`

**Lembrando tem que ser os mesmos valores do /conf/postgres/**

Agora pra configurar pra gerar PDF é necessário alterar essas variáveis

`BROWSERSHOT_NODE_BINARY="`

`BROWSERSHOT_NPM_BINARY=`

`BROWSERSHOT_CHROME_PATH=`

Por padrão os valores estarão como

`BROWSERSHOT_NODE_BINARY='/root/.nvm/versions/node/v22.20.0/bin/node'`

`BROWSERSHOT_NPM_BINARY='/root/.nvm/versions/node/v22.20.0/bin/npm'`

Já o Chrome Path é necessário que você entre no container php (abaixo o comando)

E baixe os seguintes pacotes

```bash
    docker exec -it service-php bash
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
    docker exec -it service-php bash
```

E rode o seguinte comando

```bash
    php artisan migrate
```

Isso vai rodar as migrações do banco.

Agora no ambiente web **(front)** você precisara copiar o .env.example e colocar a seguinte variavel

`VITE_API_URL="http://localhost:8000/api"`

Com tudo isso pronto, você já estará rodando seu ambiente 100% 😎

Agora basta entrar em http://localhost:5173/

## Stack utilizada

**Front-end:** Vue, Vite, TailwindCSS, PrimveVue

**Back-end:** Laravel
