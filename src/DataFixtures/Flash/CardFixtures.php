<?php

declare(strict_types=1);

namespace App\DataFixtures\Flash;

use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Repeat\UseCase\DiscreteRepeat\DiscreteAnswer;
use App\Domain\Flash\Service\AnswerMangerService\AnswerManagerService;
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
        $managerService = new AnswerManagerService();

        $this->createMany(50, self::ADMINS_ID, function () use ($managerService) {
            /** @var Deck $deck */
            $deck = $this->getRandomReference(DeckFixtures::ADMINS_ID);
            $card = $this->makeCard($deck);
            $card = $this->addRepeats($card, $managerService);
            return $this->addRecords($card);
        });


        //
//        $this->createMany(UserFixtures::USER_COUNT * 100, self::USERS_ID, function () {
//            /** @var Deck $deck */
//            $deck = $this->getRandomReference(DeckFixtures::USERS_ID);
//            return $this->makeCard($deck);
//        });

        $manager->flush();
    }

    public function addRepeats(Card $card, AnswerManagerService $managerService): Card
    {
        /** @var  $answers */
        $answers = DiscreteAnswer::getStates();

        for ($i = 0; $i <10; $i++) {
            /** @var Repeat $lastRepeat */
            $lastRepeat = $card->getRepeats()->last();
            $timeRepeat = $this->faker->numberBetween(5, 120);
            $index = $this->faker->numberBetween(0, 10) % count($answers);

            if($lastRepeat) {
                $totalTime = $timeRepeat
                    + $lastRepeat->getUpdatedAt()->getTimestamp()
                    + $card->getCurrentRepeatInterval();
                $dateAnswer = (new \DateTimeImmutable())->setTimestamp($totalTime);
            } else {
                $dateAnswer = DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('- 2 month', '- 1 month')
                );
            }

            $answer = new DiscreteAnswer($dateAnswer, $timeRepeat, $answers[$index]);

            $repeat = new Repeat(
                $card,
                $answer->getDate(),
                $answer->getEstimateAnswer(),
                $answer->getTime(),
                $card->getCurrentRepeatInterval()
            );
            $card->addRepeat($repeat);

            $newInterval = $managerService->getNewInterval($card, $answer);
            $card->setCurrentRepeatInterval($newInterval);

            if(!$card->isReadyForLearn()) {
                break;
            }
        }

        return $card;
    }

    public function makeCard(Deck $deck): Card
    {
        return new Card(
            $deck,
            new DateTimeImmutable(),
            $this->faker->name
        );
    }

    public function addRecords(Card $card): Card
    {
        $firstBlock['blocks'][]= [
            'type' => 'paragraph',
            'data' => ['text' => $this->faker->text(300)]
        ];
        $front = Record::makeFront($card, $firstBlock, new DateTimeImmutable());

        $secondBlock['blocks'][]= [
            'type' => 'paragraph',
            'data' => ['text' => $this->faker->text(300)]
        ];
        $back = Record::makeBack($card, $secondBlock, new DateTimeImmutable());

        $card->addRecord($front);
        $card->addRecord($back);

        return $card;
    }

    public function getDependencies(): array
    {
        return [ DeckFixtures::class ];
    }
}
