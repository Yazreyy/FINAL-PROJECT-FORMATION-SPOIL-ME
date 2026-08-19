<?php

class AuthController extends AbstractController {


private UserManager $um;

public function __construct()
{
    $this->um = new UserManager();
}

public function login() : void {
    $this->render('login', [
        'title' => 'Connexion - Spoil Me',
        'description' => 'Connectez-vous à votre compte Spoil Me.',
    ]);
}

public function checkLogin() : void {
    $this->verifyCsrf();
    if(isset($_POST['email'])) {
        $email = $_POST['email']; 
} else {$email = '';}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
	} else {
    $password = '';
}
$user = $this->um->findByEmail($email);

if($user && password_verify($password, $user->getPassword())) {
    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_role'] = $user->getRole();
    $_SESSION['user_avatar'] = $user->getAvatarUrl();
    $this->redirect('series');
}
$this->render('login', ['title' => 'Connexion - Spoil Me', 'error' => 'Email ou mot de passe incorrect.']);
}

public function register() : void {
    $this->render('register', [
        'title' => 'Inscription - Spoil Me',
        'description' => 'Créez votre compte Spoil Me pour noter et commenter vos séries préférées.',
    ]);
}

public function checkRegister() : void {
    $this->verifyCsrf();
    if(isset($_POST['pseudo'])) {
        $pseudo = $_POST['pseudo'];
    	} else {
        $pseudo = '';
    }
    if(isset($_POST['email'])) {
        $email = $_POST['email'];
    	} else {
        $email = '';
    }
    if(isset($_POST['password'])) {
        $password = $_POST['password'];
    	} else {
        $password = '';
    }

    if($this->um->findByEmail($email)) {
        $this->render('register', ['title' => 'Inscription - Spoil Me', 'error'=>'Cet email est déja utilisé.']);
        return;
    }

    $defaultAvatars = [
        'assets/img/avatars/default1.svg',
        'assets/img/avatars/default2.svg',
        'assets/img/avatars/default3.svg'
    ];
    $randomAvatar = $defaultAvatars[array_rand($defaultAvatars)];

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $user = new User($pseudo,$email, $hash,'user', date('Y-m-d H:i:s'), $randomAvatar);
    $this->um->create($user);

    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_role'] = $user->getRole();
    $_SESSION['user_avatar'] = $user->getAvatarUrl();
    $this->redirect('series');
}

public function logout() : void {
    session_destroy();
    $this->redirect('login');
}

public function profile($id = null) : void {
    $this->requireLogin();

    $userId = isset($id) ? $id : $_SESSION['user_id'];
    $user = $this->um->findById($userId);
    $rm = new ReviewManager();
    $wm = new WatchListManager();

    $stats = [
        'reviews'     => $rm->countByUser($userId),
        'series_vues' => $wm->countByUserAndStatus($userId, 'terminé')
    ];

    $reviews = $rm->findByUser($userId);
    $watchedSeries = $wm->findByUserAndStatut($userId, 'terminé');

    $this->render('profile' , [
        'user'          => $user,
        'stats'         => $stats,
        'reviews'       => $reviews,
        'watchedSeries' => $watchedSeries,
        'isOwnProfile'  => (int)$userId === (int)$_SESSION['user_id'],
        'error'         => null
    ]);
}

public function updateProfile() : void {
    $this->requireLogin();
    $this->verifyCsrf();

    $user = $this->um->findById($_SESSION['user_id']);

    $username = trim($_POST['pseudo'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $existing = $this->um->findByEmail($email);
    if ($existing && $existing->getId() !== $user->getId()) {
        $rm = new ReviewManager();
        $wm = new WatchListManager();
        $this->render('profile', [
            'user'          => $user,
            'stats'         => [
                'reviews'     => $rm->countByUser($user->getId()),
                'series_vues' => $wm->countByUserAndStatus($user->getId(), 'terminé')
            ],
            'reviews'       => $rm->findByUser($user->getId()),
            'watchedSeries' => $wm->findByUserAndStatut($user->getId(), 'terminé'),
            'isOwnProfile'  => true,
            'error'         => 'Cet email est déjà utilisé.'
        ]);
        return;
    }

    $user->setUsername($username);
    $user->setEmail($email);
    if ($password !== '') {
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
    }
    $this->um->update($user);

    $this->redirect('profile');
}

public function deleteAccount() : void {
    $this->requireLogin();
    $this->verifyCsrf();

    $user = $this->um->findById($_SESSION['user_id']);
    if ($user) {
        $this->um->delete($user);
    }
    session_destroy();
    $this->redirect('login');
}

public function updateAvatar() : void {
    $this->requireLogin();
    $this->verifyCsrf();

    if (!empty($_FILES['avatar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $uploadDir = __DIR__ . '/../uploads/avatar/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename);

            $user = $this->um->findById($_SESSION['user_id']);
            $user->setAvatar('uploads/avatar/' . $filename);
            $this->um->update($user);

            $_SESSION['user_avatar'] = $user->getAvatarUrl();
        }
    }

    $this->redirect('profile');
}
}