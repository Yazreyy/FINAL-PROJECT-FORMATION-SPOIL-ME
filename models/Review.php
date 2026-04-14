<?php

class Review {

public function __construct(private string $texte, private bool $est_officielle, private string $date_creation,
private ?int $id = null, private ?int $id_serie = null, private ?int $id_user = null )
{
 
}

public function getTexte() : string {
    return $this->texte;
}
public function setTexte(string $texte) : void {
    $this->texte = $texte;
}

public function getOfficial() : bool {
    return $this->est_officielle;
}
public function setOfficial(bool $est_officielle) : void {
    $this->est_officielle = $est_officielle;
}

public function getDateCreate() : string {
    return $this->date_creation;
}
public function setDateCreate(string $date_creation) : void {
    $this->date_creation = $date_creation;
}

public function getId() : ?int {
    return $this->id;
}
public function setId(?int $id) : void {
    $this->id = $id;
}

public function getSerieid() : ?int {
    return $this->id_serie;
}
public function setSerieid(?int $id_serie) : void {
    $this->id_serie = $id_serie;
}

public function getUserid() : ?int {
    return $this->id_user;
}
public function setUserid(?int $id_user) : void {
    $this->id_user = $id_user;
}

public function isOfficial() : bool {
    //Verifie si il est officiel//
    return $this->est_officielle === true;
}
}