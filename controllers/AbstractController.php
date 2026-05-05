<?php

abstract class AbstractController {
    
protected function render(string $template, array $data = []) : void  {
extract($data); 
require "templates/{$template}.phtml";
}

protected function redirect(string $route) : void {
    header ("Location: /{$route}");
    exit;
}

protected function isLogged() : bool {
    return isset($_SESSION['user_id']);
}

protected function isAdmin() : bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

protected function isVip() : bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'vip';
}

protected function requireLogin() : void {
    if(!$this->isLogged()) {
        $this->redirect('login');
    }
}

protected function requireAdmin() : void {
    if(!$this->isAdmin()) {
        $this->redirect('series');
    }
}
}