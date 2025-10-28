<?php

namespace Config;

use CodeIgniter\Routing\Router; // for type hinting only

$routes = Services::routes();

$routes->get('/', 'Home::index');
$routes->match(['get','post'], '/login', 'AuthController::login');
$routes->match(['get','post'], '/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function($routes) {
	$routes->get('/dashboard', 'DashboardController::index');
	$routes->get('/dashboard/chart', 'DashboardController::chartData');

	$routes->get('/funds', 'FundController::index');
	$routes->get('/funds/list', 'FundController::list');
	$routes->post('/funds', 'FundController::store');
	$routes->post('/funds/(:num)', 'FundController::update/$1');
	$routes->delete('/funds/(:num)', 'FundController::delete/$1');

	$routes->get('/expenses', 'ExpenseController::index');
	$routes->get('/expenses/list', 'ExpenseController::list');
	$routes->post('/expenses', 'ExpenseController::store');
	$routes->post('/expenses/(:num)', 'ExpenseController::update/$1');
	$routes->delete('/expenses/(:num)', 'ExpenseController::delete/$1');
	$routes->get('/expenses/export/csv', 'ExpenseController::exportCsv');
	$routes->get('/expenses/export/pdf', 'ExpenseController::exportPdf');
}); 