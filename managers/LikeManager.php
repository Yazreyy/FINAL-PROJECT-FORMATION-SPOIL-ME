<?php

class LikeManager extends AbstractManager {

public function __construct() {
    parent::__construct();
}

public function add(int $user_id, int $review_id) : void {
    $query = $this->db->prepare('INSERT INTO user_like (user_id, review_id, created_at)
        VALUES (:user_id, :review_id, :created_at)');
    $query->execute([
        'user_id'    => $user_id,
        'review_id'  => $review_id,
        'created_at' => date('Y-m-d H:i:s')
    ]);
}

public function remove(int $user_id, int $review_id) : void {
    $query = $this->db->prepare('DELETE FROM user_like WHERE user_id = :user_id AND review_id = :review_id');
    $query->execute([
        'user_id'   => $user_id,
        'review_id' => $review_id
    ]);
}

public function exists(int $user_id, int $review_id) : bool {
    $query = $this->db->prepare('SELECT COUNT(*) FROM user_like WHERE user_id = :user_id AND review_id = :review_id');
    $query->execute([
        'user_id'   => $user_id,
        'review_id' => $review_id
    ]);
    return (bool)$query->fetchColumn();
}
}
