<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\Entity\Types\Name;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class LearnerFixtures extends BaseFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    public const ADMINS = 'ADMINS';
    public const USERS = 'USERS';

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMINS, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::ADMINS);
            $name = new Name('Roman', 'Ignashov');
            $id = new Id($user->getId()->getValue());
            return Learner::create($id, $name);

        });

        $this->createMany(1, self::USERS, function () {
            /** @var User $user */
            $user = $this->getRandomReference(UserFixtures::USERS);
            $name = new Name($this->faker->firstName, $this->faker->lastName);
            $id = new Id($user->getId()->getValue());
            return Learner::create($id, $name);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ UserFixtures::class ];
    }
}
