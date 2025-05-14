<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_service {
    
    private $ci;
    private $dev_mode;
    private $log_path;
    
    public function __construct() {
        $this->ci =& get_instance();
        
        // Carregar configuração do e-mail
        $this->ci->config->load('email');
        $this->dev_mode = $this->ci->config->item('dev_mode');
        $this->log_path = $this->ci->config->item('log_path');
        
        // Certificar que a pasta de logs existe se estiver em modo dev
        if ($this->dev_mode && !empty($this->log_path) && !file_exists($this->log_path)) {
            mkdir($this->log_path, 0755, true);
        }
    }
    
    public function send_order_confirmation($pedido_id) {
        // Carregar modelos necessários
        $this->ci->load->model('pedido_model');
        
        // Obter dados do pedido
        $pedido = $this->ci->pedido_model->get_by_id($pedido_id);
        $itens = $this->ci->pedido_model->get_itens($pedido_id);
        
        if (empty($pedido)) {
            return false;
        }
        
        // Preparar mensagem do e-mail
        $subject = 'Confirmação de Pedido #' . $pedido_id . ' - Mini ERP do Tiago Lino';
        $message = $this->prepare_order_template($pedido, $itens);
        
        // Verificar modo de desenvolvimento
        if ($this->dev_mode) {
            return $this->log_email($pedido->cliente_email, $subject, $message);
        }
        
        // Configurar e enviar e-mail
        $this->ci->load->library('email');
        $this->ci->email->clear();
        
        $this->ci->email->from('minierpteste@gmail.com', 'Mini ERP do Tiago Lino');
        $this->ci->email->to($pedido->cliente_email);
        $this->ci->email->subject($subject);
        $this->ci->email->message($message);
        
        if ($this->ci->email->send()) {
            return true;
        } else {
            // Log do erro
            log_message('error', 'Falha ao enviar e-mail: ' . $this->ci->email->print_debugger());
            return false;
        }
    }
    
    private function log_email($to, $subject, $message) {
        $filename = $this->log_path . 'email_' . date('Y-m-d_H-i-s') . '_' . md5($to . time()) . '.html';
        
        // Adicionar metadados
        $content = "To: " . $to . "\n";
        $content .= "Subject: " . $subject . "\n";
        $content .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
        $content .= $message;
        
        // Salvar arquivo
        if (file_put_contents($filename, $content)) {
            log_message('info', 'E-mail registrado em: ' . $filename);
            return true;
        }
        
        log_message('error', 'Falha ao registrar e-mail em arquivo');
        return false;
    }
    
    private function prepare_order_template($pedido, $itens) {
        // Template HTML do email
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background: #4a89dc; color: white; padding: 15px; text-align: center; }
                .content { padding: 20px; }
                .footer { background: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f5f5f5; }
                .text-right { text-align: right; }
                .address { background: #f9f9f9; padding: 10px; margin-bottom: 20px; }
                .summary { margin-top: 20px; background: #f9f9f9; padding: 10px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Confirmação de Pedido #'.$pedido->id.'</h2>
                </div>
                <div class="content">
                    <p>Olá <strong>'.$pedido->cliente_nome.'</strong>,</p>
                    <p>Obrigado por sua compra! Seu pedido foi recebido e está sendo processado.</p>
                    
                    <div class="address">
                        <h3>Endereço de Entrega</h3>
                        <p>
                            '.$pedido->endereco.', '.$pedido->numero;
                    
        if ($pedido->complemento) {
            $html .= ' - '.$pedido->complemento;
        }
        
        $html .= '<br>
                            '.$pedido->bairro.'<br>
                            '.$pedido->cidade.' - '.$pedido->estado.'<br>
                            CEP: '.$pedido->cep.'
                        </p>
                    </div>
                    
                    <h3>Itens do Pedido</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th class="text-right">Preço</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($itens as $item) {
            $html .= '
                            <tr>
                                <td>
                                    '.$item->produto_nome;
                                    
            if ($item->variacao_nome) {
                $html .= '<br><small>Variação: '.$item->variacao_nome.'</small>';
            }
            
            $html .= '
                                </td>
                                <td>'.$item->quantidade.'</td>
                                <td class="text-right">R$ '.number_format($item->preco_unitario, 2, ',', '.').'</td>
                                <td class="text-right">R$ '.number_format($item->subtotal, 2, ',', '.').'</td>
                            </tr>';
        }
        
        $html .= '
                        </tbody>
                    </table>
                    
                    <div class="summary">
                        <p><strong>Subtotal:</strong> R$ '.number_format($pedido->subtotal, 2, ',', '.').'</p>
                        <p><strong>Frete:</strong> ';
                        
        if ($pedido->valor_frete > 0) {
            $html .= 'R$ '.number_format($pedido->valor_frete, 2, ',', '.');
        } else {
            $html .= 'Grátis';
        }
        
        $html .= '</p>';
        
        if ($pedido->desconto > 0) {
            $html .= '<p><strong>Desconto:</strong> R$ '.number_format($pedido->desconto, 2, ',', '.').'</p>';
        }
        
        $html .= '
                        <p><strong>Total:</strong> R$ '.number_format($pedido->valor_total, 2, ',', '.').'</p>
                    </div>
                    
                    <p>Status do pedido: <strong>'.ucfirst($pedido->status).'</strong></p>
                    <p>Se tiver alguma dúvida, responda a este e-mail ou entre em contato conosco.</p>
                    <p>Obrigado por comprar conosco!</p>
                </div>
                <div class="footer">
                    <p>Mini ERP - Sistema de Controle de Pedidos, Produtos, Cupons e Estoque</p>
                    <p>&copy; '.date('Y').' Mini ERP do Tiago Lino - Todos os direitos reservados</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}