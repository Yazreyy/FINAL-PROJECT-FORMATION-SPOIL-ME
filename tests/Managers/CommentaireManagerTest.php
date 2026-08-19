<?php

namespace Tests\Managers;

use Commentaire;
use CommentaireManager;
use Tests\DatabaseTestCase;

class CommentaireManagerTest extends DatabaseTestCase
{
    private CommentaireManager $cm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cm = new CommentaireManager();
    }

    public function testAddAndFindByReview(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $commentaire = new Commentaire('bon commentaire', true, date('Y-m-d H:i:s'), null, $reviewId, $userId);
        $this->cm->add($commentaire);
        $this->assertNotNull($commentaire->getId());
        $this->trackForCleanup('comment', $commentaire->getId());

        $comments = $this->cm->findByReview($reviewId);
        $this->assertCount(1, $comments);
        $this->assertSame('bon commentaire', $comments[0]['content']);
    }

    public function testUpdateChangesContent(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $commentaire = new Commentaire('premier texte', true, date('Y-m-d H:i:s'), null, $reviewId, $userId);
        $this->cm->add($commentaire);
        $this->trackForCleanup('comment', $commentaire->getId());

        $commentaire->setContent('texte modifié');
        $this->cm->update($commentaire);

        $comments = $this->cm->findByReview($reviewId);
        $this->assertSame('texte modifié', $comments[0]['content']);
    }

    public function testFindAllReturnsCommentWithSerieAndUserInfo(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $commentaire = new Commentaire('commentaire admin', true, date('Y-m-d H:i:s'), null, $reviewId, $userId);
        $this->cm->add($commentaire);
        $this->trackForCleanup('comment', $commentaire->getId());

        $all = $this->cm->findAll();
        $match = array_values(array_filter($all, fn($c) => (int)$c['id'] === $commentaire->getId()));

        $this->assertCount(1, $match);
        $this->assertSame($seriesId, (int)$match[0]['series_id']);
        $this->assertArrayHasKey('username', $match[0]);
        $this->assertArrayHasKey('series_title', $match[0]);
    }

    public function testDeleteRemovesCommentaire(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $commentaire = new Commentaire('à supprimer', true, date('Y-m-d H:i:s'), null, $reviewId, $userId);
        $this->cm->add($commentaire);

        $this->cm->delete($commentaire->getId());

        $this->assertCount(0, $this->cm->findByReview($reviewId));
    }
}
