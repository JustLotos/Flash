<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Flash\Deck\Entity;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Types\Id;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\Flash\Learner\Entity\Learner;

class CreateTest extends TestCase
{
    public function testWithReqFields(): void
    {
        $learner = Learner::create(Id::next());
        $name = 'Components';
        $date = new DateTimeImmutable();
        $deck = new Deck($learner, $name, $date);

        self::assertEquals($learner->getId(), $deck->getLearner()->getId());
        self::assertEquals('', $deck->getDescription());
        self::assertEquals($name, $deck->getName());
    }

    public function testWithNonReqFields(): void
    {
        $learner = Learner::create(Id::next());
        $name = 'Components';
        $date = new DateTimeImmutable();
        $description = 'Description';
        $deck = new Deck($learner, $name, $date, $description);

        self::assertEquals($learner->getId(), $deck->getLearner()->getId());
        self::assertEquals($description, $deck->getDescription());
        self::assertEquals($name, $deck->getName());
    }
}
