<?php

class SerieManager extends AbstractManager
{

    public function __construct()
    {
        parent::__construct();
    }

    private function hydrate(array $result): Serie
    {
        return new Serie(
            $result['title'],
            $result['synopsis'],
            $result['release_date'],
            $result['image'],
            $result['average_rating'],
            $result['status'],
            $result['created_at'],
            $result['created_by'],
            $result['id'],
            $result['tmdb_id'],
            $result['format'],
            $result['creator'],
            $result['country'],
            $result['language'],
            $result['nb_saisons'],
            $result['nb_episodes'],
            $result['backdrop']
        );
    }

    public function findByTmdbId(int $tmdb_id): ?Serie
    {
        $query = $this->db->prepare('SELECT * FROM series WHERE tmdb_id = :tmdb_id');
        $query->execute(['tmdb_id' => $tmdb_id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) return null;

        return $this->hydrate($result);
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM series');
        $query->execute();
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function findOne(?int $id): Serie
    {
        $query = $this->db->prepare('SELECT * FROM series WHERE id = :id');
        $query->execute(['id' => $id]);
        return $this->hydrate($query->fetch(PDO::FETCH_ASSOC));
    }

    public function findByGenre(?int $genre_id): array
    {
        $query = $this->db->prepare('SELECT series.* FROM series
        JOIN series_genre ON series.id = series_genre.series_id
        WHERE series_genre.genre_id = :genre_id');
        $query->execute(['genre_id' => $genre_id]);
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function findByTitle(string $title): array
    {
        $query = $this->db->prepare('SELECT * FROM series WHERE title LIKE :title');
        $query->execute(['title' => '%' . $title . '%']);
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function create(Serie $serie): void
    {
        $query = $this->db->prepare('INSERT INTO series (title, synopsis, release_date, image, average_rating, status, created_at, created_by, tmdb_id, format, creator, country, language, nb_saisons, nb_episodes, backdrop)
        VALUES (:title, :synopsis, :release_date, :image, :average_rating, :status, :created_at, :created_by, :tmdb_id, :format, :creator, :country, :language, :nb_saisons, :nb_episodes, :backdrop)');
        $query->execute([
            'title'          => $serie->getTitle(),
            'synopsis'       => $serie->getSynopsis(),
            'release_date'   => $serie->getReleaseDate(),
            'image'          => $serie->getImage(),
            'average_rating' => $serie->getAverageRating(),
            'status'         => $serie->getStatus(),
            'created_at'     => $serie->getCreatedAt(),
            'created_by'     => $serie->getCreatedBy(),
            'tmdb_id'        => $serie->getTmdbId(),
            'format'         => $serie->getFormat(),
            'creator'        => $serie->getCreator(),
            'country'        => $serie->getCountry(),
            'language'       => $serie->getLanguage(),
            'nb_saisons'     => $serie->getSaisons(),
            'nb_episodes'    => $serie->getEpisodes(),
            'backdrop'       => $serie->getBackdrop()
        ]);
        $serie->setId($this->db->lastInsertId());
    }

    public function update(Serie $serie): void
    {
        $query = $this->db->prepare('UPDATE series SET title = :title, synopsis = :synopsis,
        release_date = :release_date, image = :image, average_rating = :average_rating,
        status = :status, created_at = :created_at, created_by = :created_by,
        tmdb_id = :tmdb_id, format = :format, creator = :creator, country = :country, language = :language
        WHERE id = :id');
        $query->execute([
            'title'          => $serie->getTitle(),
            'synopsis'       => $serie->getSynopsis(),
            'release_date'   => $serie->getReleaseDate(),
            'image'          => $serie->getImage(),
            'average_rating' => $serie->getAverageRating(),
            'status'         => $serie->getStatus(),
            'created_at'     => $serie->getCreatedAt(),
            'created_by'     => $serie->getCreatedBy(),
            'tmdb_id'        => $serie->getTmdbId(),
            'format'         => $serie->getFormat(),
            'creator'        => $serie->getCreator(),
            'country'        => $serie->getCountry(),
            'language'       => $serie->getLanguage(),
            'id'             => $serie->getId()
        ]);
    }

    public function delete(int $id): void
    {
        $query = $this->db->prepare('DELETE FROM series WHERE id = :id');
        $query->execute(['id' => $id]);
    }

    public function findTop(int $limit = 4): array
    {
        $query = $this->db->prepare('SELECT * FROM series ORDER BY average_rating DESC LIMIT :limit');
        $query->bindValue('limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function findOnGoing(int $limit = 10): array
    {
        $query = $this->db->prepare('SELECT * FROM series WHERE status = \'en cours\' AND tmdb_id IS NOT NULL LIMIT :limit');
        $query->bindValue('limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function countAll(): int
    {
        $query = $this->db->prepare('SELECT COUNT(*) FROM series');
        $query->execute();
        return (int)$query->fetchColumn();
    }

    public function findWithFilters(array $filters = [], string $sort = 'nouveautes', int $page = 1, int $perPage = 20): array
    {
        $sql = 'SELECT * FROM series WHERE 1=1';
        $parameters = [];

        if (!empty($filters['genre_id'])) {
            $sql .= ' AND id IN (SELECT series_id FROM series_genre WHERE genre_id = :genre_id)';
            $parameters['genre_id'] = $filters['genre_id'];
        }
        if (!empty($filters['plateforme_id'])) {
            $sql .= ' AND id IN (SELECT series_id FROM series_platform WHERE platform_id = :platform_id)';
            $parameters['platform_id'] = $filters['plateforme_id'];
        }
        if (!empty($filters['titre'])) {
            $sql .= ' AND title LIKE :title';
            $parameters['title'] = '%' . $filters['titre'] . '%';
        }

        $sql .= match ($sort) {
            'populaire' => ' ORDER BY average_rating DESC',
            default     => ' ORDER BY release_date DESC',
        };

        $offset = ($page - 1) * $perPage;
        $sql .= ' LIMIT :limit OFFSET :offset';

        $query = $this->db->prepare($sql);
        foreach ($parameters as $key => $value) {
            $query->bindValue($key, $value);
        }
        $query->bindValue('limit', $perPage, PDO::PARAM_INT);
        $query->bindValue('offset', $offset, PDO::PARAM_INT);
        $query->execute();

        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function findPlateformes(int $series_id): array
    {
        $query = $this->db->prepare('
        SELECT platform.*
        FROM platform
        JOIN series_platform ON platform.id = series_platform.platform_id
        WHERE series_platform.series_id = :series_id
    ');
        $query->execute(['series_id' => $series_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllPlateformes(): array
    {
        $query = $this->db->prepare('SELECT * FROM platform ORDER BY name ASC');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findSimilar(int $series_id, int $limit = 4): array
    {
        $query = $this->db->prepare('
        SELECT DISTINCT series.* FROM series
        JOIN series_genre ON series.id = series_genre.series_id
        WHERE series_genre.genre_id IN (
            SELECT genre_id FROM series_genre WHERE series_id = :series_id
        )
        AND series.id != :series_id
        ORDER BY average_rating DESC
        LIMIT :limit
    ');
        $query->bindValue('series_id', $series_id, PDO::PARAM_INT);
        $query->bindValue('limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $series = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $result) {
            $series[] = $this->hydrate($result);
        }
        return $series;
    }

    public function findAllGenres(): array
    {
        $query = $this->db->prepare('SELECT * FROM genre ORDER BY name ASC');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
