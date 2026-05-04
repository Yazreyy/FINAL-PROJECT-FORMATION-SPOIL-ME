<?php

class LikeManager extends AbstractManager {

public function __construct() {
    parent::__construct();
}

public function add(int $id_user, int $id_review) : void {
    $query = $this->db->prepare('INSERT INTO `Like` (id_user, id_review, date_creation)
    VALUES (:id_user, :id_review, :date_creation)');
    $parameters = [
        'id_user' => $id_user,
        'id_review' => $id_review,
        'date_creation' => date('Y-m-d H:i:s')
    ];
    $query->execute($parameters);
}

public function remove(int $id_user, int $id_review) : void {
    $query = $this->db->prepare('DELETE FROM `Like` WHERE id_user = :id_user AND id_review = :id_review');
    $parameters = [
        'id_user' => $id_user,
        'id_review' => $id_review
    ];
    $query->execute($parameters);
}

public function exists(int $id_user, int $id_review) : bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM `Like` WHERE id_user = :id_user AND id_review = :id_review');
    $parameters = [
        'id_user' => $id_user,
        'id_review' => $id_review
    ];
    $query->execute($parameters);
    return (bool)$query->fetchColumn();
}
}