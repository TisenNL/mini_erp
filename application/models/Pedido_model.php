<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('pedidos')->result();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('pedidos')->row();
    }

    public function create($data) {
        $this->db->insert('pedidos', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('pedidos', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('pedidos');
    }

    public function get_itens($pedido_id) {
        $this->db->select('pedido_itens.*, produtos.nome as produto_nome, variacoes.nome as variacao_nome');
        $this->db->from('pedido_itens');
        $this->db->join('produtos', 'produtos.id = pedido_itens.produto_id');
        $this->db->join('variacoes', 'variacoes.id = pedido_itens.variacao_id', 'left');
        $this->db->where('pedido_id', $pedido_id);
        
        return $this->db->get()->result();
    }

    public function adicionar_item($pedido_id, $data) {
        $this->db->insert('pedido_itens', $data);
        return $this->db->insert_id();
    }

    public function remover_item($id) {
        return $this->db->where('id', $id)->delete('pedido_itens');
    }

    public function calcular_frete($subtotal) {
        if ($subtotal >= 200) {
            return 0; // Frete grátis acima de R$ 200
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15; // Frete R$ 15 entre R$ 52 e R$ 166,59
        } else {
            return 20; // Frete R$ 20 para outros valores
        }
    }

    public function atualizar_status($pedido_id, $status) {
        if ($status == 'cancelado') {
            // Devolver itens ao estoque
            $itens = $this->get_itens($pedido_id);
            
            foreach ($itens as $item) {
                $this->load->model('estoque_model');
                $this->estoque_model->aumentar_estoque(
                    $item->produto_id, 
                    $item->variacao_id, 
                    $item->quantidade
                );
            }
            
            // Remover o pedido
            return $this->delete($pedido_id);
        } else {
            // Atualizar o status
            return $this->update($pedido_id, ['status' => $status]);
        }
    }
}