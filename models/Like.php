<?php

class Like {

public function __construct(private string $date_creation, private ?int $id = null,
private ?int $id_user = null, private ?int $id_review = null, private ?int $id_commentaire = null)
{

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

public function getReviewid() : ?int {
    return $this->id_review;
}
public function setReviewid(?int $id_review) : void {
    $this->id_review = $id_review;
}

public function getCommentid() : ?int {
    return $this->id_commentaire;
}
public function setCommentid(?int $id_commentaire) : void {
    $this->id_commentaire = $id_commentaire;
}
}