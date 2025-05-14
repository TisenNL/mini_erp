<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Carrinho extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('produto_model');
        $this->load->model('variacao_model');
        $this->load->model('estoque_model');
        $this->load->model('pedido_model');
        $this->load->model('cupom_model');
    }

    public function index()
    {
        $data['title'] = 'Meu Carrinho';
        $data['carrinho'] = $this->session->carrinho ?: [];
        $data['subtotal'] = $this->session->carrinho_subtotal ?: 0;
        $data['frete'] = $this->session->carrinho_frete ?: 0;
        $data['desconto'] = $this->session->carrinho_desconto ?: 0;
        $data['total'] = $this->session->carrinho_total ?: 0;
        $data['cupom_id'] = $this->session->cupom_id ?: NULL;

        if ($data['cupom_id']) {
            $data['cupom'] = $this->cupom_model->get_by_id($data['cupom_id']);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('carrinho/index', $data);
        $this->load->view('templates/footer');
    }

    public function adicionar($produto_id, $variacao_id = NULL)
    {
        $produto = $this->produto_model->get_by_id($produto_id);

        if (empty($produto)) {
            show_404();
        }

        // Verificar estoque
        $disponivel = $this->estoque_model->verificar_disponibilidade($produto_id, $variacao_id, 1);

        if (!$disponivel) {
            $this->session->set_flashdata('error', 'Produto sem estoque disponível.');
            redirect('carrinho');
        }

        // Inicializar carrinho na sessão, se não existir
        if (!$this->session->carrinho) {
            $this->session->set_userdata('carrinho', []);
        }

        $carrinho = $this->session->carrinho;

        // Gerar chave única para o item
        $item_key = $produto_id . '-' . ($variacao_id ? $variacao_id : '0');

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
                'produto_id' => $produto_id,
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

    public function remover($item_key)
    {
        $carrinho = $this->session->carrinho;

        if (isset($carrinho[$item_key])) {
            unset($carrinho[$item_key]);
            $this->session->set_userdata('carrinho', $carrinho);
            $this->atualizar_totais_carrinho();
            $this->session->set_flashdata('success', 'Item removido do carrinho!');
        } else {
            $this->session->set_flashdata('error', 'Item não encontrado no carrinho.');
        }

        redirect('carrinho');
    }

    public function atualizar()
    {
        $quantidades = $this->input->post('quantidade');
        $carrinho = $this->session->carrinho;

        foreach ($quantidades as $item_key => $quantidade) {
            if (isset($carrinho[$item_key])) {
                $produto_id = $carrinho[$item_key]['produto_id'];
                $variacao_id = $carrinho[$item_key]['variacao_id'];

                // Verificar estoque
                $disponivel = $this->estoque_model->verificar_disponibilidade($produto_id, $variacao_id, $quantidade);

                if ($disponivel) {
                    $carrinho[$item_key]['quantidade'] = $quantidade;
                    $carrinho[$item_key]['subtotal'] = $quantidade * $carrinho[$item_key]['preco'];
                } else {
                    $this->session->set_flashdata('error', 'Quantidade indisponível para o produto: ' . $carrinho[$item_key]['produto_nome']);
                }
            }
        }

        $this->session->set_userdata('carrinho', $carrinho);
        $this->atualizar_totais_carrinho();

        $this->session->set_flashdata('success', 'Carrinho atualizado com sucesso!');
        redirect('carrinho');
    }

    public function limpar()
    {
        $this->session->unset_userdata('carrinho');
        $this->session->unset_userdata('carrinho_subtotal');
        $this->session->unset_userdata('carrinho_frete');
        $this->session->unset_userdata('carrinho_desconto');
        $this->session->unset_userdata('carrinho_total');
        $this->session->unset_userdata('cupom_id');

        $this->session->set_flashdata('success', 'Carrinho limpo com sucesso!');
        redirect('carrinho');
    }

    public function aplicar_cupom()
    {
        $codigo = $this->input->post('cupom');
        $subtotal = $this->session->carrinho_subtotal;

        $cupom = $this->cupom_model->validar_cupom($codigo, $subtotal);

        if ($cupom) {
            $this->session->set_userdata('cupom_id', $cupom->id);
            $this->atualizar_totais_carrinho();
            $this->session->set_flashdata('success', 'Cupom aplicado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Cupom inválido ou não aplicável para o valor atual.');
        }

        redirect('carrinho');
    }

    public function remover_cupom()
    {
        $this->session->unset_userdata('cupom_id');
        $this->atualizar_totais_carrinho();

        $this->session->set_flashdata('success', 'Cupom removido com sucesso!');
        redirect('carrinho');
    }

    public function checkout()
    {
        if (empty($this->session->carrinho)) {
            $this->session->set_flashdata('error', 'Seu carrinho está vazio!');
            redirect('carrinho');
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cliente_nome', 'Nome', 'required');
        $this->form_validation->set_rules('cliente_email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('cliente_telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('cep', 'CEP', 'required');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required');
        $this->form_validation->set_rules('numero', 'Número', 'required');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Finalizar Compra';
            $data['carrinho'] = $this->session->carrinho;
            $data['subtotal'] = $this->session->carrinho_subtotal;
            $data['frete'] = $this->session->carrinho_frete;
            $data['desconto'] = $this->session->carrinho_desconto;
            $data['total'] = $this->session->carrinho_total;

            $this->load->view('templates/header', $data);
            $this->load->view('carrinho/checkout', $data);
            $this->load->view('templates/footer');
        } else {
            // Criar o pedido
            $pedido_data = [
                'cliente_nome' => $this->input->post('cliente_nome'),
                'cliente_email' => $this->input->post('cliente_email'),
                'cliente_telefone' => $this->input->post('cliente_telefone'),
                'cep' => $this->input->post('cep'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'cidade' => $this->input->post('cidade'),
                'estado' => $this->input->post('estado'),
                'subtotal' => $this->session->carrinho_subtotal,
                'valor_frete' => $this->session->carrinho_frete,
                'desconto' => $this->session->carrinho_desconto,
                'valor_total' => $this->session->carrinho_total,
                'cupom_id' => $this->session->cupom_id ?: NULL
            ];

            $pedido_id = $this->pedido_model->create($pedido_data);

            // Adicionar os itens do pedido
            $carrinho = $this->session->carrinho;

            foreach ($carrinho as $item) {
                $item_data = [
                    'pedido_id' => $pedido_id,
                    'produto_id' => $item['produto_id'],
                    'variacao_id' => $item['variacao_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'subtotal' => $item['subtotal']
                ];

                $this->pedido_model->adicionar_item($pedido_id, $item_data);

                // Diminuir o estoque
                $this->estoque_model->diminuir_estoque(
                    $item['produto_id'],
                    $item['variacao_id'],
                    $item['quantidade']
                );
            }

            // Enviar email
            $this->enviar_email_confirmacao($pedido_id);

            // Limpar carrinho
            $this->session->unset_userdata('carrinho');
            $this->session->unset_userdata('carrinho_subtotal');
            $this->session->unset_userdata('carrinho_frete');
            $this->session->unset_userdata('carrinho_desconto');
            $this->session->unset_userdata('carrinho_total');
            $this->session->unset_userdata('cupom_id');

            // Redirecionar para confirmação
            redirect('carrinho/confirmacao/' . $pedido_id);
        }
    }

    public function confirmacao($pedido_id)
    {
        $data['pedido'] = $this->pedido_model->get_by_id($pedido_id);

        if (empty($data['pedido'])) {
            show_404();
        }

        $data['itens'] = $this->pedido_model->get_itens($pedido_id);
        $data['title'] = 'Pedido Confirmado';

        $this->load->view('templates/header', $data);
        $this->load->view('carrinho/confirmacao', $data);
        $this->load->view('templates/footer');
    }

    public function buscar_cep()
    {
        $cep = $this->input->post('cep');

        if (!$cep) {
            echo json_encode(['erro' => true]);
            return;
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    private function atualizar_totais_carrinho()
    {
        $carrinho = $this->session->carrinho;
        $subtotal = 0;

        if ($carrinho) {
            foreach ($carrinho as $item) {
                $subtotal += $item['subtotal'];
            }
        }

        $frete = $this->pedido_model->calcular_frete($subtotal);

        // Verificar cupom
        $cupom_id = $this->session->cupom_id;
        $desconto = 0;

        if ($cupom_id) {
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

    private function enviar_email_confirmacao($pedido_id)
    {
        // Carregar a biblioteca de e-mail
        $this->load->library('email_service');

        // Enviar e-mail
        $enviado = $this->email_service->send_order_confirmation($pedido_id);

        if (!$enviado) {
            log_message('error', 'Falha ao enviar e-mail de confirmação para o pedido: ' . $pedido_id);
        }

        return $enviado;
    }
}
