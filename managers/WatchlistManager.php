<?php

class WatchListManager extends AbstractManager {
    
public function __construct()
{
     parent::__construct();
}

public function add(Watchlist $watchlist) : void {
    $query = $this->db->prepare('INSERT INTO watchlist (id_serie, statut, date_ajout, id_user)
    VALUES (:id_serie, :statut, :date_ajout, :id_user)');
    $parameters = [
        'id_serie' => $watchlist->getSerieid(),
        'statut' => $watchlist->getStatut(),
        'date_ajout' => $watchlist->getDate(),
        'id_user' => $watchlist->getUserid()
    ];
    $query->execute($parameters);
    $watchlist->setId($this->db->lastInsertId());
}

public function remove(int $id) : void {
    $query = $this->db->prepare('DELETE FROM Watchlist WHERE id = :id');
    $parameters = [
        'id' => $id
    ];
    $query->execute($parameters);
}

public function findByUser(int $id_user) : array {
    $query = $this->db->prepare('SELECT * FROM Watchlist WHERE id_user = :id_user');
    $parameters = [
        'id_user' => $id_user
    ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $watchlist = [];

    foreach($results as $result) {
        $watchlist[] = new Watchlist($result['id_serie'], $result['statut'],
            $result['date_ajout'], $result['id'], $result['id_user']);
    }
    return $watchlist;
}

public function update(Watchlist $watchlist) : void {
    $query = $this->db->prepare('UPDATE Watchlist SET statut = :statut WHERE id = :id');
    $parameters = [
        'statut' => $watchlist->getStatut(),
        'id'=> $watchlist->getId()
    ];
    $query->execute($parameters);
}

public function findByUserAndStatut(int $id_user, string $statut) : array {
    $query = $this->db->prepare('
    SELECT Watchlist.*, Serie.titre, Serie.image, Serie.statut AS serie_statut
    FROM WATCHLIST
    JOIN Serie ON Watchlist.id_serie = Series.id
    WHERE Watchlist.id_user = :id_user AND Watchlist.statut = :statut');
    $parameters = [
        'id_user' => $id_user,
        'statut' => $statut
    ];
    $query->execute($parameters);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


}