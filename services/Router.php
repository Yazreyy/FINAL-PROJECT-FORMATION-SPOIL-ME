<?php

class Router
{

    private AuthController $ac;
    private SerieController $sc;
    private WatchlistController $wc;
    private ReviewController $rc;
    private AdminController $adc;

    public function __construct()
    {
        $this->ac = new AuthController();
        $this->sc = new SerieController();
        $this->wc = new WatchlistController();
        $this->rc = new ReviewController();
        $this->adc = new AdminController();
    }

    public function handleRequest(array $get): void
    {
        if (!isset($get['route'])) {
            $this->sc->home();
        } else if ($get['route'] === 'login') {
            $this->ac->login();
        } else if ($get['route'] === 'check-login') {
            $this->ac->checklogin();
        } else if ($get['route'] === 'register') {
            $this->ac->register();
        } else if ($get['route'] === 'check-register') {
            $this->ac->checkRegister();
        } else if ($get['route'] === 'logout') {
            $this->ac->logout();
        } else if ($get['route'] === 'series') {
            $this->sc->index();
        }else if ($get['route'] === 'tendances') {
            $this->sc->tendances();}
            else if ($get['route'] === 'serie') {
            if (isset($get['id'])) {
                $this->sc->show($get['id']);
            } else {
                $this->sc->home();
            }
        } else if ($get['route'] === 'review-add') {
            $this->rc->add();
        } else if ($get['route'] === 'review-delete') {
            $this->rc->delete();
        } else if ($get['route'] === 'watchlist') {
            $this->wc->index();
        } else if ($get['route'] === 'add-watchlist') {
            $this->wc->addWatchlist();
        } else if ($get['route'] === 'remove-watchlist') {
            $this->wc->removeWatchlist();
        } else if ($get['route'] === 'watchlist-status') {
            $this->wc->changeStatus();
        } else if ($get['route'] === 'update-avatar') {
            $this->ac->updateAvatar();
        } else if ($get['route'] === 'profile') {
            if (isset($get['id'])) {
                $this->ac->profile($get['id']);
            } else {
                $this->ac->profile();
            }
        } else if ($get['route'] === 'admin') {
            $this->adc->dashboard();
        } else if ($get['route'] === 'admin-series') {
            $this->adc->manageSeries();
        } else if ($get['route'] === 'admin-series-add') {
            $this->adc->addSerie();
        } else if ($get['route'] === 'admin-series-edit') {
            if (isset($get['id'])) {
                $this->adc->editSerie((int)$get['id']);
            } else {
                $this->adc->manageSeries();
            }
        } else if ($get['route'] === 'admin-series-delete') {
            if (isset($get['id'])) {
                $this->adc->deleteSerie((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-users') {
            $this->adc->manageUsers();
        } else if ($get['route'] === 'admin-user-role') {
            $this->adc->changeRole();
        } else if ($get['route'] === 'admin-user-delete') {
            if (isset($get['id'])) {
                $this->adc->deleteUser((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-import') {
            $this->adc->importFromTmdb();
        } else if ($get['route'] === 'admin-import-genres') {
            $this->adc->importGenres();
        } else if ($get['route'] === 'admin-import-platforms') {
            $this->adc->importPlatforms();
        } else if ($get['route'] === 'admin-import-series-platforms') {
            $this->adc->importSeriesPlatforms();
        } else if ($get['route'] === 'admin-refresh-ratings') {
            $this->adc->refreshRatings();
        } else {
            $this->sc->home();
        }
    }
}
