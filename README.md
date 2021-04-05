<h1 align="center">Bem-Vindo ao payment-app 👋</h1>

> API para aplicativo de pagamentos

# Pré-requisitos

- php >=7.3.27
- laravel >=8.35.1

# Configuração

```sh
cp .env.example .env
php artisan migrate
```

# Endpoints para requisições:

## Cadastro de usuários:

> Este método serve para cadastrar usuários.

```
POST: localhost/api/user
```

Campos:
 - name: Nome do usuário
 - cpf_cnpj: CPF ou CNPJ do usuário, em caso de CNPJ significa que o usuário é um lojista
 - email: E-mail do usuário
 - password: Senha do usuário

Exemplo de requisição:
```json
{
    "name": "Nome do Usuário",
    "cpf_cnpj": "11111111111",
    "email": "email@email.com.br",
    "password": "minha_senha_muito_segura"
}
```

> Obs.: Ao cadastrar um usuário vai ser feito o cadastro da carteira automaticamente.
 
## Atualização dos valores na carteira de um usuário:

> Este método serve para creditar valores na carteira de um usuário.

```
PUT: localhost/api/wallet/add_funds
```

Campos:
 - user_id: ID do usuário
 - amount: Valor a ser creditado

Exemplo de requisição:
```json
{
    "user_id": "1",
    "amount": "1000"
}
```

## Transações entre usuários:

> Este método serve para realizar transações entre usuários.

```
POST: localhost/api/transaction
```

Campos:
 - amount: Valor a ser transferido
 - payer: Usuário de origem
 - payee: Usuário de destino

Exemplo de requisição:
```json
{
    "amount": 200,
    "payer": 1,
    "payee": 2
}
```

# Autor

👤 **Fabrício Pedroso Nunes**

* GitHub: [@tiobri](https://github.com/tiobri)
* LinkedIn: [@fabriciopedrosonunes](https://linkedin.com/in/fabriciopedrosonunes)

***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_
