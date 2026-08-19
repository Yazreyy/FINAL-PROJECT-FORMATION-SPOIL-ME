<?php

namespace Tests\Managers;

use Tests\DatabaseTestCase;
use User;
use UserManager;

class UserManagerTest extends DatabaseTestCase
{
    private UserManager $um;

    protected function setUp(): void
    {
        parent::setUp();
        $this->um = new UserManager();
    }

    public function testCreateAndFindByEmail(): void
    {
        $email = $this->uniqueEmail();
        $user = new User('test_pseudo', $email, password_hash('secret', PASSWORD_DEFAULT), 'user', date('Y-m-d H:i:s'));

        $this->um->create($user);
        $this->assertNotNull($user->getId());
        $this->trackForCleanup('user', $user->getId());

        $found = $this->um->findByEmail($email);
        $this->assertNotNull($found);
        $this->assertSame('test_pseudo', $found->getUsername());
    }

    public function testFindByIdReturnsNullWhenMissing(): void
    {
        $this->assertNull($this->um->findById(-1));
    }

    public function testUpdateChangesUsername(): void
    {
        $id = $this->createTestUser();
        $user = $this->um->findById($id);

        $user->setUsername('nouveau_pseudo');
        $this->um->update($user);

        $reloaded = $this->um->findById($id);
        $this->assertSame('nouveau_pseudo', $reloaded->getUsername());
    }

    public function testDeleteRemovesUser(): void
    {
        $id = $this->createTestUser();
        $user = $this->um->findById($id);

        $this->um->delete($user);

        $this->assertNull($this->um->findById($id));
    }

    public function testFindVipOnlyReturnsVipUsers(): void
    {
        $vipId = $this->createTestUser('vip');

        $vips = $this->um->findVip(50);

        $ids = array_map(fn($u) => $u->getId(), $vips);
        $this->assertContains($vipId, $ids);
        foreach ($vips as $vip) {
            $this->assertSame('vip', $vip->getRole());
        }
    }
}
