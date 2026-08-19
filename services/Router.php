<?php

class Router
{

    public function handleRequest(array $get): void
    {
        if (!isset($get['route'])) {
            (new SerieController())->home();
        } else if ($get['route'] === 'login') {
            (new AuthController())->login();
        } else if ($get['route'] === 'check-login') {
            (new AuthController())->checklogin();
        } else if ($get['route'] === 'register') {
            (new AuthController())->register();
        } else if ($get['route'] === 'check-register') {
            (new AuthController())->checkRegister();
        } else if ($get['route'] === 'logout') {
            (new AuthController())->logout();
        } else if ($get['route'] === 'series') {
            (new SerieController())->index();
        }else if ($get['route'] === 'tendances') {
            (new SerieController())->tendances();}
            else if ($get['route'] === 'serie') {
            if (isset($get['id'])) {
                (new SerieController())->show($get['id']);
            } else {
                (new SerieController())->home();
            }
        } else if ($get['route'] === 'like-review') {
            (new LikeController())->toggle();
        } else if ($get['route'] === 'comment-add') {
            (new CommentaireController())->add();
        } else if ($get['route'] === 'review-add') {
            (new ReviewController())->add();
        } else if ($get['route'] === 'review-delete') {
            (new ReviewController())->delete();
        } else if ($get['route'] === 'watchlist') {
            (new WatchlistController())->index();
        } else if ($get['route'] === 'add-watchlist') {
            (new WatchlistController())->addWatchlist();
        } else if ($get['route'] === 'remove-watchlist') {
            (new WatchlistController())->removeWatchlist();
        } else if ($get['route'] === 'watchlist-status') {
            (new WatchlistController())->changeStatus();
        } else if ($get['route'] === 'update-avatar') {
            (new AuthController())->updateAvatar();
        } else if ($get['route'] === 'update-profile') {
            (new AuthController())->updateProfile();
        } else if ($get['route'] === 'delete-account') {
            (new AuthController())->deleteAccount();
        } else if ($get['route'] === 'profile') {
            if (isset($get['id'])) {
                (new AuthController())->profile($get['id']);
            } else {
                (new AuthController())->profile();
            }
        } else if ($get['route'] === 'admin') {
            (new AdminController())->dashboard();
        } else if ($get['route'] === 'admin-series') {
            (new AdminController())->manageSeries();
        } else if ($get['route'] === 'admin-series-add') {
            (new AdminController())->addSerie();
        } else if ($get['route'] === 'admin-series-edit') {
            if (isset($get['id'])) {
                (new AdminController())->editSerie((int)$get['id']);
            } else {
                (new AdminController())->manageSeries();
            }
        } else if ($get['route'] === 'admin-series-delete') {
            if (isset($get['id'])) {
                (new AdminController())->deleteSerie((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-users') {
            (new AdminController())->manageUsers();
        } else if ($get['route'] === 'admin-user-role') {
            (new AdminController())->changeRole();
        } else if ($get['route'] === 'admin-user-delete') {
            if (isset($get['id'])) {
                (new AdminController())->deleteUser((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-comments') {
            (new AdminController())->manageComments();
        } else if ($get['route'] === 'admin-comment-delete') {
            if (isset($get['id'])) {
                (new AdminController())->deleteComment((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-reviews') {
            (new AdminController())->manageReviews();
        } else if ($get['route'] === 'admin-review-delete') {
            if (isset($get['id'])) {
                (new AdminController())->deleteReview((int)$get['id']);
            }
        } else if ($get['route'] === 'admin-import') {
            (new AdminController())->importFromTmdb();
        } else if ($get['route'] === 'admin-import-genres') {
            (new AdminController())->importGenres();
        } else if ($get['route'] === 'admin-import-platforms') {
            (new AdminController())->importPlatforms();
        } else if ($get['route'] === 'admin-import-series-platforms') {
            (new AdminController())->importSeriesPlatforms();
        } else if ($get['route'] === 'admin-refresh-ratings') {
            (new AdminController())->refreshRatings();
        } else {
            (new SerieController())->home();
        }
    }
}
