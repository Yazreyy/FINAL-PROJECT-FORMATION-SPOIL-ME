<?php


class CommentaireManager extends AbstractManager {


public function __construct()
{
    parent::__construct();
}


Public function add(Commentaire $commentaire) : void {
    $query = $this->db->prepare('INSERT INTO Commentaire (texte, est_valide, date_creation, id_review, id_user)
    VALUES (:texte, :est_valide, :date_creation, :id_review, :id_user)');
    $parameters = [
        'texte' => $commentaire->getTexte(),
        'est_valide' => $commentaire->getValidate(),
        'date_creation' => $commentaire->getDate(),
        'id_review' => $commentaire->getReviewId(),
        'id_user' => $commentaire->getUserId()
    ];
    $query->execute($parameters);
    $commentaire->setId($this->db->lastInsertId());
}

Public function update(Commentaire $commentaire) : void {
 $query = $this->db->prepare('UPDATE Commentaire SET texte = :texte, est_valide = :est_valide WHERE id = :id');
 $parameters = [
    'texte' => $commentaire->getTexte(),
    'est_valide' => $commentaire->getValidate(),
    'id' => $commentaire->getId()
 ];
 $query->execute($parameters);   
}

Public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM Commentaire WHERE id = :id');
    $parameters= [
        'id' => $id
    ];
    $query->execute($parameters);
}

Public function findByReview(int $id_review) : array {
    $query = $this->db->prepare('SELECT * FROM Commentaire WHERE id_review = :id_review ORDER BY date_creation');
    $parameters = [
        'id_review' => $id_review
    ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $commentaires = [];

    foreach ($results as $result) {
        $commentaires[] = new Commentaire($result['texte'], $result['est_valide'], $result['date_creation'],
            $result['id'], $result['id_review'], $result['id_user']);
}
return $commentaires;
}


Public function findByUser(int $id_user) : array {
    $query = $this->db->prepare('SELECT * FROM Commentaire WHERE id_user = :id_user ORDER BY date_creation');
    $parameters = [
        'id_user' => $id_user
    ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $commentaires = [];

    foreach ($results as $result) {
        $commentaires[] = new Commentaire($result['texte'], $result['est_valide'], $result['date_creation'],
            $result['id'], $result['id_review'], $result['id_user']);
    }
    return $commentaires;
}
}