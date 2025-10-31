# Sistema Simples de PDV (Ponto de Venda)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

> **Um sistema de Ponto de Venda (PDV) simples, leve e funcional, desenvolvido com PHP puro e JavaScript vanilla (sem frameworks).**  
> Ideal para pequenos comércios, feiras, lanchonetes ou uso em ambientes locais.

---

## Funcionalidades

- Cadastro rápido de produtos com código, nome, preço e estoque  
- Interface de vendas com carrinho dinâmico (JS vanilla)  
- Cálculo automático de subtotal, desconto e total  
- Finalização de venda com registro no banco de dados  
- Busca de produtos por código ou nome  
- Relatório simples de vendas do dia  
- Design responsivo e limpo (funciona em tablets e desktops)  
- Sem dependências externas — roda em qualquer servidor PHP + MySQL  

---

## Tecnologias Utilizadas

| Camada       | Tecnologia               |
|--------------|--------------------------|
| Backend      | PHP (7.4 ou superior)    |
| Frontend     | HTML, CSS, JavaScript (Vanilla) |
| Banco de Dados | MySQL                  |
| Servidor     | Apache / Nginx (XAMPP, WAMP, LAMP) |

> **Nenhum framework. Nenhuma biblioteca externa. Apenas código limpo e funcional.**

---

## Estrutura do Projeto
php_pdv/
├── index.php               # Tela principal do PDV
├── config/
│   └── database.php        # Configuração de conexão com o banco
├── assets/
│   ├── js/
│   │   └── pdv.js          # Lógica do carrinho e interações
│   └── css/
│       └── style.css       # Estilos da interface
├── includes/
│   ├── functions.php       # Funções auxiliares
│   └── header.php          # Cabeçalho comum
├── pages/
│   ├── produtos.php        # Gerenciamento de produtos
│   └── relatorio.php       # Relatório de vendas
├── db/
│   └── pdv_schema.sql      # Script SQL para criar o banco
├── venda.php               # Processa a finalização da venda
└── README.md               # Este arquivo


---

## Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7+
- Servidor web (Apache ou Nginx)
- Navegador moderno (Chrome, Firefox, Edge)

> Recomendado: [XAMPP](https://www.apachefriends.org/) (Windows/Mac/Linux)

---

## Instalação

1. **Clone o repositório**
   ```bash
   git clone https://github.com/fernandoRodrigues29/php_pdv.git
   cd php_pdv