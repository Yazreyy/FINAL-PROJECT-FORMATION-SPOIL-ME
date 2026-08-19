<?php

namespace Tests\Managers;

use Note;
use NoteManager;
use Tests\DatabaseTestCase;

class NoteManagerTest extends DatabaseTestCase
{
    private NoteManager $nm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->nm = new NoteManager();
    }

    public function testAddAndFindByUserAndSerie(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $note = new Note(4, date('Y-m-d H:i:s'), null, $userId, $seriesId);
        $this->nm->add($note);
        $this->assertNotNull($note->getId());
        $this->trackForCleanup('rating', $note->getId());

        $found = $this->nm->findByUserAndSerie($userId, $seriesId);
        $this->assertSame(4, $found->getValue());
    }

    public function testUpdateChangesValue(): void
    {
        $userId = $this->createTestUser();
        $seriesId = $this->createTestSeries($userId);

        $note = new Note(2, date('Y-m-d H:i:s'), null, $userId, $seriesId);
        $this->nm->add($note);
        $this->trackForCleanup('rating', $note->getId());

        $note->setValue(5);
        $this->nm->update($note);

        $reloaded = $this->nm->findByUserAndSerie($userId, $seriesId);
        $this->assertSame(5, $reloaded->getValue());
    }

    public function testGetMoyenneAveragesValues(): void
    {
        $userA = $this->createTestUser();
        $userB = $this->createTestUser();
        $seriesId = $this->createTestSeries($userA);

        $noteA = new Note(2, date('Y-m-d H:i:s'), null, $userA, $seriesId);
        $noteB = new Note(4, date('Y-m-d H:i:s'), null, $userB, $seriesId);
        $this->nm->add($noteA);
        $this->nm->add($noteB);
        $this->trackForCleanup('rating', $noteA->getId());
        $this->trackForCleanup('rating', $noteB->getId());

        $this->assertSame(3.0, $this->nm->getMoyenne($seriesId));
    }
}
