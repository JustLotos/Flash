<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Deck\Entity\Types\Settings;
use App\Domain\Flash\Learner\Entity\Learner;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\BaseFixture;

class DeckFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const ADMINS_ID = 'ADMIN_'.'DECK';
    public const USERS_ID = 'USER_'.'DECK';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(10, self::ADMINS_ID, function () {
            /** @var Learner $learner */
            $learner = $this->getReferenceByNumber(LearnerFixtures::ADMINS, 0);
            return $this->makeDeck($learner);
        });

        $this->createMany(UserFixtures::USER_COUNT * 10, self::USERS_ID, function () {
            /** @var Learner $learner */
            $learner = $this->getRandomReference(LearnerFixtures::USERS);
            return $this->makeDeck($learner);
        });

        $manager->flush();
    }

    public function makeDeck(Learner $learner): Deck {
        $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);

        $deck = new Deck(
            $learner,
            $this->faker->company,
            DateTimeImmutable::createFromMutable($date),
            new Settings(),
            $this->faker->sentence
        );

        if(rand(0,1) < 0.5) { $deck->publish(); }

        $learner->addDeck($deck);
        return $deck;
    }


    public function getDependencies(): array
    {
        return [ LearnerFixtures::class ];
    }
}
