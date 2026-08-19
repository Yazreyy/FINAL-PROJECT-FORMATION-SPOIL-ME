<?php

class WatchlistController extends AbstractController
{

    private WatchListManager $wm;

    public function __construct()
    {
        $this->wm = new WatchListManager();
    }

    public function index(): void
    {
        $this->requireLogin();

        if (isset($_GET['statut'])) {
            $statut = $_GET['statut'];
        } else {
            $statut = 'à voir';
        }

        $watchlist = $this->wm->findByUserAndStatut($_SESSION['user_id'], $statut);

        $this->render('watchlist', [
            'watchlist' => $watchlist,
            'statut' => $statut
        ]);
    }

    public function addWatchlist(): void
    {
        $this->requireLogin();
        $this->verifyCsrf();

        if (isset($_POST['id_serie'])) {
            $id_serie = (int)$_POST['id_serie'];
        } else {
            $id_serie = 0;
        }
        if (isset($_POST['statut'])) {
            $statut = $_POST['statut'];
        } else {
            $statut = 'à voir';
        }

        if (!$this->wm->exists($_SESSION['user_id'], $id_serie)) {
            $entry = new Watchlist($id_serie, $statut, date('Y-m-d H:i:s'), $_SESSION['user_id']);
            $this->wm->add($entry);
        }

        $this->redirect('serie?id=' . $id_serie);
    }

    public function removeWatchlist(): void
    {
        $this->requireLogin();

        if (isset($_GET['id_serie'])) {
            $id_serie = (int)$_GET['id_serie'];
        } else {
            $id_serie = 0;
        }

        $this->wm->remove($_SESSION['user_id'], $id_serie);

        $this->redirect('watchlist');
    }

    public function changeStatus(): void
    {
        $this->requireLogin();
        $this->verifyCsrf();

        if (isset($_POST['id_serie'])) {
            $id_serie = (int)$_POST['id_serie'];
        } else {
            $id_serie = 0;
        }
        if (isset($_POST['statut'])) {
            $statut = $_POST['statut'];
        } else {
            $statut = 'à voir';
        }

        $entry = new Watchlist($id_serie, $statut, date('Y-m-d H:i:s'), $_SESSION['user_id']);
        $this->wm->update($entry);

        $this->redirect('watchlist?statut=' . $statut);
    }
}
