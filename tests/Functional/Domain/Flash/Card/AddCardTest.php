<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Card;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class AddCardTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/flash/card/';

    protected function getFixtures() : array
    {
        return [CardFixtures::class];
    }

    protected function setUp() : void
    {
        parent::setUp();
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $this->uri .= $deck->getId().'/add/';
    }

    public function testAddCard() : void
    {
        $this->makeRequestWithAuth();
        static::assertResponseCode(Response::HTTP_CREATED, $this->response);
        static::assertArrayHasKey('id', $this->content);
    }
}
