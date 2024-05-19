<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//$routes->get('/test-db', 'DbTest::index');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register_post', 'Auth::register_post');
$routes->get('auth/verify/(:any)', 'Auth::verify/$1');
$routes->post('auth/payment', 'Auth::payment');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login_post', 'Auth::login_post');
$routes->get('auth/request_password_reset', 'Auth::request_password_reset');
$routes->post('auth/send_password_reset_email', 'Auth::send_password_reset_email');
$routes->get('auth/reset_password/(:any)', 'Auth::reset_password/$1');
$routes->post('auth/reset_password_post', 'Auth::reset_password_post');
