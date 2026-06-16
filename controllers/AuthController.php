<?php

class AuthController extends AbstractController {


private UserManager $um;

public function __construct()
{
    $this->um = new UserManager();
}

public function login() : void {
    $this->render('login');
}

public function checkLogin() : void {
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
$this->render('login', ['error' => 'Email ou mot de passe incorrect.']);
}

public function register() : void {
    $this->render('register');
}

public function checkRegister() : void {
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
        $this->render('register', ['error'=>'Cet email est déja utilisé.']);
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
        'series_vues' => $wm->countByUserAndStatus($userId, 'vu')
    ];

    $reviews = $rm->findByUser($userId);
    $watchedSeries = $wm->findByUserAndStatut($userId, 'vu');

    $this->render('profile' , [
        'user'          => $user,
        'stats'         => $stats,
        'reviews'       => $reviews,
        'watchedSeries' => $watchedSeries
    ]);
}

public function updateAvatar() : void {
    $this->requireLogin();

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