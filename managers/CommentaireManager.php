<?php

class CommentaireManager extends AbstractManager {

public function __construct()
{
    parent::__construct();
}

public function add(Commentaire $commentaire) : void {
    $query = $this->db->prepare('INSERT INTO comment (content, is_valid, created_at, review_id, user_id)
        VALUES (:content, :is_valid, :created_at, :review_id, :user_id)');
    $query->execute([
        'content'    => $commentaire->getContent(),
        'is_valid'   => $commentaire->getIsValid(),
        'created_at' => $commentaire->getCreatedAt(),
        'review_id'  => $commentaire->getReviewId(),
        'user_id'    => $commentaire->getUserId()
    ]);
    $commentaire->setId($this->db->lastInsertId());
}

public function update(Commentaire $commentaire) : void {
    $query = $this->db->prepare('UPDATE comment SET content = :content, is_valid = :is_valid WHERE id = :id');
    $query->execute([
        'content'  => $commentaire->getContent(),
        'is_valid' => $commentaire->getIsValid(),
        'id'       => $commentaire->getId()
    ]);
}

public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM comment WHERE id = :id');
    $query->execute(['id' => $id]);
}

public function findByReview(int $review_id) : array {
    $query = $this->db->prepare('SELECT * FROM comment WHERE review_id = :review_id ORDER BY created_at');
    $query->execute(['review_id' => $review_id]);
    $commentaires = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
        $commentaires[] = new Commentaire($result['content'], $result['is_valid'],
            $result['created_at'], $result['id'], $result['review_id'], $result['user_id']);
    }
    return $commentaires;
}

public function findByUser(int $user_id) : array {
    $query = $this->db->prepare('SELECT * FROM comment WHERE user_id = :user_id ORDER BY created_at');
    $query->execute(['user_id' => $user_id]);
    $commentaires = [];
    foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
        $commentaires[] = new Commentaire($result['content'], $result['is_valid'],
            $result['created_at'], $result['id'], $result['review_id'], $result['user_id']);
    }
    return $commentaires;
}
}
