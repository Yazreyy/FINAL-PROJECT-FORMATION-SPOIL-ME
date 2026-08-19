<?php

abstract class AbstractController {
    
protected function render(string $template, array $data = []) : void {
    extract($data);
    ob_start();
    require "templates/{$template}.phtml";
    $content = ob_get_clean();
    require "templates/layout.phtml";
}

protected function redirect(string $route) : void {
    header ("Location: /Spoil-Me/{$route}");
    throw new RedirectException($route);
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

protected function getEnv(string $key): ?string {
    $env = parse_ini_file(__DIR__ . '/../.env');
    return $env[$key] ?? null;
}

protected function csrfToken() : string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

protected function verifyCsrf() : void {
    $expected = $_SESSION['csrf_token'] ?? '';
    $sent = $_POST['csrf_token'] ?? '';
    if ($expected === '' || !hash_equals($expected, $sent)) {
        $this->redirect('');
    }
}
}
