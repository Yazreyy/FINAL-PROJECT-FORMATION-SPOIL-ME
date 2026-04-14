<?php

class Note{

public function __construct(private int $valeur, private string $date_creation,
private ?int $id = null, private ?int $id_user = null, private ?int $id_serie = null)
{
    
}

public function getValeur() : int {
    return $this->valeur;
}
public function setValeur(int $valeur) : void {
    $this->valeur = $valeur;
}

public function getCreateDate() : string {
    return $this->date_creation;
}
public function setCreateDate(string $date_creation) : void {
    $this->date_creation = $date_creation;
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
public function setSerieid(?int $id_serie) : void {
    $this->id_serie = $id_serie;
}

}