# teste-situacional

## AMBIENTE DOCKER

### Configuração Inicial

1. **Criar o arquivo `.env` para o Docker:**

```bash
cp .env.sample .env
```

2. **Editar as variáveis no arquivo `.env`:**

```
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=
MYSQL_ROOT_PASSWORD=
```

3. **Adicionar uma entrada ao arquivo hosts do seu sistema operacional:**

```bash
127.0.0.1   api.testesituacional.com.br
```

### Iniciar o Docker

Para iniciar os containers pela primeira vez, use o comando:

```bash
docker compose up -d
```

### Acessar container do php

Para acessar o terminal do container PHP (o nome pode variar, como teste_situacional-php-fpm-1), execute:

```bash
docker exec -it teste_situacional-php-fpm-1 /bin/bash
```

**Observação:** O nome do container pode variar. Verifique o nome correto com:
```bash
docker ps
```