<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pedido_model');
    }

    public function index() {
        $data['title'] = 'Gerenciar Pedidos';
        $data['pedidos'] = $this->pedido_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($id) {
        $data['pedido'] = $this->pedido_model->get_by_id($id);
        
        if (empty($data['pedido'])) {
            show_404();
        }
        
        $data['itens'] = $this->pedido_model->get_itens($id);
        $data['title'] = 'Detalhes do Pedido #' . $id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/view', $data);
        $this->load->view('templates/footer');
    }
}