<?php

class ReviewManager extends AbstractManager {

public function __construct()
{
    parent::__construct();
}

public function add(Review $review) : void {
    $query = $this->db->prepare('INSERT INTO review (content, is_official, created_at, series_id, user_id)
        VALUES (:content, :is_official, :created_at, :series_id, :user_id)');
    $query->execute([
        'content'    => $review->getContent(),
        'is_official' => $review->getIsOfficial() ? 1 : 0,
        'created_at' => $review->getCreatedAt(),
        'series_id'  => $review->getSeriesId(),
        'user_id'    => $review->getUserId()
    ]);
    $review->setId($this->db->lastInsertId());
}

public function update(Review $review) : void {
    $query = $this->db->prepare('UPDATE review SET content = :content, is_official = :is_official WHERE id = :id');
    $query->execute([
        'content'     => $review->getContent(),
        'is_official' => $review->getIsOfficial() ? 1 : 0,
        'id'          => $review->getId()
    ]);
}

public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM review WHERE id = :id');
    $query->execute(['id' => $id]);
}

public function findOne(int $id) : ?Review {
    $query = $this->db->prepare('SELECT * FROM review WHERE id = :id');
    $query->execute(['id' => $id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) return null;

    return new Review(
        $result['content'],
        (bool)$result['is_official'],
        $result['created_at'],
        $result['id'],
        $result['series_id'],
        $result['user_id']
    );
}

public function findRecent(int $limit = 5) : array {
    $query = $this->db->prepare('
        SELECT review.*, user.username, user.avatar,
               series.title AS series_title, series.image AS series_image,
               rating.value AS note_value
        FROM review
        JOIN user ON review.user_id = user.id
        JOIN series ON review.series_id = series.id
        LEFT JOIN rating ON rating.user_id = review.user_id AND rating.series_id = review.series_id
        ORDER BY review.created_at DESC
        LIMIT :limit
    ');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function countAll() : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM review');
    $query->execute();
    return (int)$query->fetchColumn();
}

public function countByUser(int $user_id) : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM review WHERE user_id = :user_id');
    $query->execute(['user_id' => $user_id]);
    return (int)$query->fetchColumn();
}

public function findByUser(int $user_id) : array {
    $query = $this->db->prepare('
        SELECT review.*, series.title AS series_title, series.image AS series_image,
               rating.value AS note_value
        FROM review
        JOIN series ON review.series_id = series.id
        LEFT JOIN rating ON rating.user_id = review.user_id AND rating.series_id = review.series_id
        WHERE review.user_id = :user_id
        ORDER BY review.created_at DESC
    ');
    $query->execute(['user_id' => $user_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function findBySerie(int $series_id, string $sort = 'recent') : array {
    $orderBy = $sort === 'likes' ? 'likes_count DESC' : 'review.created_at DESC';
    $query = $this->db->prepare("
        SELECT review.*, user.username, user.avatar,
               rating.value AS note_value,
               COUNT(user_like.id) AS likes_count
        FROM review
        JOIN user ON review.user_id = user.id
        LEFT JOIN user_like ON user_like.review_id = review.id
        LEFT JOIN rating ON rating.user_id = review.user_id AND rating.series_id = review.series_id
        WHERE review.series_id = :series_id
        GROUP BY review.id
        ORDER BY $orderBy
    ");
    $query->execute(['series_id' => $series_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
}
