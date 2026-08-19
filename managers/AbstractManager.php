<?php

class AbstractManager {
    
protected PDO $db;

public function __construct()
{   
    $envFile = getenv('APP_ENV') === 'testing' ? '.env.testing' : '.env';
    $env = parse_ini_file(__DIR__ . '/../' . $envFile);
    
    $this->db = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
        $env['DB_USER'],
        $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
}

public function getDb() : PDO {
    return $this->db;
}
}