<?php

namespace Tests\Controllers;

use LikeController;
use RedirectException;
use Tests\DatabaseTestCase;

class LikeControllerTest extends DatabaseTestCase
{
    public function testToggleAddsThenRemovesLike(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $_SESSION = ['user_id' => $userId, 'csrf_token' => 'test-csrf-token'];
        $_POST = ['review_id' => $reviewId, 'serie_id' => $seriesId, 'csrf_token' => 'test-csrf-token'];

        // Premier appel : aucun like n'existe encore, toggle() doit en créer un.
        try {
            (new LikeController())->toggle();
            $this->fail('toggle() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('serie?id=' . $seriesId, $e->getRoute());
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM user_like WHERE user_id = :u AND review_id = :r');
        $stmt->execute(['u' => $userId, 'r' => $reviewId]);
        $this->assertSame(1, (int)$stmt->fetchColumn(), "toggle() aurait dû créer un like.");

        // Deuxième appel : le like existe déjà, toggle() doit le supprimer.
        try {
            (new LikeController())->toggle();
            $this->fail('toggle() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('serie?id=' . $seriesId, $e->getRoute());
        }

        $stmt->execute(['u' => $userId, 'r' => $reviewId]);
        $this->assertSame(0, (int)$stmt->fetchColumn(), "le deuxième toggle() aurait dû supprimer le like.");
    }
}
