<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class GetDeckTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/deck/';

    protected function setUp(): void
    {
        parent::setUp();
        /** @var User $user */
        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::USER_EMAIL]);
        /** @var Learner $learner */
        $learner = self::getEntityManager()->getRepository(Learner::class)->findOneBy(['id' => $user->getId()->getValue()]);
        /** @var Deck $deck */
        $deck = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['learner' => $learner]);
        $this->uri .= $deck->getId().'/';
    }

    protected function getFixtures() : array
    {
        return [DeckFixtures::class];
    }

    public function testGetDeck() : void
    {
        $this->makeRequestWithAuth();
        /** @var Response $response */

        static::assertResponseOk($this->response);
        static::assertArrayHasKey('id', $this->content);
        static::assertArrayHasKey('name', $this->content);
        static::assertArrayHasKey('description', $this->content);
    }
}
