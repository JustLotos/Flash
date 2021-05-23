<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Flash\Deck\Entity;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Tests\Builders\DeckBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\Flash\Learner\Entity\Learner;

class UpdateTest extends TestCase
{
    public function testWithReqFields(): void
    {
        $deck = DeckBuilder::make();

        $nameUpdated = 'DeckUpdated';
        $dateUpdated = new DateTimeImmutable();
        $deck->update($nameUpdated, $dateUpdated);

        self::assertEquals('', $deck->getDescription());
        self::assertEquals($nameUpdated, $deck->getName());
        self::assertEquals($dateUpdated->format(DATE_ATOM), $deck->getUpdatedAt()->format(DATE_ATOM));
    }

    public function testWithNonReqFields(): void
    {
        $deck = DeckBuilder::make();

        $nameUpdated = 'DeckUpdated';
        $dateUpdated = new DateTimeImmutable();
        $descriptionUpdated = 'DeckUpdated';
        $deck->update($nameUpdated, $dateUpdated, $descriptionUpdated);

        self::assertEquals($descriptionUpdated, $deck->getDescription());
        self::assertEquals($nameUpdated, $deck->getName());
        self::assertEquals($dateUpdated->format(DATE_ATOM), $deck->getUpdatedAt()->format(DATE_ATOM));
    }
}
