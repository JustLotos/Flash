<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Card;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;

class GetCardTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/card/';

    public function getFixtures() : array
    {
        return [ CardFixtures::class ];
    }

    public function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $card = self::getEntityManager()->getRepository(Card::class)->findOneBy(['deck' => $deck]);
        $this->uri .= $card->getId().'/';
    }

    public function testGetDeckValid() : void
    {
        $this->makeRequestWithAuth();
        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
    }

    public function testGetCardRepeatInfoValid() : void
    {
        $this->uri .= 'repeatInfo/';
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('repeatCount', $this->content);
        self::assertArrayHasKey('averageScore1', $this->content);
    }
}
