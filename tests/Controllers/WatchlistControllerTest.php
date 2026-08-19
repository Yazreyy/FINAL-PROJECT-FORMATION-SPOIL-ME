<?php

namespace Tests\Controllers;

use RedirectException;
use Tests\DatabaseTestCase;
use WatchlistController;

class WatchlistControllerTest extends DatabaseTestCase
{
    public function testAddWatchlistCreatesEntryAndRedirects(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $_SESSION = ['user_id' => $userId, 'csrf_token' => 'test-csrf-token'];
        $_POST = ['id_serie' => $seriesId, 'statut' => 'à voir', 'csrf_token' => 'test-csrf-token'];

        try {
            (new WatchlistController())->addWatchlist();
            $this->fail('addWatchlist() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('serie?id=' . $seriesId, $e->getRoute());
        }

        $stmt = $this->db->prepare('SELECT status FROM watchlist WHERE user_id = :u AND series_id = :s');
        $stmt->execute(['u' => $userId, 's' => $seriesId]);
        $row = $stmt->fetch();

        $this->assertNotFalse($row, "addWatchlist() aurait dû créer une ligne dans watchlist.");
        $this->assertSame('à voir', $row['status']);
        $this->db->prepare('DELETE FROM watchlist WHERE user_id = :u AND series_id = :s')
            ->execute(['u' => $userId, 's' => $seriesId]);
    }

    public function testAddWatchlistRejectsRequestWithInvalidCsrfToken(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $_SESSION = ['user_id' => $userId, 'csrf_token' => 'session-token'];
        $_POST = ['id_serie' => $seriesId, 'statut' => 'à voir', 'csrf_token' => 'token-different-envoyé-par-un-attaquant'];

        try {
            (new WatchlistController())->addWatchlist();
            $this->fail('addWatchlist() aurait dû rediriger via RedirectException (CSRF invalide).');
        } catch (RedirectException $e) {
            $this->assertSame('', $e->getRoute());
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM watchlist WHERE user_id = :u AND series_id = :s');
        $stmt->execute(['u' => $userId, 's' => $seriesId]);
        $this->assertSame(0, (int)$stmt->fetchColumn(),
            "Une requête avec un token CSRF invalide n'aurait pas dû créer de ligne dans watchlist.");
    }
}
