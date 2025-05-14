<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('produto_model');
        $this->load->model('variacao_model');
        $this->load->model('estoque_model');
    }

    public function index() {
        $data['produtos'] = $this->produto_model->get_with_estoque();
        $data['title'] = 'Gerenciar Produtos';
        
        $this->load->view('templates/header', $data);
        $this->load->view('produtos/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($id) {
        $data['produto'] = $this->produto_model->get_with_variacoes($id);
        
        if (empty($data['produto'])) {
            show_404();
        }
        
        $data['title'] = 'Detalhes do Produto: ' . $data['produto']->nome;
        
        $this->load->view('templates/header', $data);
        $this->load->view('produtos/view', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
        $this->form_validation->set_rules('quantidade', 'Quantidade em Estoque', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Adicionar Novo Produto';
            
            $this->load->view('templates/header', $data);
            $this->load->view('produtos/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Inserir o produto
            $produto_id = $this->produto_model->create([
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            ]);
            
            // Inserir estoque
            $this->estoque_model->create([
                'produto_id' => $produto_id,
                'variacao_id' => NULL,
                'quantidade' => $this->input->post('quantidade')
            ]);
            
            // Processar variações, se houver
            $variacoes = $this->input->post('variacoes');
            $estoques_variacao = $this->input->post('estoques_variacao');
            
            if (!empty($variacoes)) {
                foreach ($variacoes as $index => $nome_variacao) {
                    if (!empty($nome_variacao)) {
                        // Criar variação
                        $variacao_id = $this->variacao_model->create([
                            'produto_id' => $produto_id,
                            'nome' => $nome_variacao
                        ]);
                        
                        // Criar estoque para variação
                        $quantidade = isset($estoques_variacao[$index]) ? $estoques_variacao[$index] : 0;
                        
                        $this->estoque_model->create([
                            'produto_id' => $produto_id,
                            'variacao_id' => $variacao_id,
                            'quantidade' => $quantidade
                        ]);
                    }
                }
            }
            
            $this->session->set_flashdata('success', 'Produto adicionado com sucesso!');
            redirect('produtos');
        }
    }

    public function edit($id) {
        $data['produto'] = $this->produto_model->get_with_variacoes($id);
        
        if (empty($data['produto'])) {
            show_404();
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
        $this->form_validation->set_rules('quantidade', 'Quantidade em Estoque', 'required|integer');
        
        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Editar Produto: ' . $data['produto']->nome;
            
            $this->load->view('templates/header', $data);
            $this->load->view('produtos/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Atualizar produto
            $this->produto_model->update($id, [
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            ]);
            
            // Atualizar estoque principal
            $estoque_id = $this->input->post('estoque_id');
            $quantidade = $this->input->post('quantidade');
            
            if ($estoque_id) {
                $this->estoque_model->update($estoque_id, ['quantidade' => $quantidade]);
            } else {
                $this->estoque_model->create([
                    'produto_id' => $id,
                    'variacao_id' => NULL,
                    'quantidade' => $quantidade
                ]);
            }
            
            // Processar variações existentes
            $variacao_ids = $this->input->post('variacao_ids');
            $nomes_variacao = $this->input->post('nomes_variacao');
            $estoques_variacao_ids = $this->input->post('estoques_variacao_ids');
            $quantidades_variacao = $this->input->post('quantidades_variacao');
            
            if (!empty($variacao_ids)) {
                foreach ($variacao_ids as $index => $variacao_id) {
                    // Atualizar variação
                    $this->variacao_model->update($variacao_id, [
                        'nome' => $nomes_variacao[$index]
                    ]);
                    
                    // Atualizar estoque da variação
                    $estoque_var_id = $estoques_variacao_ids[$index];
                    $quantidade_var = $quantidades_variacao[$index];
                    
                    if ($estoque_var_id) {
                        $this->estoque_model->update($estoque_var_id, [
                            'quantidade' => $quantidade_var
                        ]);
                    } else {
                        $this->estoque_model->create([
                            'produto_id' => $id,
                            'variacao_id' => $variacao_id,
                            'quantidade' => $quantidade_var
                        ]);
                    }
                }
            }
            
            // Processar novas variações
            $novas_variacoes = $this->input->post('variacoes');
            $novos_estoques_var = $this->input->post('estoques_variacao');
            
            if (!empty($novas_variacoes)) {
                foreach ($novas_variacoes as $index => $nome_var) {
                    if (!empty($nome_var)) {
                        // Criar variação
                        $variacao_id = $this->variacao_model->create([
                            'produto_id' => $id,
                            'nome' => $nome_var
                        ]);
                        
                        // Criar estoque para variação
                        $quantidade = isset($novos_estoques_var[$index]) ? $novos_estoques_var[$index] : 0;
                        
                        $this->estoque_model->create([
                            'produto_id' => $id,
                            'variacao_id' => $variacao_id,
                            'quantidade' => $quantidade
                        ]);
                    }
                }
            }
            
            $this->session->set_flashdata('success', 'Produto atualizado com sucesso!');
            redirect('produtos');
        }
    }

    public function delete($id) {
        $produto = $this->produto_model->get_by_id($id);
        
        if (empty($produto)) {
            show_404();
        }
        
        $this->produto_model->delete($id);
        $this->session->set_flashdata('success', 'Produto removido com sucesso!');
        redirect('produtos');
    }

    public function comprar($id, $variacao_id = NULL) {
        $produto = $this->produto_model->get_by_id($id);
        
        if (empty($produto)) {
            show_404();
        }
        
        // Verificar estoque
        $disponivel = $this->estoque_model->verificar_disponibilidade($id, $variacao_id, 1);
        
        if (!$disponivel) {
            $this->session->set_flashdata('error', 'Produto sem estoque disponível.');
            redirect('produtos/view/' . $id);
        }
        
        // Inicializar carrinho na sessão, se não existir
        if (!$this->session->carrinho) {
            $this->session->set_userdata('carrinho', []);
        }
        
        $carrinho = $this->session->carrinho;
        
        // Gerar chave única para o item
        $item_key = $id . '-' . ($variacao_id ? $variacao_id : '0');
        
        // Verificar se já existe no carrinho
        if (isset($carrinho[$item_key])) {
            // Adicionar mais um
            $carrinho[$item_key]['quantidade']++;
            $carrinho[$item_key]['subtotal'] = $carrinho[$item_key]['quantidade'] * $carrinho[$item_key]['preco'];
        } else {
            // Obter nome da variação, se houver
            $variacao_nome = NULL;
            if ($variacao_id) {
                $variacao = $this->db->where('id', $variacao_id)->get('variacoes')->row();
                $variacao_nome = $variacao ? $variacao->nome : NULL;
            }
            
            // Adicionar novo item
            $carrinho[$item_key] = [
                'produto_id' => $id,
                'produto_nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => 1,
                'subtotal' => $produto->preco,
                'variacao_id' => $variacao_id,
                'variacao_nome' => $variacao_nome
            ];
        }
        
        // Atualizar carrinho na sessão
        $this->session->set_userdata('carrinho', $carrinho);
        
        // Atualizar totais
        $this->atualizar_totais_carrinho();
        
        $this->session->set_flashdata('success', 'Produto adicionado ao carrinho!');
        redirect('carrinho');
    }

    private function atualizar_totais_carrinho() {
        $carrinho = $this->session->carrinho;
        $subtotal = 0;
        
        foreach ($carrinho as $item) {
            $subtotal += $item['subtotal'];
        }
        
        $this->load->model('pedido_model');
        $frete = $this->pedido_model->calcular_frete($subtotal);
        
        // Verificar cupom
        $cupom_id = $this->session->cupom_id;
        $desconto = 0;
        
        if ($cupom_id) {
            $this->load->model('cupom_model');
            $cupom = $this->cupom_model->get_by_id($cupom_id);
            $desconto = $this->cupom_model->calcular_desconto($cupom, $subtotal);
        }
        
        $total = $subtotal + $frete - $desconto;
        
        // Atualizar totais na sessão
        $this->session->set_userdata('carrinho_subtotal', $subtotal);
        $this->session->set_userdata('carrinho_frete', $frete);
        $this->session->set_userdata('carrinho_desconto', $desconto);
        $this->session->set_userdata('carrinho_total', $total);
    }
}