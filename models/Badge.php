<?php

class Badge {

public function __construct(private string $nom,private string $description,
private string $icone, private string $condition_obtention , private ?int $id = null)
{
    
}

public function getId() : ?int {
    return $this->id;
}
public function setId(?int $id) : void {
    $this->id = $id;
}

public function getNom() : string {
    return $this->nom;
}
public function setNom(string $nom) : void {
    $this->nom = $nom;
}

public function getDescription() : string {
    return $this->description;
}
public function setDescription(string $description) : void {
    $this->description = $description;
}

public function getIcone() : string {
    return $this->icone;
}
public function setIcone(string $icone) : void {
    $this->icone = $icone;
}

public function getCondition() : string {
    return $this->condition_obtention;
}
public function setCondition(string $condition_obtention) : void {
    $this->condition_obtention = $condition_obtention;
    }
}