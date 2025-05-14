<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Variacao_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_by_produto($produto_id) {
        return $this->db->where('produto_id', $produto_id)->get('variacoes')->result();
    }

    public function create($data) {
        $this->db->insert('variacoes', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('variacoes', $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete('variacoes');
    }
}