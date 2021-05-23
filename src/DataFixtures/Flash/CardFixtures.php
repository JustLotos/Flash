<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\DiscreteAnswer;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\BaseFixture;

class CardFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const ADMINS_ID = 'ADMIN_'.'CARD';
    public const USERS_ID = 'USER_'.'CARD';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(500, self::ADMINS_ID, function () {
            /** @var Deck $deck */
            $deck = $this->getRandomReference(DeckFixtures::ADMINS_ID);
            /** @var Card $card */
            $card = $this->addRepeats($this->makeCard($deck));
            $card->setInterval($deck->getSettings()->getBaseInterval());
            return $card;
        });

//        $this->createMany(UserFixtures::USER_COUNT * 100, self::USERS_ID, function () {
//            /** @var Deck $deck */
//            $deck = $this->getRandomReference(DeckFixtures::USERS_ID);
//            return $this->makeCard($deck);
//        });

        $manager->flush();
    }

    public function addRepeats(Card $card): Card {
        for($i = 0; $i <10; $i++) {
            $answer = new DiscreteAnswer(
                new DateTimeImmutable(),
                60,
                DiscreteAnswer::KNOW
            );
            $repeat = new Repeat($card, new DateTimeImmutable(), $answer->getEstimateAnswer(), 60 );
            $card->addRepeat($repeat);
        }
        return $card;
    }

    public function makeCard(Deck $deck): Card {
        return new Card(
            $deck,
            Id::next(),
            new DateTimeImmutable()
        );
    }

    public function getDependencies(): array
    {
        return [ DeckFixtures::class ];
    }
}
