<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\Types\Status;
use App\Domain\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use App\DataFixtures\BaseFixture;

class UserFixtures extends BaseFixture implements ContainerAwareInterface
{
    public const ADMINS = 'ADMINS';
    public const USERS = 'USERS';
    public const USER_COUNT = 10;

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(1, self::ADMINS, function () {
            $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
            $user = User::createByEmail(
                Id::next(),
                DateTimeImmutable::createFromMutable($date),
                Role::createAdmin(),
                new Email(getenv('TEST_USER_EMAIL')),
                new Password(getenv('TEST_USER_PASSWORD')),
                Status::createWait()
            );

            $user->getStatus()->activate();
            return $user;
        });

        $this->createMany(self::USER_COUNT, self::USERS, function (int  $i) {
            $date = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now', $timezone = null);
            $user = User::createByEmail(
                Id::next(),
                DateTimeImmutable::createFromMutable($date),
                Role::createUser(),
                new Email("test$i@test.test"),
                new Password('12345678Ab'),
                Status::createWait()
            );


            if($i !== 5) {
                $user->getStatus()->activate();
            }
            return $user;
        });

        $manager->flush();
    }
}
