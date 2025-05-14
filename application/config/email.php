<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Configuração de email
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'minierpteste@gmail.com';
$config['smtp_pass'] = 'xswc qlur qofj dujb';
$config['smtp_crypto'] = 'tls';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;

// Modo de desenvolvimento (salva emails em arquivos em vez de enviar)
$config['dev_mode'] = FALSE;
$config['log_path'] = APPPATH . 'logs/emails/';