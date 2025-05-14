<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupom_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('cupons')->result();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('cupons')->row();
    }

    public function get_by_codigo($codigo) {
        return $this->db->where('codigo', $codigo)->get('cupons')->row();
    }

    public function create($data) {
        $this->db->insert('cupons', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('cupons', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('cupons');
    }

    public function validar_cupom($codigo, $subtotal) {
        $hoje = date('Y-m-d');
        
        $cupom = $this->db->where('codigo', $codigo)
                          ->where('ativo', TRUE)
                          ->where('data_validade >=', $hoje)
                          ->get('cupons')->row();
        
        if (!$cupom) {
            return FALSE;
        }
        
        // Verificar valor mínimo
        if ($subtotal < $cupom->valor_minimo) {
            return FALSE;
        }
        
        return $cupom;
    }

    public function calcular_desconto($cupom, $subtotal) {
        if (!$cupom) {
            return 0;
        }
        
        if ($cupom->tipo_desconto == 'percentual') {
            return ($subtotal * $cupom->desconto) / 100;
        } else {
            return $cupom->desconto;
        }
    }
}