<?php

class SerieManager extends AbstractManager {

public function __construct()
{
     parent::__construct();
}

public function findAll() : array {
    $query = $this->db->prepare('SELECT * FROM Serie');
    $parameters = [
            ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];

    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
        $result['image'], $result['note_moyenne'],$result['statut'], $result['date_creation'],
        $result['created_by'], $result['id'], $result['id_tmdb'],
        $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}

public function findOne(?int $id) : Serie {
    $query = $this->db->prepare('SELECT * FROM Serie WHERE id = :id');
    $parameters = [
        'id' => $id
    ];
    $query->execute($parameters);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
        $result['image'], $result['note_moyenne'], $result['statut'],
        $result['date_creation'],$result['created_by'], $result['id'], $result['id_tmdb'],
        $result['format'], $result['createur'], $result['pays'], $result['langue']);
}

public function findByGenre(?int $genre_id) : array {
    $query = $this->db->prepare('SELECT Serie.* FROM SERIE
    JOIN serie_genre ON Serie.id = serie_genre.serie_id
    WHERE serie_genre.genre_id = :genre_id');
    $parameters = [
        'genre_id' => $genre_id 
    ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];
    
    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
            $result['image'], $result['note_moyenne'], $result['statut'], $result['date_creation'],
            $result['created_by'], $result['id'], $result['id_tmdb'],
            $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}

public function findByTitre(string $titre) : array  {
    $query = $this->db->prepare('SELECT * FROM Serie WHERE titre LIKE :titre');
    $parameters = [
        'titre' => '%' . $titre . '%'
    ];
    $query->execute($parameters);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];

    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
            $result['image'], $result['note_moyenne'], $result['statut'], $result['date_creation'],
            $result['created_by'], $result['id'], $result['id_tmdb'],
            $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}

public function create(Serie $serie) : void {
    $query = $this->db->prepare('insert INTO Serie (titre, synopsis, date_sortie, image, note_moyenne, statut, date_creation, created_by, id_tmdb, format, createur, pays, langue)
    VALUES (:titre, :synopsis, :date_sortie, :image, :note_moyenne, :statut, :date_creation, :created_by, :id_tmdb, :format, :createur, :pays, :langue)');
    $parameters = [
        'titre' => $serie->getTitre(),
        'synopsis' => $serie->getSynopsis(),
        'date_sortie' => $serie->getDate(),
        'image' => $serie->getImage(),
        'note_moyenne' => $serie->getNote(),
        'statut' => $serie->getStatut(),
        'date_creation' => $serie->getCreatedDate(),
        'created_by' => $serie->getCreatedBy(),
        'id_tmdb' => $serie->getIdtmdb(),
        'format' => $serie->getFormat(),
        'createur' => $serie->getCreateur(),
        'pays' => $serie->getPays(),
        'langue' => $serie->getLangue()
    ];
    $query->execute($parameters);
    $serie->setId($this->db->lastInsertId());
}

public function update(Serie $serie) : void {
    $query = $this->db->prepare('UPDATE Serie SET titre = :titre, synopsis = :synopsis, 
    date_sortie = :date_sortie, image = :image, note_moyenne = :note_moyenne, 
    statut = :statut, date_creation = :date_creation, created_by = :created_by, 
    id_tmdb = :id_tmdb, format = :format, createur = :createur, pays = :pays, langue = :langue WHERE id = :id');
    $parameters = [
        'titre' => $serie->getTitre(),
        'synopsis' => $serie->getSynopsis(),
        'date_sortie' => $serie->getDate(),
        'image' => $serie->getImage(),
        'note_moyenne' => $serie->getNote(),
        'statut' => $serie->getStatut(),
        'date_creation' => $serie->getCreatedDate(),
        'created_by' => $serie->getCreatedBy(),
        'id_tmdb' => $serie->getIdtmdb(),
        'format' => $serie->getFormat(),
        'createur' => $serie->getCreateur(),
        'pays' => $serie->getPays(),
        'langue' => $serie->getLangue(),
        'id' => $serie->getId()
    ];
    $query->execute($parameters);
}

public function delete(int $id) : void {
    $query = $this->db->prepare('DELETE FROM Serie WHERE id = :id');
    $parameters = [
        'id' => $id
    ];
    $query->execute($parameters);
}

public function findTop(int $limit = 4) : array {
    $query = $this->db->prepare('SELECT * FROM Serie ORDER BY note_moyenne desc LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];

    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
            $result['image'], $result['note_moyenne'], $result['statut'], $result['date_creation'],
            $result['created_by'], $result['id'], $result['id_tmdb'],
            $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}

public function countAll() : int {
    $query = $this->db->prepare('SELECT COUNT(*) FROM Serie');
    $query->execute();
    return (int)$query->fetchColumn();
}

public function findWithFilters(array $filters = [], string $sort = 'pertinence', int $page = 1, int $perPage = 20) : array {
    $sql = 'SELECT * FROM Serie WHERE 1=1';
    $parameters = [];

    if (!empty($filters['genre_id'])) {
        $sql .= ' AND id IN (SELECT serie_id FROM serie_genre WHERE genre_id = :genre_id)';
        $parameters['genre_id'] = $filters['genre_id'];
    }
    if (!empty($filters['plateforme_id'])) {
        $sql .= ' AND id IN (SELECT serie_id FROM serie_plateforme WHERE plateforme_id = :plateforme_id)';
        $parameters['plateforme_id'] = $filters['plateforme_id'];
    }
    if (!empty($filters['note_min'])) {
        $sql .= ' AND note_moyenne >= :note_min';
        $parameters['note_min'] = $filters['note_min'];
    }
    if (!empty($filters['annee_min'])) {
        $sql .= ' AND YEAR(date_sortie) >= :annee_min';
        $parameters['annee_min'] = $filters['annee_min'];
    }
    if (!empty($filters['annee_max'])) {
        $sql .= ' AND YEAR(date_sortie) <= :annee_max';
        $parameters['annee_max'] = $filters['annee_max'];
    }
    if (!empty($filters['titre'])) {
        $sql .= ' AND titre LIKE :titre';
        $parameters['titre'] = '%' . $filters['titre'] . '%';
    }

    $sql .= match($sort) {
        'note'       => ' ORDER BY note_moyenne DESC',
        'nouveautes' => ' ORDER BY date_sortie DESC',
        default      => ' ORDER BY id DESC'
    };

    $offset = ($page - 1) * $perPage;
    $sql .= ' LIMIT :limit OFFSET :offset';

    $query = $this->db->prepare($sql);
    foreach($parameters as $key => $value) {
        $query->bindValue($key, $value);
    }
    $query->bindValue('limit', $perPage, PDO::PARAM_INT);
    $query->bindValue('offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];

    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
            $result['image'], $result['note_moyenne'], $result['statut'], $result['date_creation'],
            $result['created_by'], $result['id'], $result['id_tmdb'],
            $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}

public function findPlateformes(int $id_serie) : array {
    $query = $this->db->prepare('
        SELECT plateforme.*
        FROM plateforme
        JOIN serie_plateforme ON plateforme.id = serie_plateforme.plateforme_id
        WHERE serie_plateforme.serie_id = :id_serie
    ');
    $query->execute(['id_serie' => $id_serie]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function findAllPlateformes() : array {
    $query = $this->db->prepare('SELECT * FROM plateforme ORDER BY nom ASC');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function findSimilar(int $id_serie, int $limit = 4) : array {
    $query = $this->db->prepare('
        SELECT DISTINCT Serie.* FROM Serie
        JOIN serie_genre ON Serie.id = serie_genre.serie_id
        WHERE serie_genre.genre_id IN (
            SELECT genre_id FROM serie_genre WHERE serie_id = :id_serie
        )
        AND Serie.id != :id_serie
        ORDER BY note_moyenne DESC
        LIMIT :limit
    ');
    $query->bindValue('id_serie', $id_serie, PDO::PARAM_INT);
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $series = [];

    foreach($results as $result) {
        $series[] = new Serie($result['titre'], $result['synopsis'], $result['date_sortie'],
            $result['image'], $result['note_moyenne'], $result['statut'], $result['date_creation'],
            $result['created_by'], $result['id'], $result['id_tmdb'],
            $result['format'], $result['createur'], $result['pays'], $result['langue']);
    }
    return $series;
}
}