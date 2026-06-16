<?php

date_default_timezone_set('Europe/Paris');
session_start();
require_once 'autoload.php';

$router = new Router();
$router->handleRequest($_GET);