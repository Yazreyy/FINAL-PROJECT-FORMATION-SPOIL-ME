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
    $this->render('login', ['error' => 'Email ou mot de passe incorrect.']);
    $this->redirect('series');
}
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

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $user = new User($pseudo,$email, $hash,null,'user', date('Y-m-d H:i:s'));
    $this->um->create($user);

    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_role'] = $user->getRole();
    $this->redirect('series');
}
}