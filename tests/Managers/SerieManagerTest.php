<?php

namespace Tests\Managers;

use Serie;
use SerieManager;
use Tests\DatabaseTestCase;

class SerieManagerTest extends DatabaseTestCase
{
    private SerieManager $sm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sm = new SerieManager();
    }

    public function testCreateAndFindOne(): void
    {
        $serie = new Serie(
            'TEST_' . bin2hex(random_bytes(4)),
            'synopsis',
            '2021-05-01',
            'image.jpg',
            0,
            'en cours',
            date('Y-m-d H:i:s')
        );

        $this->sm->create($serie);
        $this->assertNotNull($serie->getId());
        $this->trackForCleanup('series', $serie->getId());

        $found = $this->sm->findOne($serie->getId());
        $this->assertSame($serie->getTitle(), $found->getTitle());
        $this->assertSame('en cours', $found->getStatus());
    }

    public function testFindByTitle(): void
    {
        $id = $this->createTestSeries();
        $title = $this->db->query("SELECT title FROM series WHERE id = $id")->fetchColumn();

        $results = $this->sm->findByTitle(substr($title, 0, 8));

        $ids = array_map(fn($s) => $s->getId(), $results);
        $this->assertContains($id, $ids);
    }

    public function testUpdate(): void
    {
        $id = $this->createTestSeries();
        $serie = $this->sm->findOne($id);

        $serie->setTitle('TEST_updated_title');
        $serie->setStatus('terminée');
        $this->sm->update($serie);

        $reloaded = $this->sm->findOne($id);
        $this->assertSame('TEST_updated_title', $reloaded->getTitle());
        $this->assertTrue($reloaded->isFinish());
    }

    public function testDeleteRemovesSerie(): void
    {
        $id = $this->createTestSeries();

        $this->sm->delete($id);

        $query = $this->db->prepare('SELECT COUNT(*) FROM series WHERE id = :id');
        $query->execute(['id' => $id]);
        $this->assertSame(0, (int)$query->fetchColumn());
    }

    public function testCountAllIncludesCreatedSerie(): void
    {
        $before = $this->sm->countAll();
        $this->createTestSeries();

        $this->assertSame($before + 1, $this->sm->countAll());
    }
}
