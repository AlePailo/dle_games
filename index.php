<?php declare(strict_types = 1);

require 'functions.php'; //file esterno momentaneo per dump and die

require_once __DIR__ . '/autoload.php';
use App\Core\Router;
use App\Core\DatabaseFactory;


$pdo = DatabaseFactory::create();

$router = new Router($pdo);

$router->dispatch($_SERVER['REQUEST_URI']);