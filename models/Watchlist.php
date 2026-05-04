<?php

class Watchlist {
    
public function __construct(private ?int $id_serie,private string $statut,private string $date_ajout,
private ?int $id = null, private ?int $id_user = null)
{
    
}

public function getId() : ?int {
return $this->id;    
}
public function setId(?int $id) : void {
    $this->id = $id;
}

public function getUserid() : ?int {
    return $this->id_user;
}
public function setUserid(?int $id_user) : void {
    $this->id_user = $id_user;
}

public function getSerieid() : ?int {
    return $this->id_serie;
}
public function setSerieid(int $id_serie) : void {
    $this->id_serie = $id_serie;
}

public function getStatut() : string {
    return $this->statut;
}
public function setStatut(string $statut) : void {
    $this->statut = $statut;
}

public function getDate() : string {
    return $this->date_ajout;
}
public function setDate(string $date_ajout) : void{
    $this->date_ajout = $date_ajout;
}
public function isWatch() : bool {
    // verifie si le statut est vu//
    return $this->statut === 'vu';
}
}