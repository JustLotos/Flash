<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\Entity\Types\Name;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class DeckFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const ADMINS_ID = 'ADMIN_'.'DECK';
    public const USERS_ID = 'USER_'.'DECK';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(100, self::ADMINS_ID, function () {
            $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
            /** @var Learner $learner */
            $learner = $this->getReferenceByNumber(LearnerFixtures::ADMINS, 0);
            return new Deck(
                $learner,
                $this->faker->title,
                DateTimeImmutable::createFromMutable($date),
                $this->faker->sentence
            );
        });

        $this->createMany(UserFixtures::USER_COUNT * 10, self::USERS_ID, function () {
            $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
            /** @var Learner $learner */
            $learner = $this->getRandomReference(LearnerFixtures::USERS);
            return new Deck(
                $learner,
                $this->faker->title,
                DateTimeImmutable::createFromMutable($date),
                $this->faker->sentence
            );
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ LearnerFixtures::class ];
    }
}
