<?php

class ReviewManager extends AbstractManager {
    
public function __construct()
{
     parent::__construct();
}

public function add (Review $review) : void {
    $query = $this->db->prepare('INSERT INTO Review (texte, est_officielle, date_creation, id_serie, id_user)
    VALUES (:texte, :est_officielle, :date_creation, :id_serie, :id_user)');
    $parameters = [
        'texte' => $review->getTexte(),
        'est_officielle' => $review->getOfficial(),
        'date_creation' => $review->getDateCreate(),
        'id_serie' => $review->getSerieid(),
        'id_user' => $review->getUserid()
    ];
    $query->execute($parameters);
    $review->setId($this->db->lastInsertId());
}

public function update(Review $review) : void {
    $query = $this->db->prepare('UPDATE Review SET text = :texte, est_officielle = :est_officielle WHERE id = :id');
    $parameters = [
        'texte' => $review->getTexte(),
        'est_officielle' => $review->getOfficial(),
        'id' => $review->getId()
    ];
    $query->execute($parameters);
}

public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM Review WHERE id = :id');
    $parameters = [
         'id' => $id
    ];
    $query->execute($parameters);
}

public function findRecent(int $limit = 5) : array {
    $query = $this->db->prepare('SELECT Review.*, User.pseudo, User.avatar, Serie.titre AS serie_titre,Serie.image AS serie_image
    FROM Review
    JOIN user ON Review.id_user = User.id
    JOIN Serie ON Review.id_serie = Serie.id
    ORDER BY date_creation DESC
    LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function countAll() : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM Review');
    $query->execute();
    return (int)$query->fetchColumn();
}

public function findBySerie(int $id_serie, string $sort = 'recent') : array {
    $orderBy = $sort === 'likes' ? 'likes_count DESC' : 'Review.date_creation DESC';
    $query = $this->db->prepare("
        SELECT Review.*, User.pseudo, User.avatar,
               COUNT(`Like`.id) AS likes_count
        FROM Review
        JOIN User ON Review.id_user = User.id
        LEFT JOIN `Like` ON `Like`.id_review = Review.id
        WHERE Review.id_serie = :id_serie
        GROUP BY Review.id
        ORDER BY $orderBy
    ");
    $query->execute(['id_serie' => $id_serie]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
}