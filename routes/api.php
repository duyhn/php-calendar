<?php
require __DIR__.'/../config/Router.php';

$router = new Config\Router();

// Add the routes
$router->add('/home', ['controller' => 'HomeController', 'action' => 'index']);
$router->add('/resources/assets', ['controller' => 'StaticFileControler', 'action' => 'index']);
$router->add('/calendar/create', ['controller' => 'CalendarControler', 'action' => 'store']);
$router->add('/calendar/delete', ['controller' => 'CalendarControler', 'action' => 'destroy']);
$router->add('/calendar/update', ['controller' => 'CalendarControler', 'action' => 'update']);
$router->add('/calendar', ['controller' => 'CalendarControler', 'action' => 'index']);
$router->dispatch($_SERVER['REQUEST_URI']);