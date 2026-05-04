<?php

require_once 'models/Commentaire.php';
require_once 'models/Like.php';
require_once 'models/Note.php';
require_once 'models/Review.php';
require_once 'models/Serie.php';
require_once 'models/User.php';
require_once 'models/Watchlist.php';


require_once 'managers/AbstractManager.php';
require_once 'managers/CommentaireManager.php';
require_once 'managers/LikeManager.php';
require_once 'managers/NoteManager.php';
require_once 'managers/ReviewManager.php';
require_once 'managers/SerieManager.php';
require_once 'managers/UserManager.php';
require_once 'managers/WatchlistManager.php';


require_once 'controllers/AdminController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ReviewController.php';
require_once 'controllers/SerieController.php';
require_once 'controllers/WatchlistController.php';

require_once 'services/Router.php';