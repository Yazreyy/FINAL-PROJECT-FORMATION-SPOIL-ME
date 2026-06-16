<?php

class AdminController extends AbstractController
{

    private SerieManager $sm;
    private UserManager $um;
    private ReviewManager $rm;


    public function __construct()
    {
        $this->sm = new SerieManager();
        $this->um = new UserManager();
        $this->rm = new ReviewManager();
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats = [
            'series' => $this->sm->countAll(),
            'users' => $this->um->countAll(),
            'reviews' => $this->rm->countAll(),
        ];
        $recentReviews = $this->rm->findRecent(10);

        $this->render('admin/dashboard', [
            'stats' => $stats,
            'recentReviews' => $recentReviews,
        ]);
    }

    public function manageSeries(): void
    {
        $this->requireAdmin();

        $series = $this->sm->findAll();

        $this->render('admin/manage-series', ['series' => $series]);
    }

    public function addSerie(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $serie = new Serie(
                $_POST['titre']      ?? '',
                $_POST['synopsis']   ?? '',
                $_POST['date_sortie'] ?? date('Y-m-d'),
                $_POST['image']      ?? '',
                0.0,
                $_POST['statut']     ?? 'en cours',
                date('Y-m-d H:i:s'),
                $_SESSION['user_id'],
                null,
                !empty($_POST['id_tmdb'])   ? (int)$_POST['id_tmdb']  : null,
                !empty($_POST['format'])    ? $_POST['format']         : null,
                !empty($_POST['createur'])  ? $_POST['createur']       : null,
                !empty($_POST['pays'])      ? $_POST['pays']           : null,
                !empty($_POST['langue'])    ? $_POST['langue']         : null
            );
            $this->sm->create($serie);
            $this->redirect('admin-series');
        }

        $this->render('admin/serie-form', ['serie' => null, 'action' => 'admin-serie-add']);
    }

    public function editSerie(int $id): void
    {
        $this->requireAdmin();

        $serie = $this->sm->findOne($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $serie->setTitle($_POST['titre']           ?? $serie->getTitle());
            $serie->setSynopsis($_POST['synopsis']     ?? $serie->getSynopsis());
            $serie->setReleaseDate($_POST['date_sortie'] ?? $serie->getReleaseDate());
            $serie->setImage($_POST['image']           ?? $serie->getImage());
            $serie->setStatus($_POST['statut']         ?? $serie->getStatus());
            $serie->setFormat(!empty($_POST['format'])     ? $_POST['format']   : null);
            $serie->setCreator(!empty($_POST['createur'])  ? $_POST['createur'] : null);
            $serie->setCountry(!empty($_POST['pays'])      ? $_POST['pays']     : null);
            $serie->setLanguage(!empty($_POST['langue'])   ? $_POST['langue']   : null);
            $this->sm->update($serie);
            $this->redirect('admin-series');
        }

        $this->render('admin/serie-form', [
            'serie'  => $serie,
            'action' => 'admin-serie-edit&id=' . $id,
        ]);
    }

    public function deleteSerie(int $id): void
    {
        $this->requireAdmin();
        $this->sm->delete($id);
        $this->redirect('admin-series');
    }

    public function manageUsers(): void
    {
        $this->requireAdmin();
        $users = $this->um->findAll();

        $this->render('admin/manage-users', ['users' => $users]);
    }

    public function changeRole(): void
    {
        $this->requireAdmin();

        $id   = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $role = $_POST['role'] ?? null;

        if ($id && in_array($role, ['user', 'vip', 'admin'])) {
            $user = $this->um->findById($id);
            if ($user) {
                $user->setRole($role);
                $this->um->update($user);
            }
        }
        $this->redirect('admin-users');
    }

    public function deleteUser(int $id): void
    {
        $this->requireAdmin();


        if ($id === $_SESSION['user_id']) {
            $this->redirect('admin-users');
        }

        $user = $this->um->findById($id);
        if ($user) {
            $this->um->delete($user);
        }
        $this->redirect('admin-users');
    }

    public function importFromTmdb() : void {
    // $this->requireAdmin();

    $apiKey = '3af9fea10037a0d3bc7de800df308adb';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $url = 'https://api.themoviedb.org/3/tv/popular?api_key=' . $apiKey . '&language=fr-FR&page=' . $page;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    $imported = 0;
    $skipped = 0;

    foreach($data['results'] as $s) {
        if ($this->sm->findByTmdbId($s['id'])) {
            $skipped++;
            continue;
        }
        $imported++;
        $serie = new Serie(
            $s['name'],
            $s['overview'],
            $s['first_air_date'] ?? date('Y-m-d'),
            'https://image.tmdb.org/t/p/w500' . $s['poster_path'],
            $s['vote_average'],
            'en cours',
            date('Y-m-d H:i:s'),
            $_SESSION['user_id'] ?? 1,
            null,
            $s['id'],
            null,
            null,
            $s['origin_country'][0] ?? null,
            $s['original_language'],
            $s['number_of_seasons'] ?? null,
            $s['number_of_episodes'] ?? null,
            'https://image.tmdb.org/t/p/w1280' . ($s['backdrop_path'] ?? '')
        );
        $this->sm->create($serie);
        foreach($s['genre_ids'] as $genreId) {
    $query = $this->sm->getDb()->prepare(
        'INSERT IGNORE INTO series_genre (series_id, genre_id) VALUES (:series_id, :genre_id)'
    );
    $query->execute([
        'series_id' => $serie->getId(),
        'genre_id'  => $genreId
    ]);
}
    }

    echo "Import terminé ! $imported nouvelles séries, $skipped déjà existantes.";
}

public function refreshRatings() : void {
    $this->requireAdmin();

    $apiKey = '3af9fea10037a0d3bc7de800df308adb';
    $series = $this->sm->findAll();

    foreach ($series as $serie) {
        if (!$serie->getTmdbId()) {
            continue;
        }

        $url = 'https://api.themoviedb.org/3/tv/' . $serie->getTmdbId() . '?api_key=' . $apiKey . '&language=fr-FR';
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['vote_average'])) {
            $serie->setAverageRating((float)$data['vote_average']);
            $this->sm->update($serie);
        }
    }

    echo 'Notes rafraîchies !';
}

public function importGenres() : void {
    $apiKey = '3af9fea10037a0d3bc7de800df308adb';
    $url = 'https://api.themoviedb.org/3/genre/tv/list?api_key=' . $apiKey . '&language=fr-FR';
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    foreach($data['genres'] as $genre) {
        $query = $this->sm->getDb()->prepare('INSERT IGNORE INTO genre (id, name) VALUES (:id, :name)');
        $query->execute(['id' => $genre['id'], 'name' => $genre['name']]);
    }

    echo 'Genres importés !';
}

public function importPlatforms() : void {
    $this->requireAdmin();

    $apiKey = '3af9fea10037a0d3bc7de800df308adb';
    $url = 'https://api.themoviedb.org/3/watch/providers/tv?api_key=' . $apiKey . '&watch_region=FR&language=fr-FR';
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    foreach ($data['results'] as $provider) {
        $query = $this->sm->getDb()->prepare('INSERT IGNORE INTO platform (id, name, logo) VALUES (:id, :name, :logo)');
        $query->execute([
            'id'   => $provider['provider_id'],
            'name' => $provider['provider_name'],
            'logo' => 'https://image.tmdb.org/t/p/w200' . $provider['logo_path']
        ]);
    }

    echo 'Plateformes importées !';
}

public function importSeriesPlatforms() : void {
    $this->requireAdmin();

    $apiKey = '3af9fea10037a0d3bc7de800df308adb';
    $series = $this->sm->findAll();

    $debugCount = 0;
    $debugSkipped = 0;
    $debugInserted = 0;
    $debugLines = [];

    foreach ($series as $serie) {
        if (!$serie->getTmdbId()) {
            $debugSkipped++;
            continue;
        }

        $debugCount++;

        $url = 'https://api.themoviedb.org/3/tv/' . $serie->getTmdbId() . '/watch/providers?api_key=' . $apiKey;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        $providers = $data['results']['FR']['flatrate'] ?? [];

        $debugLines[] = $serie->getTitle() . ' (id=' . $serie->getId() . ', tmdb=' . $serie->getTmdbId() . ') -> ' . count($providers) . ' provider(s)';

        foreach ($providers as $provider) {
            $query = $this->sm->getDb()->prepare('INSERT IGNORE INTO series_platform (series_id, platform_id) VALUES (:series_id, :platform_id)');
            $query->execute([
                'series_id'   => $serie->getId(),
                'platform_id' => $provider['provider_id']
            ]);
            $debugInserted += $query->rowCount();
        }
    }

    echo "Séries avec tmdb_id traitées : $debugCount<br>";
    echo "Séries ignorées (pas de tmdb_id) : $debugSkipped<br>";
    echo "Lignes réellement insérées : $debugInserted<br><br>";
    echo implode('<br>', $debugLines);
}
}
