<?php

class NoteManager extends AbstractManager{

public function __construct() {
    parent::__construct();
}

public function add(Note $note) : void {
    $query = $this->db->prepare('INSERT INTO rating (value, created_at, user_id, series_id)
        VALUES (:value, :created_at, :user_id, :series_id)');
    $query->execute([
        'value'      => $note->getValue(),
        'created_at' => $note->getCreatedAt(),
        'user_id'    => $note->getUserId(),
        'series_id'  => $note->getSeriesId()
    ]);
    $note->setId($this->db->lastInsertId());
}

public function update(Note $note) : void {
    $query = $this->db->prepare('UPDATE rating SET value = :value WHERE id = :id');
    $query->execute([
        'value' => $note->getValue(),
        'id'    => $note->getId()
    ]);
}

public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM rating WHERE id = :id');
    $query->execute(['id' => $id]);
}

public function findByUserAndSerie(int $user_id, int $series_id) : ?Note {
    $query = $this->db->prepare('SELECT * FROM rating WHERE user_id = :user_id AND series_id = :series_id');
    $query->execute(['user_id' => $user_id, 'series_id' => $series_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) return null;

    return new Note($result['value'], $result['created_at'], $result['id'], $result['user_id'], $result['series_id']);
}

public function getMoyenne(int $series_id) : float {
    $query = $this->db->prepare('SELECT AVG(value) as average FROM rating WHERE series_id = :series_id');
    $query->execute(['series_id' => $series_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return (float)$result['average'];
}
}
