<?php
declare(strict_types=1);

namespace App\Tests\Builders;

use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Learner\Entity\Learner;
use App\Domain\Flash\Learner\Entity\Types\Id;
use DateTimeImmutable;

class DeckBuilder
{
    public static function make(string $name = 'Components', string $description = ''): Deck {
        $learner = Learner::create(Id::next());
        return new Deck($learner, $name, new DateTimeImmutable(), $description);
    }
}