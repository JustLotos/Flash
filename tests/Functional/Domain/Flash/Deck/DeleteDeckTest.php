<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;

class DeleteDeckTest extends AbstractTest
{
    protected $method = 'DELETE';
    protected $uri = '/flash/deck/';

    protected function getFixtures() : array
    {
        return [DeckFixtures::class];
    }

    protected function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $this->uri .= $deck->getId().'/delete/';
    }

    public function testDeleteDeck() : void
    {
        $this->makeRequestWithAuth();
        $this->assertResponseOk($this->response);
        static::assertArrayHasKey('success', $this->content);
    }
}
