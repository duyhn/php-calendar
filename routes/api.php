<?php
require __DIR__.'/../config/Router.php';

$router = new Config\Router();

// Add the routes
$router->add('/home', ['controller' => 'HomeController', 'action' => 'index']);
$router->dispatch($_SERVER['REQUEST_URI']);