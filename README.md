Mini ERP - Sistema de Controle de Pedidos, Produtos, Cupons e Estoque

Este é um mini sistema ERP desenvolvido em PHP utilizando o framework CodeIgniter 3, com Bootstrap para o frontend e jQuery para interações do usuário.
Funcionalidades

    Gerenciamento de Produtos
        Cadastro de produtos com preço e estoque
        Suporte a variações de produtos
        Controle de estoque para cada variação
    Carrinho de Compras
        Adição de produtos ao carrinho
        Atualização de quantidades
        Cálculo de frete baseado em regras de valores
        Aplicação de cupons de desconto
    Gerenciamento de Cupons
        Criação de cupons com código único
        Suporte a descontos percentuais ou valores fixos
        Definição de valor mínimo para aplicação
        Controle de data de validade
    Checkout e Finalização de Pedidos
        Preenchimento de dados de entrega
        Integração com API de CEP (ViaCEP)
        Envio de e-mail de confirmação
    Webhook para Atualizações de Status
        Atualização de status de pedidos via API
        Tratamento de pedidos cancelados

Requisitos

    PHP 7.3 ou superior
    MySQL 5.7 ou superior
    Servidor web (Apache ou Nginx)
    Extensões PHP: mysqli, curl, json

Instalação

    Clone este repositório:

    git clone https://github.com/seu-usuario/mini-erp.git

    Configure o ambiente:
        Crie um banco de dados MySQL
        Importe o arquivo SQL disponível em sql/mini_erp.sql
        Configure as credenciais do banco no arquivo application/config/database.php
        Ajuste a base_url no arquivo application/config/config.php
    Configure o servidor web para apontar para a pasta do projeto
        Certifique-se de que o mod_rewrite (ou equivalente) está habilitado para as URLs amigáveis
    Acesse o sistema via navegador

Estrutura do Projeto

    /application/controllers/ - Controladores do sistema
    /application/models/ - Modelos para acesso ao banco de dados
    /application/views/ - Arquivos de visualização (templates)
    /application/config/ - Arquivos de configuração
    /assets/ - Arquivos CSS, JavaScript, imagens, etc.

Regras de Negócio

    Regras de Frete:
        Subtotal entre R$ 52,00 e R$ 166,59: frete de R$ 15,00
        Subtotal maior que R$ 200,00: frete grátis
        Outros valores: frete de R$ 20,00
    Cupons:
        Validação de data de expiração
        Validação de valor mínimo
        Aplicação de desconto percentual ou fixo

Webhook para Atualização de Status

O sistema oferece um endpoint para atualização de status de pedidos via API:

POST /webhook/status

Payload esperado:

json

{
  "pedido_id": 123,
  "status": "entregue" // Valores possíveis: pendente, pago, enviado, entregue, cancelado
}

Resposta de sucesso:

json

{
  "sucesso": true,
  "mensagem": "Status atualizado com sucesso"
}

Melhorias Futuras

    A ser definido.

Autor
Tiago Lino

Desenvolvido como teste técnico.
Licença

Este projeto é licenciado sob a licença MIT - veja o arquivo LICENSE para mais detalhes.
