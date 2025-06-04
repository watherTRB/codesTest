<?php namespace Config;

use CodeIgniter\Config\Services;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->group('api', function($routes) {
    $routes->post('connect', 'ComplianceController::connect');
    $routes->post('search/create', 'ComplianceController::createSearch');
    $routes->post('search/start', 'ComplianceController::startSearch');
    $routes->get('search/results/(:segment)', 'ComplianceController::getResults/$1');
    $routes->post('search/purge', 'ComplianceController::purgeEmails');
    $routes->get('search/status/(:segment)', 'ComplianceController::checkStatus/$1');
});

$routes->get('/', 'Home::index');
