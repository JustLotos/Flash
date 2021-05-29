<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;

class UpdateDeckTest extends AbstractTest
{
    protected $method = 'PUT';
    protected $uri = '/flash/deck/';

    public function getFixtures() : array
    {
        return [DeckFixtures::class];
    }

    public function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $this->uri .= $deck->getId().'/update/';
    }

    public function testUpdateDeck() : void
    {
        $this->makeRequestWithAuth([
            'name' => 'deckName',
            'description' => 'description'
        ]);

        $this->assertResponseOk($this->response);
        $this->assertArrayHasKey('id', $this->content);
        $this->assertArrayHasKey('name', $this->content);
        $this->assertArrayHasKey('description', $this->content);
        $this->assertArrayHasKey('createdAt', $this->content);
        $this->assertArrayHasKey('updatedAt', $this->content);
        $this->assertEquals('deckName', $this->content['name']);
        $this->assertEquals('description', $this->content['description']);
    }
}
