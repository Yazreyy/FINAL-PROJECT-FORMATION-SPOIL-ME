<?php

class AbstractManager {
    
protected PDO $db;

public function __construct()
{   
    $env = parse_ini_file(__DIR__ . '/../.env');
    
    $this->db = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
        $env['DB_USER'],
        $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
}
}