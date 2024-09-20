# Projeto de API - Livros e Clientes

## Requisitos

Antes de iniciar, você precisará ter os seguintes itens instalados:

- [Yii2 Framework](https://www.yiiframework.com/)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [PHP 8](https://www.php.net/)

## Instalação

Siga os passos abaixo para configurar o projeto:

1. Clone este repositório para o seu ambiente local:

   ```bash
   git clone https://github.com/judsonmb/library.git
   cd library
   ```
2. Rode o comando para instalar as dependências:

    ```bash
    composer install
    ```

3. Crie um banco de dados no mysql e o configure no arquivo config/db.php

4. Rode o comando para executar as migrações

    ```bash
    ./yii migrate
    ```

5. Agora você pode executar o servidor embutido do Yii para testar a API

    ```
    ./yii serve
    ```

6. A API estará disponível em http://localhost:8080

