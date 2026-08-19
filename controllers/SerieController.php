<?php


class SerieController extends AbstractController
{

    private SerieManager $sm;

    public function __construct()
    {
        $this->sm = new SerieManager();
    }

    public function home(): void
    {
        $topSeries = $this->sm->findTop(4);
        $rm = new ReviewManager();
        $recentReviews = $rm->findRecent(5, $_SESSION['user_id'] ?? null);
        $um = new UserManager();
        $vipMembers = $um->findVip(3);
        $stats = [
            'series' => $this->sm->countAll(),
            'reviews' => $rm->countAll(),
            'membres' => $um->countAll()
        ];

        $this->render('home', [
            'title' => 'Spoil Me - Découvrez, notez et débattez de vos séries',
            'description' => 'Spoil Me - la plateforme francophone pour découvrir, noter et débattre de vos séries préférées.',
            'topSeries' => $topSeries,
            'recentReviews' => $recentReviews,
            'vipMembers' => $vipMembers,
            'stats' => $stats
        ]);
    }

    public function index(): void
    {
        $filters = [];

        if (isset($_GET['genre_id'])) {
            $filters['genre_id']      = $_GET['genre_id'];
        }
        if (isset($_GET['plateforme_id'])) {
            $filters['plateforme_id'] = $_GET['plateforme_id'];
        }
        if (isset($_GET['titre'])) {
            $filters['titre']         = $_GET['titre'];
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'nouveautes';
        }
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        } else {
            $page = 1;
        }

        $series      = $this->sm->findWithFilters($filters, $sort, $page);
        $plateformes = $this->sm->findAllPlateformes();
        $genres = $this->sm->findAllGenres();

        $this->render('serie-list', [
            'title' => 'Toutes les séries - Spoil Me',
            'description' => 'Parcourez et filtrez toutes les séries disponibles sur Spoil Me par genre, plateforme ou titre.',
            'series'      => $series,
            'genres' => $genres,
            'plateformes' => $plateformes,
            'sort'        => $sort,
            'page'        => $page,
            'filters'     => $filters
        ]);
    }

    public function show(int $id): void
    {
        $serie       = $this->sm->findOne($id);
        $rm          = new ReviewManager();
        $plateformes = $this->sm->findPlateformes($id);
        $similaires  = $this->sm->findSimilar($id);

        $apiKey = $this->getEnv('TMDB_API_KEY');
        $url = 'https://api.themoviedb.org/3/tv/' . $serie->getTmdbId() . '/credits?api_key=' . $apiKey . '&language=fr-FR';
        $response = file_get_contents($url);
        $casting = json_decode($response, true)['cast'] ?? [];

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        } else {
            $sort = 'recent';
        }

        $reviews = $rm->findBySerie($id, $sort, $_SESSION['user_id'] ?? null);

        $cm = new CommentaireManager();
        $comments = [];
        foreach ($reviews as $review) {
            $comments[$review['id']] = $cm->findByReview($review['id']);
        }

        $this->render('serie-detail', [
            'title' => $serie->getTitle() . ' - Spoil Me',
            'description' => mb_substr($serie->getSynopsis(), 0, 155),
            'serie'       => $serie,
            'reviews'     => $reviews,
            'comments'    => $comments,
            'plateformes' => $plateformes,
            'similaires'  => $similaires,
            'casting'     => $casting,
            'sort'        => $sort
        ]);
    }

    public function tendances(): void
    {
        $apiKey = $this->getEnv('TMDB_API_KEY');
        $series = $this->sm->findOnGoing(60);
        $feed = [];
        $rm = new ReviewManager();
        $recentReviews = $rm->findRecent(5);

        foreach ($series as $serie) {
            $url = 'https://api.themoviedb.org/3/tv/' . $serie->getTmdbId() . '?api_key=' . $apiKey . '&language=fr-FR';
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (!empty($data['next_episode_to_air'])) {
                $feed[] = [
                    'type'         => 'episode',
                    'serie'        => $serie,
                    'next_episode' => $data['next_episode_to_air']
                ];
            }
             if (!empty($data['number_of_seasons']) && $data['number_of_seasons'] > $serie->getSaisons()) {
            $feed[] = [
                'type'              => 'season',
                'serie'             => $serie,
                'number_of_seasons' => $data['number_of_seasons']
            ];
        }
            if (($data['status'] ?? null) === 'In Production') {
                $feed[] = [
                    'type'  => 'production',
                    'serie' => $serie
                ];
            }
        }

        $this->render('tendances', [
            'title' => 'Tendances - Spoil Me',
            'description' => 'Suivez les dernières actualités des séries en cours : nouveaux épisodes, saisons et productions.',
            'feed' => $feed,
            'recentReviews' => $recentReviews
        ]);
    }
}
