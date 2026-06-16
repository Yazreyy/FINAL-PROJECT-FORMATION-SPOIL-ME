<?php

class WatchListManager extends AbstractManager {

public function __construct()
{
    parent::__construct();
}

public function add(Watchlist $watchlist) : void {
    $query = $this->db->prepare('INSERT INTO watchlist (series_id, status, added_at, user_id)
        VALUES (:series_id, :status, :added_at, :user_id)');
    $query->execute([
        'series_id' => $watchlist->getSeriesId(),
        'status'    => $watchlist->getStatus(),
        'added_at'  => $watchlist->getAddedAt(),
        'user_id'   => $watchlist->getUserId()
    ]);
    $watchlist->setId($this->db->lastInsertId());
}

public function remove(int $id) : void {
    $query = $this->db->prepare('DELETE FROM watchlist WHERE id = :id');
    $query->execute(['id' => $id]);
}

public function findByUser(int $user_id) : array {
    $query = $this->db->prepare('SELECT * FROM watchlist WHERE user_id = :user_id');
    $query->execute(['user_id' => $user_id]);
    $watchlist = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
        $watchlist[] = new Watchlist($result['series_id'], $result['status'],
            $result['added_at'], $result['id'], $result['user_id']);
    }
    return $watchlist;
}

public function update(Watchlist $watchlist) : void {
    $query = $this->db->prepare('UPDATE watchlist SET status = :status WHERE id = :id');
    $query->execute([
        'status' => $watchlist->getStatus(),
        'id'     => $watchlist->getId()
    ]);
}

public function findByUserAndStatut(int $user_id, string $status) : array {
    $query = $this->db->prepare('
        SELECT watchlist.*, series.title, series.image, series.status AS series_status
        FROM watchlist
        JOIN series ON watchlist.series_id = series.id
        WHERE watchlist.user_id = :user_id AND watchlist.status = :status
    ');
    $query->execute([
        'user_id' => $user_id,
        'status'  => $status
    ]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function countByUserAndStatus(int $user_id, string $status) : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM watchlist WHERE user_id = :user_id AND status = :status');
    $query->execute([
        'user_id' => $user_id,
        'status'  => $status
    ]);
    return (int)$query->fetchColumn();
}
}
