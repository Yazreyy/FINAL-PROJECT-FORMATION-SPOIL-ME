<?php

namespace Tests\Managers;

use Tests\DatabaseTestCase;
use Watchlist;
use WatchListManager;

class WatchlistManagerTest extends DatabaseTestCase
{
    private WatchListManager $wm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wm = new WatchListManager();
    }

    public function testAddAndFindByUser(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $entry = new Watchlist($seriesId, 'à voir', date('Y-m-d H:i:s'), $userId);
        $this->wm->add($entry);

        $list = $this->wm->findByUser($userId);
        $this->assertCount(1, $list);
        $this->assertSame($seriesId, $list[0]->getSeriesId());

        $this->wm->remove($userId, $seriesId);
    }

    public function testUpdateChangesStatus(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $entry = new Watchlist($seriesId, 'à voir', date('Y-m-d H:i:s'), $userId);
        $this->wm->add($entry);

        $entry->setStatus('terminé');
        $this->wm->update($entry);

        $list = $this->wm->findByUserAndStatut($userId, 'terminé');
        $this->assertCount(1, $list);

        $this->wm->remove($userId, $seriesId);
    }

    public function testCountByUserAndStatus(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $entry = new Watchlist($seriesId, 'terminé', date('Y-m-d H:i:s'), $userId);
        $this->wm->add($entry);

        $this->assertSame(1, $this->wm->countByUserAndStatus($userId, 'terminé'));
        $this->assertSame(0, $this->wm->countByUserAndStatus($userId, 'abandonné'));

        $this->wm->remove($userId, $seriesId);
    }

    public function testRemoveDeletesEntry(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $entry = new Watchlist($seriesId, 'à voir', date('Y-m-d H:i:s'), $userId);
        $this->wm->add($entry);

        $this->wm->remove($userId, $seriesId);

        $this->assertCount(0, $this->wm->findByUser($userId));
    }
}
