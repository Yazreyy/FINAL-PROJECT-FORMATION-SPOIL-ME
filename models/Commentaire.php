<?php

class Commentaire {


public function __construct(private string $texte, private bool $est_valide, private string $date_creation,
private ?int $id = null, private ?int $id_review = null, private ?int $id_user = null)
{

}

public function getId() : ?int {
    return $this->id;
}
public function setId(?int $id) : void {
    $this->id = $id;
}

public function getReviewId() : ?int {
    return $this->id_review;
}
public function setReviewId(?int $id_review) : void {
    $this->id_review = $id_review;
}

public function getUserId() : ?int {
    return $this->id_user;
}
public function setUserId(?int $id_user) : void {
    $this->id_user = $id_user;
}

public function getTexte() : string {
    return $this->texte;
}
public function setTexte(string $texte) : void {
    $this->texte = $texte;
}

public function getValidate() : bool {
    return $this->est_valide;
}
public function setValidate(bool $est_valide) : void {
    $this->est_valide = $est_valide;
}
public function isValidate() : bool {
    //vérifie si c'est bien validé//
    return $this->est_valide === true;
}

public function getDate() : string {
    return $this->date_creation;
}
public function setDate(string $date_creation) : void {
    $this->date_creation = $date_creation;
}
}