<?php

namespace Tests\Managers;

use Review;
use ReviewManager;
use Tests\DatabaseTestCase;

class ReviewManagerTest extends DatabaseTestCase
{
    private ReviewManager $rm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rm = new ReviewManager();
    }

    public function testAddAndFindOne(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $review = new Review('super série', true, date('Y-m-d H:i:s'), null, $seriesId, $userId);
        $this->rm->add($review);
        $this->assertNotNull($review->getId());
        $this->trackForCleanup('review', $review->getId());

        $found = $this->rm->findOne($review->getId());
        $this->assertSame('super série', $found->getContent());
        $this->assertTrue($found->isOfficial());
    }

    public function testUpdateChangesContent(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $review = $this->rm->findOne($reviewId);
        $review->setContent('contenu modifié');
        $this->rm->update($review);

        $reloaded = $this->rm->findOne($reviewId);
        $this->assertSame('contenu modifié', $reloaded->getContent());
    }

    public function testDeleteRemovesReview(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $this->rm->delete($reviewId);

        $this->assertNull($this->rm->findOne($reviewId));
    }

    public function testCountByUser(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $this->createTestReview($seriesId, $userId);
        $this->createTestReview($seriesId, $userId);

        $this->assertSame(2, $this->rm->countByUser($userId));
    }
}
