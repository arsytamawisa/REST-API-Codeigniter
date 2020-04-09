<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


/* ROUTE API */
$route['api/login']                     = 'C_Users/login';
$route['api/users']['GET']              = 'C_Users/index';
$route['api/users']['POST']             = 'C_Users/user';
$route['api/users/(:num)']['GET']      	= 'C_Users/user/$1';
$route['api/users/(:num)']['PUT']       = 'C_Users/user/$1';
$route['api/users/(:num)']['DELETE']    = 'C_Users/user/$1';
$route['api/check_token']['GET']		= 'C_Users/check_token';