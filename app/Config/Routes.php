<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

//Auth Routes
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Products Routes
$routes->group('products', function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('getProducts', 'Products::getProducts');
    $routes->post('create', 'Products::create');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->post('delete/(:num)', 'Products::delete/$1');
});

// Categories Routes
$routes->group('categories', function($routes) {
    $routes->get('/', 'Categories::index');
    $routes->get('getCategories', 'Categories::getCategories');
    $routes->post('create', 'Categories::create');
    $routes->get('edit/(:num)', 'Categories::edit/$1');
    $routes->post('update/(:num)', 'Categories::update/$1');
    $routes->post('delete/(:num)', 'Categories::delete/$1');
});


