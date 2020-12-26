<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class DeckFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const GROUP_ADMINS = 'ADMINS';
    public const DECK_ADMINS_COUNT = 100;

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(self::DECK_ADMINS_COUNT, self::GROUP_ADMINS, function () {
            $name = $this->faker->name;
            $description = $this->faker->paragraph;
            $date = DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', 'now'));
            /** @var Learner $learner */
            $learner = $this->getRandomReference(UserFixtures::ADMINS);

            return new Deck($learner, $name, $date, $description);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ LearnerFixtures::class ];
    }
}
