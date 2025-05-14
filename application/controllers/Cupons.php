<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cupom_model');
    }

    public function index() {
        $data['cupons'] = $this->cupom_model->get_all();
        $data['title'] = 'Gerenciar Cupons';
        
        $this->load->view('templates/header', $data);
        $this->load->view('cupons/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('codigo', 'Código', 'required|is_unique[cupons.codigo]');
        $this->form_validation->set_rules('desconto', 'Desconto', 'required|numeric');
        $this->form_validation->set_rules('tipo_desconto', 'Tipo de Desconto', 'required');
        $this->form_validation->set_rules('valor_minimo', 'Valor Mínimo', 'required|numeric');
        $this->form_validation->set_rules('data_validade', 'Data de Validade', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Adicionar Novo Cupom';
            
            $this->load->view('templates/header', $data);
            $this->load->view('cupons/create', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'codigo' => strtoupper($this->input->post('codigo')),
                'desconto' => $this->input->post('desconto'),
                'tipo_desconto' => $this->input->post('tipo_desconto'),
                'valor_minimo' => $this->input->post('valor_minimo'),
                'data_validade' => $this->input->post('data_validade'),
                'ativo' => $this->input->post('ativo') ? TRUE : FALSE
            ];
            
            $this->cupom_model->create($data);
            
            $this->session->set_flashdata('success', 'Cupom adicionado com sucesso!');
            redirect('cupons');
        }
    }

    public function edit($id) {
        $data['cupom'] = $this->cupom_model->get_by_id($id);
        
        if (empty($data['cupom'])) {
            show_404();
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('codigo', 'Código', 'required');
        $this->form_validation->set_rules('desconto', 'Desconto', 'required|numeric');
        $this->form_validation->set_rules('tipo_desconto', 'Tipo de Desconto', 'required');
        $this->form_validation->set_rules('valor_minimo', 'Valor Mínimo', 'required|numeric');
        $this->form_validation->set_rules('data_validade', 'Data de Validade', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Editar Cupom: ' . $data['cupom']->codigo;
            
            $this->load->view('templates/header', $data);
            $this->load->view('cupons/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'codigo' => strtoupper($this->input->post('codigo')),
                'desconto' => $this->input->post('desconto'),
                'tipo_desconto' => $this->input->post('tipo_desconto'),
                'valor_minimo' => $this->input->post('valor_minimo'),
                'data_validade' => $this->input->post('data_validade'),
                'ativo' => $this->input->post('ativo') ? TRUE : FALSE
            ];
            
            $this->cupom_model->update($id, $data);
            
            $this->session->set_flashdata('success', 'Cupom atualizado com sucesso!');
            redirect('cupons');
        }
    }

    public function delete($id) {
        $cupom = $this->cupom_model->get_by_id($id);
        
        if (empty($cupom)) {
            show_404();
        }
        
        $this->cupom_model->delete($id);
        $this->session->set_flashdata('success', 'Cupom removido com sucesso!');
        redirect('cupons');
    }
}