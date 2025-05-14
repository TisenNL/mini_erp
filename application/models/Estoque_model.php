<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estoque_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_by_produto($produto_id) {
        return $this->db->where('produto_id', $produto_id)->get('estoque')->result();
    }

    public function get_by_variacao($variacao_id) {
        return $this->db->where('variacao_id', $variacao_id)->get('estoque')->row();
    }

    public function create($data) {
        $this->db->insert('estoque', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('estoque', $data);
    }

    public function update_quantidade($produto_id, $variacao_id, $quantidade) {
        $this->db->where('produto_id', $produto_id);
        
        if ($variacao_id) {
            $this->db->where('variacao_id', $variacao_id);
        } else {
            $this->db->where('variacao_id IS NULL');
        }
        
        $estoque = $this->db->get('estoque')->row();
        
        if ($estoque) {
            return $this->db->where('id', $estoque->id)
                            ->update('estoque', ['quantidade' => $quantidade]);
        } else {
            return $this->create([
                'produto_id' => $produto_id,
                'variacao_id' => $variacao_id,
                'quantidade' => $quantidade
            ]);
        }
    }

    public function diminuir_estoque($produto_id, $variacao_id, $quantidade) {
        $this->db->where('produto_id', $produto_id);
        
        if ($variacao_id) {
            $this->db->where('variacao_id', $variacao_id);
        } else {
            $this->db->where('variacao_id IS NULL');
        }
        
        $estoque = $this->db->get('estoque')->row();
        
        if ($estoque && $estoque->quantidade >= $quantidade) {
            return $this->db->where('id', $estoque->id)
                            ->update('estoque', ['quantidade' => $estoque->quantidade - $quantidade]);
        }
        
        return false;
    }

    public function aumentar_estoque($produto_id, $variacao_id, $quantidade) {
        $this->db->where('produto_id', $produto_id);
        
        if ($variacao_id) {
            $this->db->where('variacao_id', $variacao_id);
        } else {
            $this->db->where('variacao_id IS NULL');
        }
        
        $estoque = $this->db->get('estoque')->row();
        
        if ($estoque) {
            return $this->db->where('id', $estoque->id)
                            ->update('estoque', ['quantidade' => $estoque->quantidade + $quantidade]);
        } else {
            return $this->create([
                'produto_id' => $produto_id,
                'variacao_id' => $variacao_id,
                'quantidade' => $quantidade
            ]);
        }
    }

    public function verificar_disponibilidade($produto_id, $variacao_id, $quantidade) {
        $this->db->where('produto_id', $produto_id);
        
        if ($variacao_id) {
            $this->db->where('variacao_id', $variacao_id);
        } else {
            $this->db->where('variacao_id IS NULL');
        }
        
        $estoque = $this->db->get('estoque')->row();
        
        return ($estoque && $estoque->quantidade >= $quantidade);
    }
}
