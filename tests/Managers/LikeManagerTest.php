<?php

namespace Tests\Managers;

use LikeManager;
use Tests\DatabaseTestCase;

class LikeManagerTest extends DatabaseTestCase
{
    private LikeManager $lm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lm = new LikeManager();
    }

    public function testAddThenExists(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $this->assertFalse($this->lm->exists($userId, $reviewId));

        $this->lm->add($userId, $reviewId);

        $this->assertTrue($this->lm->exists($userId, $reviewId));

        $this->db->prepare('DELETE FROM user_like WHERE user_id = :user_id AND review_id = :review_id')
            ->execute(['user_id' => $userId, 'review_id' => $reviewId]);
    }

    public function testRemoveDeletesLike(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);
        $reviewId = $this->createTestReview($seriesId, $userId);

        $this->lm->add($userId, $reviewId);
        $this->lm->remove($userId, $reviewId);

        $this->assertFalse($this->lm->exists($userId, $reviewId));
    }
}
