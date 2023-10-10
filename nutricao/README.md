# Backend Challenge 20230105

Este projeto é uma aplicação para gerenciamento de informações nutricionais.

Apresentação: https://www.loom.com/share/510a6df8d95d4d0f85473bc7fe212818?t=374&sid=686c5664-23b8-4da6-bf97-40818188e456

## Tecnologias Utilizadas

- **Laravel:** Framework PHP moderno e poderoso.
- **MySQL:** Sistema de gerenciamento de banco de dados relacional.
- **Elasticsearch:** Motor de busca distribuído e altamente escalável.
- **Algolia:** Plataforma de pesquisa e descoberta como serviço.

## Instalação

Siga os passos abaixo para configurar e executar o projeto localmente.

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/projeto-nutricao.git
cd projeto-nutricao
```

### 2. Instalar dependências com Composer
```bash
composer install
```
### 3. Configurar o ambiente com Docker
```bash
docker-compose up -d
```
### 4. Iniciar o servidor PHP
```bash
php artisan serve
```
### 5. Para rodar os testes
```bash
php artisan test
```
### 6. Importar dados do Open Food Data
```bash
php artisan import:open-food-data
```
Este comando irá importar dados nutricionais do Open Food Data para o seu sistema.

### Uso
Após a conclusão dos passos de instalação, você pode acessar a aplicação em http://localhost:8000. Certifique-se de ter um navegador web e um cliente HTTP, como o cURL ou Postman, para testar e interagir com a API, se aplicável.

### Insomina
Tem um arquivo na raiz com todas as rotas (Insomnia_2023-10-10.json)