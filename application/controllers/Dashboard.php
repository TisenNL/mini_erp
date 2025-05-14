<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('produto_model');
        $this->load->model('pedido_model');
    }

    public function index() {
        $data['title'] = 'Mini ERP do Tiago Lino - Dashboard';
        $data['produtos'] = $this->produto_model->get_all();
        $data['pedidos'] = $this->pedido_model->get_all();
        
        // Contadores
        $data['total_produtos'] = count($data['produtos']);
        $data['total_pedidos'] = count($data['pedidos']);
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}