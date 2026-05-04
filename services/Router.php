<?php

class Router {
    
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

public function handleRequest(array $get) : void {
    if(!isset($get['route'])) {
        $this->sc->home();
    }

    else if($get['route'] === 'login') {
        $this->ac->login();
    }
    else if($get['route'] === 'check-login') {
        $this->ac->checklogin();
    }
    else if($get['route'] === 'register') {
        $this->ac->register();
    }
    else if($get['route'] === 'check-register') {
        $this->ac->checkRegister();
    }
    else if($get['route'] === 'logout') {
        $this->ac->logout();
    }

    else if($get['route'] === 'series') {
        $this->sc->index();
    }
    else if($get['route'] === 'serie') {
        if(isset($get['id'])) {
            $this->sc->show($get['id']);
            } else {
                $this->sc->home();
            }
        }
    
        else if($get['route'] === 'review-add') {
            $this->rc->add();
        }
        else if($get['route'] === 'review-delete') {
            $this->rc->delete();
        }

        else if($get['route'] === 'watchlist') {
            $this->wc->index();
        }
        else if($get['route'] === 'add-watchlist') {
            $this->wc->addWatchlist();
        }
        else if($get['route'] === 'remove-watchlist') {
            $this->wc->removeWatchlist();
        }

        else if($get['route'] === 'profile') {
            if(isset($get['id'])) {
                $this->ac->profil($get['id']);
            } else {
            $this->ac->profil();
        }
        }
        

        else if($get['route'] === 'admin') {
            $this->adc->dashboard();
        }
        else if($get['route'] === 'admin-series') {
            $this->adc->manageSeries();
        }

        else {
            $this->sc->home();
        }
    }
}
