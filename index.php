<?php

date_default_timezone_set('Europe/Paris');
session_start();
require_once 'autoload.php';

$router = new Router();

try {
    $router->handleRequest($_GET);
} catch (RedirectException $e) {
    exit;
}
