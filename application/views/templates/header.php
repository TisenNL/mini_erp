<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Mini ERP</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        
        .alert-container {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            width: 350px;
        }
        
        .produto-card {
            transition: all 0.3s ease;
        }
        
        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
    </style>
    
    <!-- Sistema de gerenciamento de scripts que dependem do jQuery -->
    <script>
    // Criar um namespace global para scripts
    window.App = window.App || {};
    
    // Array para armazenar funções a serem executadas quando jQuery estiver pronto
    App.ready = [];
    
    // Função para adicionar scripts que dependem do jQuery
    function jQueryReady(fn) {
        if (typeof fn === 'function') {
            if (typeof jQuery !== 'undefined') {
                // Se jQuery já estiver carregado, execute imediatamente
                fn(jQuery);
            } else {
                // Caso contrário, adicione à fila para executar depois
                App.ready.push(fn);
            }
        }
    }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">Mini ERP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('produtos'); ?>">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('cupons'); ?>">Cupons</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('carrinho'); ?>">
                        <i class="fas fa-shopping-cart"></i> Carrinho
                        <?php if ($this->session->carrinho): ?>
                            <span class="badge badge-light"><?php echo count($this->session->carrinho); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="alert-container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
</div>

<div class="container mt-4">
    <h1 class="mb-4"><?php echo $title; ?></h1>