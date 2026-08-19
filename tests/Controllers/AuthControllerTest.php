<?php

namespace Tests\Controllers;

use AuthController;
use RedirectException;
use Tests\DatabaseTestCase;

class AuthControllerTest extends DatabaseTestCase
{
    public function testCheckLoginWithWrongPasswordRendersError(): void
    {
        $userId = $this->createTestUser();
        $email = $this->db->query("SELECT email FROM user WHERE id = $userId")->fetchColumn();

        $_POST = ['email' => $email, 'password' => 'mauvais_mot_de_passe'];
        $_SESSION = ['csrf_token' => 'test-csrf-token'];
        $_POST['csrf_token'] = 'test-csrf-token';

        ob_start();
        (new AuthController())->checkLogin();
        $output = ob_get_clean();

        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertStringContainsString('Email ou mot de passe incorrect', $output);
    }

    public function testCheckLoginWithUnknownEmailRendersError(): void
    {
        $_POST = ['email' => 'inconnu_' . bin2hex(random_bytes(4)) . '@example.test', 'password' => 'peu importe'];
        $_SESSION = ['csrf_token' => 'test-csrf-token'];
        $_POST['csrf_token'] = 'test-csrf-token';

        ob_start();
        (new AuthController())->checkLogin();
        $output = ob_get_clean();

        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertStringContainsString('Email ou mot de passe incorrect', $output);
    }

    public function testCheckLoginWithCorrectCredentialsSetsSessionAndRedirects(): void
    {
        $userId = $this->createTestUser();
        $email = $this->db->query("SELECT email FROM user WHERE id = $userId")->fetchColumn();

        $_POST = ['email' => $email, 'password' => 'secret'];
        $_SESSION = ['csrf_token' => 'test-csrf-token'];
        $_POST['csrf_token'] = 'test-csrf-token';

        try {
            (new AuthController())->checkLogin();
            $this->fail('checkLogin() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('series', $e->getRoute());
        }

        $this->assertSame($userId, $_SESSION['user_id']);
    }

    public function testCheckRegisterWithExistingEmailRendersError(): void
    {
        $userId = $this->createTestUser();
        $email = $this->db->query("SELECT email FROM user WHERE id = $userId")->fetchColumn();

        $_POST = ['pseudo' => 'peu_importe', 'email' => $email, 'password' => 'secret123'];
        $_SESSION = ['csrf_token' => 'test-csrf-token'];
        $_POST['csrf_token'] = 'test-csrf-token';

        ob_start();
        (new AuthController())->checkRegister();
        $output = ob_get_clean();

        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertStringContainsString('déja utilisé', $output);
    }

    public function testCheckRegisterWithNewEmailCreatesUserAndRedirects(): void
    {
        $email = $this->uniqueEmail();
        $_POST = ['pseudo' => 'nouveau_membre', 'email' => $email, 'password' => 'secret123'];
        $_SESSION = ['csrf_token' => 'test-csrf-token'];
        $_POST['csrf_token'] = 'test-csrf-token';

        try {
            (new AuthController())->checkRegister();
            $this->fail('checkRegister() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('series', $e->getRoute());
        }

        $this->assertArrayHasKey('user_id', $_SESSION);
        $this->trackForCleanup('user', $_SESSION['user_id']);

        $stmt = $this->db->prepare('SELECT username, role FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $this->assertNotFalse($user);
        $this->assertSame('nouveau_membre', $user['username']);
        $this->assertSame('user', $user['role']);
    }
}
