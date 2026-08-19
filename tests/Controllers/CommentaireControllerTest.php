<?php

namespace Tests\Controllers;

use CommentaireController;
use RedirectException;
use Tests\DatabaseTestCase;

class CommentaireControllerTest extends DatabaseTestCase
{
    public function testAddCreatesCommentAndRedirects(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $_SESSION = ['user_id' => $userId, 'csrf_token' => 'test-csrf-token'];
        $_POST = ['content' => 'Commentaire de test', 'review_id' => $reviewId, 'serie_id' => $seriesId, 'csrf_token' => 'test-csrf-token'];

        try {
            (new CommentaireController())->add();
            $this->fail('add() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('serie?id=' . $seriesId, $e->getRoute());
        }

        $stmt = $this->db->prepare('SELECT id, content FROM comment WHERE review_id = :r');
        $stmt->execute(['r' => $reviewId]);
        $row = $stmt->fetch();

        $this->assertNotFalse($row, "add() aurait dû créer une ligne dans comment.");
        $this->assertSame('Commentaire de test', $row['content']);
        $this->trackForCleanup('comment', (int)$row['id']);
    }

    public function testAddWithEmptyContentDoesNotCreateComment(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $_SESSION = ['user_id' => $userId, 'csrf_token' => 'test-csrf-token'];
        $_POST = ['content' => '', 'review_id' => $reviewId, 'serie_id' => $seriesId, 'csrf_token' => 'test-csrf-token'];

        try {
            (new CommentaireController())->add();
            $this->fail('add() aurait dû rediriger via RedirectException.');
        } catch (RedirectException $e) {
            $this->assertSame('serie?id=' . $seriesId, $e->getRoute());
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM comment WHERE review_id = :r');
        $stmt->execute(['r' => $reviewId]);
        $this->assertSame(0, (int)$stmt->fetchColumn());
    }
}
