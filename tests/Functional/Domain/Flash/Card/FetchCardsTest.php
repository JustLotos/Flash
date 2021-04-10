<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Card;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;

class FetchCardsTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/card/';

    public function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $this->uri .= $deck->getId().'/';
    }

    public function getFixtures() : array
    {
        return [CardFixtures::class];
    }

    public function testCGetDeckValid() : void
    {
        $this->makeRequestWithAuth();
        $this->assertResponseOk($this->response);
        $this->assertArrayHasKey('id', $this->content[0]);
    }
}
