<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//$routes->get('/test-db', 'DbTest::index');
// Auth Register
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/register-success', 'Auth::registerSuccess');
$routes->get('auth/register-verify/(:any)', 'Auth::registerVerify/$1');
$routes->get('auth/complete-registration', 'Auth::completeRegistration');
$routes->post('auth/complete-registration', 'Auth::completeRegistration');
// Auth Payment
$routes->post('auth/payment', 'Auth::payment');
// Auth Login
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
// Auth Logout
$routes->get('auth/logout', 'Auth::logout');
// Auth Password Reset
$routes->get('auth/password-reset', 'Auth::requestPasswordReset');
$routes->post('auth/password-reset', 'Auth::requestPasswordReset');
$routes->get('auth/password-verify/(:any)', 'Auth::passwordVerify/$1');
$routes->post('auth/password-verify', 'Auth::passwordVerifyPost');
// Welcome page
$routes->get('welcome', 'Auth::welcome');

// Account
$routes->get('account', 'Account::index');
$routes->post('account', 'Account::index');
$routes->get('account/password', 'Account::changePassword');
$routes->post('account/password', 'Account::changePassword');

// Test
$routes->get('test-email', 'Auth::testEmail');
