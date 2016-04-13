<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Kozo\Web\Router\Route;
use App\DummyObject;

$route =  new Route([
            "GET","DELETE"
        ], "/users/:number", "App\DummyObjectTwo");

$route->execute($_SERVER['PATH_INFO']);