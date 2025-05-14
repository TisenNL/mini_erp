<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pedido_model');
    }

    public function status() {
        // Verifica se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->output->set_status_header(405);
            echo json_encode(['erro' => 'Método não permitido']);
            return;
        }
        
        // Obtém o conteúdo da requisição
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        
        // Valida os dados recebidos
        if (!$data || !isset($data->pedido_id) || !isset($data->status)) {
            $this->output->set_status_header(400);
            echo json_encode(['erro' => 'Dados inválidos']);
            return;
        }
        
        $pedido_id = $data->pedido_id;
        $status = $data->status;
        
        // Valida status
        $status_validos = ['pendente', 'pago', 'enviado', 'entregue', 'cancelado'];
        
        if (!in_array($status, $status_validos)) {
            $this->output->set_status_header(400);
            echo json_encode(['erro' => 'Status inválido']);
            return;
        }
        
        // Verifica se o pedido existe
        $pedido = $this->pedido_model->get_by_id($pedido_id);
        
        if (!$pedido) {
            $this->output->set_status_header(404);
            echo json_encode(['erro' => 'Pedido não encontrado']);
            return;
        }
        
        // Atualiza o status do pedido
        $resultado = $this->pedido_model->atualizar_status($pedido_id, $status);
        
        if ($resultado) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado com sucesso']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['erro' => 'Falha ao atualizar status']);
        }
    }
}