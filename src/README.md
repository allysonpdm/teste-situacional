# PROJETO

## Configurando o projeto

1. **Criar o `.env` do projeto**

Copie o arquivo de exemplo para criar o arquivo de configuração (`.src/.env.sample`):

```bash
cd src &&
cp .env.sample .env
```

2. **Se você estiver usando o Docker, atualize as variáveis no `.src/.env` conforme as configurações do Docker:**

```text
DB_HOST=mysql
DB_DATABASE= [valor setado no env do docker]
DB_USERNAME= [valor setado no env do docker]
DB_PASSWORD= [valor setado no env do docker]
```

3. **Instalar as Dependências**

Para instalar as dependências do projeto execute o comando no container do projeto:

```bash
composer install
```

4. **Atualizar Configurações**

Execute os seguintes comandos no container do projeto para atualizar as configurações:

```bash
php artisan jwt:secret
php artisan optimize
```

## Testes do projeto

Para executar os testes, use o comando abaixo dentro do container do projeto:

```bash
php artisan test
```

**Nota:** Nunca execute os testes em um ambiente de produção.

### Migrations

Para executar as migrations e seeders, utilize o comando:

```bash
php artisan migrate --seed
```

### Links uteis 
- [Documentacao da  API](http://api.testesituacional.com.br/api/documentation)
- [Telescope](http://api.testesituacional.com.br/telescope)
