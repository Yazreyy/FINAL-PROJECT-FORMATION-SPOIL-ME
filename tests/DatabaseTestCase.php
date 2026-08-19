<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $db;

    /** @var array<int, array{0: string, 1: int}> */
    private array $cleanup = [];

    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
        $_GET = [];

        $env = parse_ini_file(__DIR__ . '/../.env.testing');
        $this->db = new PDO(
            "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
            $env['DB_USER'],
            $env['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanup) as [$table, $id]) {
            $this->db->prepare("DELETE FROM `$table` WHERE id = :id")->execute(['id' => $id]);
        }
        $this->cleanup = [];
    }

    protected function trackForCleanup(string $table, int $id): void
    {
        $this->cleanup[] = [$table, $id];
    }

    protected function uniqueEmail(string $prefix = 'test'): string
    {
        return $prefix . '_' . bin2hex(random_bytes(4)) . '@example.test';
    }

    protected function createTestUser(string $role = 'user'): int
    {
        $this->db->prepare('INSERT INTO user (username, email, password, role, created_at)
            VALUES (:username, :email, :password, :role, NOW())')
            ->execute([
                'username' => 'test_' . bin2hex(random_bytes(4)),
                'email'    => $this->uniqueEmail(),
                'password' => password_hash('secret', PASSWORD_DEFAULT),
                'role'     => $role,
            ]);
        $id = (int)$this->db->lastInsertId();
        $this->trackForCleanup('user', $id);
        return $id;
    }

    protected function createTestSeries(?int $createdBy = null): int
    {
        $this->db->prepare('INSERT INTO series (title, synopsis, release_date, image, average_rating, status, created_at, created_by)
            VALUES (:title, :synopsis, :release_date, :image, 0, :status, NOW(), :created_by)')
            ->execute([
                'title'        => 'TEST_' . bin2hex(random_bytes(4)),
                'synopsis'     => 'synopsis de test',
                'release_date' => '2020-01-01',
                'image'        => 'test.jpg',
                'status'       => 'en cours',
                'created_by'   => $createdBy,
            ]);
        $id = (int)$this->db->lastInsertId();
        $this->trackForCleanup('series', $id);
        return $id;
    }

    protected function createTestReview(int $seriesId, int $userId): int
    {
        $this->db->prepare('INSERT INTO review (content, is_official, created_at, series_id, user_id)
            VALUES (:content, 0, NOW(), :series_id, :user_id)')
            ->execute([
                'content'   => 'contenu de test',
                'series_id' => $seriesId,
                'user_id'   => $userId,
            ]);
        $id = (int)$this->db->lastInsertId();
        $this->trackForCleanup('review', $id);
        return $id;
    }

    /**
     * Trouve (ou crée) un user identifié par un email fixe.
     * Utilisé pour les tests de contrôleurs qui redirigent (donc exit()) :
     * comme #[RunInSeparateProcess] lance un process enfant, on ne peut pas
     * se passer un ID généré aléatoirement entre deux méthodes de test. Un
     * identifiant fixe permet à un test suivant de retrouver la même ligne.
     */
    protected function findOrCreateFixtureUser(string $email, string $role = 'user'): int
    {
        $stmt = $this->db->prepare('SELECT id FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $id = $stmt->fetchColumn();
        if ($id) {
            return (int)$id;
        }

        $this->db->prepare('INSERT INTO user (username, email, password, role, created_at)
            VALUES (:username, :email, :password, :role, NOW())')
            ->execute([
                'username' => 'fixture_' . substr(md5($email), 0, 8),
                'email'    => $email,
                'password' => password_hash('secret', PASSWORD_DEFAULT),
                'role'     => $role,
            ]);
        return (int)$this->db->lastInsertId();
    }

    protected function findOrCreateFixtureSeries(string $title, ?int $createdBy = null): int
    {
        $stmt = $this->db->prepare('SELECT id FROM series WHERE title = :title');
        $stmt->execute(['title' => $title]);
        $id = $stmt->fetchColumn();
        if ($id) {
            return (int)$id;
        }

        $this->db->prepare('INSERT INTO series (title, synopsis, release_date, image, average_rating, status, created_at, created_by)
            VALUES (:title, "synopsis fixture", "2020-01-01", "test.jpg", 0, "en cours", NOW(), :created_by)')
            ->execute(['title' => $title, 'created_by' => $createdBy]);
        return (int)$this->db->lastInsertId();
    }

    /** Supprime intégralement un couple user/series fixture et tout ce qui en dépend. */
    protected static function purgeFixture(string $email, string $seriesTitle): void
    {
        $env = parse_ini_file(__DIR__ . '/../.env.testing');
        $db = new PDO(
            "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
            $env['DB_USER'],
            $env['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $db->prepare('SELECT id FROM series WHERE title = :title');
        $stmt->execute(['title' => $seriesTitle]);
        $seriesId = $stmt->fetchColumn();

        if ($seriesId) {
            $db->prepare('DELETE FROM comment WHERE review_id IN (SELECT id FROM review WHERE series_id = :id)')->execute(['id' => $seriesId]);
            $db->prepare('DELETE FROM user_like WHERE review_id IN (SELECT id FROM review WHERE series_id = :id)')->execute(['id' => $seriesId]);
            $db->prepare('DELETE FROM review WHERE series_id = :id')->execute(['id' => $seriesId]);
            $db->prepare('DELETE FROM rating WHERE series_id = :id')->execute(['id' => $seriesId]);
            $db->prepare('DELETE FROM watchlist WHERE series_id = :id')->execute(['id' => $seriesId]);
            $db->prepare('DELETE FROM series WHERE id = :id')->execute(['id' => $seriesId]);
        }

        $stmt = $db->prepare('SELECT id FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $userId = $stmt->fetchColumn();
        if ($userId) {
            $db->prepare('DELETE FROM user WHERE id = :id')->execute(['id' => $userId]);
        }
    }
}
