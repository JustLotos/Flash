<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\UseCase\AddCardWithRecords;

use App\Domain\Flash\Record\Entity\Record;
use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="array")
     */
    public $records;

    public function getRecords(): array {
        $records = [];
        foreach ($this->records as $record) {
            $records[] = new Record($record, new DateTimeImmutable());
        }

        return $records;
    }
}