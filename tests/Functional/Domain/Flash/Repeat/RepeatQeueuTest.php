<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Repeat;

use App\DataFixtures\Flash\CardFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class RepeatQeueuTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/repeat/queue/';
    /** @var Card */
    protected $card = '';

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
//        $this->uri .= $card->getId().'/discrete/';
        $this->card = $card;
    }

    public function testRepeatCard() : void
    {
        $this->makeRequestWithAuth([
            'cardId' => $this->card->getId()->getValue()
        ]);

        var_dump($this->content);
        self::assertResponseOk($this->response);
    }
}
