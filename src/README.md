# Microserviço de Gerenciamento de Tarefas (To-Do List)

## Visão Geral  
Este projeto é uma API RESTful desenvolvida com **Laravel 12**, autenticação via **JWT**, aplicação de boas práticas de arquitetura (Services, Repositories, Policies), e documentação automática com **Swagger/OpenAPI**.  
Está estruturado para rodar em um ambiente **Dockerizado**.

## Tecnologias Utilizadas

- PHP 8.2 / Laravel 12
- Docker (NGINX, PHP-FPM, MySQL)
- JWT Auth (`tymon/jwt-auth`)
- Laravel API Resource
- Enum nativo + Policy + Repository Pattern
- Swagger/OpenAPI (`l5-swagger`)

---

## Instalação

### 1. Clonar o repositório
```sh
git clone https://github.com/heberalmeida/todo-api
cd todo-api
cp .env.example .env
```

### 2. Subir os containers
```sh
docker-compose up -d --build
```

### 3. Acessar o container do backend
```sh
docker exec -it todo-app bash
```

### 4. Instalar dependências e configurar a aplicação
```sh
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
```

---

## Autenticação

A autenticação é baseada em tokens JWT. As rotas estão separadas entre públicas e protegidas.

### Endpoints públicos

| Método | Rota            | Descrição                   |
|--------|------------------|-----------------------------|
| POST   | /api/register    | Registrar novo usuário      |
| POST   | /api/login       | Autenticar e obter token    |

### Endpoints protegidos (requer token JWT)

| Método | Rota              | Descrição                            |
|--------|-------------------|----------------------------------------|
| POST   | /api/logout        | Fazer logout                          |
| GET    | /api/tasks         | Listar tarefas do usuário autenticado |
| POST   | /api/tasks         | Criar nova tarefa                     |
| GET    | /api/tasks/{id}    | Exibir tarefa específica              |
| PUT    | /api/tasks/{id}    | Atualizar tarefa                      |
| DELETE | /api/tasks/{id}   | Remover tarefa                        |

---

## Documentação da API

A documentação da API é gerada automaticamente com **Swagger/OpenAPI** e pode ser acessada nos seguintes ambientes:

### Ambiente local
Para executar os testes:
```sh
php artisan l5-swagger:generate
```

[http://localhost:8010/api/documentation](http://localhost:8010/api/documentation)

### Demo online
[https://todo.heber.com.br/api/documentation](https://todo.heber.com.br/api/documentation)

---

## Testes Automatizados

Para executar os testes:
```sh
php artisan test
```

---


## Licença

Este projeto é distribuído sob a licença MIT.