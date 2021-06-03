<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Card;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;

class GetCollectionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/repeat/queue/';

    protected function getFixtures() : array
    {
        return [CardFixtures::class];
    }

    protected function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()
            ->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
    }

    public function testGetCollection() : void
    {
        $this->makeRequestWithAuth();
        var_dump($this->content);
        self::assertResponseOk($this->response);
    }
}
