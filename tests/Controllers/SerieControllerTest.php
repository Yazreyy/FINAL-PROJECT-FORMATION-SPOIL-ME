<?php

namespace Tests\Controllers;

use SerieController;
use Tests\DatabaseTestCase;

class SerieControllerTest extends DatabaseTestCase
{
    public function testHomeRendersWithoutError(): void
    {
        ob_start();
        (new SerieController())->home();
        $output = ob_get_clean();

        $this->assertStringContainsString('Spoil Me', $output);
    }

    public function testIndexFiltersByTitle(): void
    {
        $seriesId = $this->createTestSeries();
        $title = $this->db->query("SELECT title FROM series WHERE id = $seriesId")->fetchColumn();

        $_GET = ['titre' => $title];

        ob_start();
        (new SerieController())->index();
        $output = ob_get_clean();

        $this->assertStringContainsString($title, $output);
    }
}
