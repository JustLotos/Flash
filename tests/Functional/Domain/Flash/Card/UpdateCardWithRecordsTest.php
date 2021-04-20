<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Card;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class UpdateCardWithRecordsTest extends AbstractTest
{
    protected $method = 'PUT';
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
        $card = self::getEntityManager()->getRepository(Card::class)->findOneBy(['deck' => $deck]);
        $this->uri .= $card->getId().'/update/records/';
    }

    public function testAddCard() : void
    {
        $this->makeRequestWithAuth(["records" => ["value1", "value2"]]);

        var_dump($this->content);
        static::assertResponseCode(Response::HTTP_CREATED, $this->response);
        static::assertArrayHasKey('id', $this->content);
        static::assertArrayHasKey('records', $this->content);
        static::assertEquals('value1', $this->content['records'][0]['value']);
        static::assertEquals('value2', $this->content['records'][1]['value']);
    }
}
