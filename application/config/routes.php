<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// Rota padrão
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Rotas para produtos
$route['produtos'] = 'produtos/index';
$route['produtos/create'] = 'produtos/create';
$route['produtos/edit/(:num)'] = 'produtos/edit/$1';
$route['produtos/view/(:num)'] = 'produtos/view/$1';
$route['produtos/delete/(:num)'] = 'produtos/delete/$1';
$route['produtos/comprar/(:num)'] = 'produtos/comprar/$1';
$route['produtos/comprar/(:num)/(:num)'] = 'produtos/comprar/$1/$2';

// Rotas para cupons
$route['cupons'] = 'cupons/index';
$route['cupons/create'] = 'cupons/create';
$route['cupons/edit/(:num)'] = 'cupons/edit/$1';
$route['cupons/delete/(:num)'] = 'cupons/delete/$1';

// Rotas para carrinho
$route['carrinho'] = 'carrinho/index';
$route['carrinho/adicionar/(:num)'] = 'carrinho/adicionar/$1';
$route['carrinho/adicionar/(:num)/(:num)'] = 'carrinho/adicionar/$1/$2';
$route['carrinho/remover/(:any)'] = 'carrinho/remover/$1';
$route['carrinho/atualizar'] = 'carrinho/atualizar';
$route['carrinho/limpar'] = 'carrinho/limpar';
$route['carrinho/aplicar_cupom'] = 'carrinho/aplicar_cupom';
$route['carrinho/remover_cupom'] = 'carrinho/remover_cupom';
$route['carrinho/checkout'] = 'carrinho/checkout';
$route['carrinho/confirmacao/(:num)'] = 'carrinho/confirmacao/$1';
$route['carrinho/buscar_cep'] = 'carrinho/buscar_cep';

// Rota para webhook
$route['webhook/status'] = 'webhook/status';