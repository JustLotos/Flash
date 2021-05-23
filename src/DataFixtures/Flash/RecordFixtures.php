<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Record\Entity\Record;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\BaseFixture;

class RecordFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const ADMINS_ID = 'ADMIN_'.'RECORD';
    public const USERS_ID = 'USER_'.'RECORD';

    public function getDependencies(): array
    {
        return [ CardFixtures::class ];
    }

    public function loadData(ObjectManager $manager) : void
    {
        $this->createMany(500, self::ADMINS_ID, function () {
            /** @var Card $card */
            $card = $this->getRandomReference(CardFixtures::ADMINS_ID);
            return $this->makeRecord($card);
        });

        $this->createMany(UserFixtures::USER_COUNT * 100, self::USERS_ID, function () {
            /** @var Card $card */
            $card = $this->getRandomReference(CardFixtures::USERS_ID);
            return $this->makeRecord($card);
        });

        $manager->flush();
    }

    public function makeRecord(Card $card): Record {
        return Record::makeByCard(
            $card,
            $this->faker->text(300),
            new DateTimeImmutable()
        );
    }
}
