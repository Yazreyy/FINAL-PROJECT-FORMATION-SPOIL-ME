<?php

class Serie{


public function __construct(private string $titre,
private string $synopsis, private string $date_sortie, private string $image,
private float $note_moyenne,private string $statut, private string $date_creation,
private ?int $created_by = null,
private ?int $id = null, private ?int $id_tmdb = null,
private ?string $format = null, private ?string $createur = null,
private ?string $pays = null, private ?string $langue = null
)
{
    
}

public function getId() : ?int {
    return $this->id;
}
public function setId(?int $id) : void {
    $this->id = $id;
}

public function getIdtmdb() : ?int {
    return $this->id_tmdb;
}
public function setIdtmdb(int $id_tmdb) : void {
    $this->id_tmdb = $id_tmdb;
}

public function getTitre() : string {
    return $this->titre;
}
public function setTitre(string $titre) : void {
    $this->titre = $titre;
}

public function getSynopsis() : string {
    return $this->synopsis;
}
public function setSynopsis(string $synopsis) : void {
    $this->synopsis = $synopsis;
}

public function getDate() : string {
    return $this->date_sortie;
}
public function setDate(string $date_sortie) : void {
    $this->date_sortie = $date_sortie;
}

public function getImage() : string {
    return $this->image;
}
public function setImage(string $image) : void {
    $this->image = $image;
}

public function getNote() : float {
    return $this->note_moyenne;
}
public function setNote(float $note_moyenne) : void {
    $this->note_moyenne = $note_moyenne;
}

public function getCreatedBy() : ?int {
    return $this->created_by;
}
public function setCreatedBy(?int $created_by) : void {
    $this->created_by = $created_by;
}

public function getStatut() : string {
    return $this->statut;
}
public function setStatut(string $statut) : void {
    $this->statut = $statut;
}

public function getCreatedDate() : string {
    return $this->date_creation;
}
public function setCreatedDate(string $date_creation) : void {
    $this->date_creation = $date_creation;
}
public function isFinish() : bool {
    return $this->statut === 'terminée';
}

public function getFormat() : ?string {
    return $this->format;
}
public function setFormat(?string $format) : void {
    $this->format = $format;
}

public function getCreateur() : ?string {
    return $this->createur;
}
public function setCreateur(?string $createur) : void {
    $this->createur = $createur;
}

public function getPays() : ?string {
    return $this->pays;
}
public function setPays(?string $pays) : void {
    $this->pays = $pays;
}

public function getLangue() : ?string {
    return $this->langue;
}
public function setLangue(?string $langue) : void {
    $this->langue = $langue;
}
}