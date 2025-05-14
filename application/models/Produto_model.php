<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('produtos')->result();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('produtos')->row();
    }

    public function create($data) {
        $this->db->insert('produtos', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('produtos', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('produtos');
    }

    public function get_with_estoque() {
        $this->db->select('produtos.*, estoque.quantidade, estoque.id as estoque_id');
        $this->db->from('produtos');
        $this->db->join('estoque', 'produtos.id = estoque.produto_id', 'left');
        $this->db->where('estoque.variacao_id IS NULL');
        return $this->db->get()->result();
    }

    public function get_with_variacoes($produto_id) {
        $produto = $this->get_by_id($produto_id);
        
        if (!$produto) {
            return null;
        }
        
        $variacoes = $this->db->where('produto_id', $produto_id)->get('variacoes')->result();
        
        foreach ($variacoes as &$variacao) {
            $estoque = $this->db->where('variacao_id', $variacao->id)->get('estoque')->row();
            $variacao->estoque = $estoque ? $estoque : (object)['quantidade' => 0, 'id' => null];
        }
        
        $produto->variacoes = $variacoes;
        
        // Obter estoque do produto principal (sem variação)
        $estoque_principal = $this->db->where('produto_id', $produto_id)
                            ->where('variacao_id IS NULL')
                            ->get('estoque')->row();
        
        $produto->estoque = $estoque_principal ? $estoque_principal : (object)['quantidade' => 0, 'id' => null];
        
        return $produto;
    }
}