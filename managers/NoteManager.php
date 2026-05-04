<?php

class NoteManager extends AbstractManager{
    
public function __construct () {
    parent :: __construct();
}

public function add(Note $note) : void {
    $query = $this->db->prepare('INSERT INTO Note (valeur, date_creation, id_user, id_serie)
    VALUES (:valeur, :date_creation, :id_user, :id_serie)');
    $parameters = [
        'valeur' => $note->getValeur(),
        'date_creation' => $note->getCreateDate(),
        'id_user' => $note->getUserid(),
        'id_serie' => $note->getSerieid()
    ];
    $query->execute($parameters);
    $note->setId($this->db->lastInsertId());
}

public function update (Note $note) : void {
    $query = $this->db->prepare('UPDATE Note Set valeur = :valeur WHERE id = :id');
    $parameters = [
        'valeur' => $note->getValeur(),
        'id' => $note->getId()
    ];
$query->execute($parameters);
}

public function delete(int $id) :void {
    $query = $this->db->prepare('DELETE FROM Note WHERE id = :id');
    $parameters = [
        'id' => $id
    ];
    $query->execute($parameters);
}

public function getMoyenne(int $id_serie) : float {
    $query = $this->db->prepare('SELECT AVG(valeur) as moyenne FROM Note WHERE id_serie = :id_serie');
    $parameters = [
        'id_serie' => $id_serie
    ];
    $query->execute($parameters);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return (float) $result['moyenne'];
}

}