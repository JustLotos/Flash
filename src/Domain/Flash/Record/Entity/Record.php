<?php

declare(strict_types=1);

namespace App\Domain\Flash\Record\Entity;

use App\Domain\Flash\Card\Entity\Card;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity
 * @ORM\Table(name="flash_records")
 */
class Record
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Record::GROUP_LIST, Record::GROUP_ONE})
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Record::GROUP_LIST, Record::GROUP_ONE})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Record::GROUP_LIST, Record::GROUP_ONE})
     */
    private $updatedAt;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups({Record::GROUP_LIST, Record::GROUP_ONE})
     */
    private $value;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups({Record::GROUP_LIST, Record::GROUP_ONE})
     */
    private $side;

    /**
     * @var Card
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Card\Entity\Card", inversedBy="records")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Serializer\Groups({Card::GROUP_ONE})
     */
    private $card;

    public const GROUP_LIST = 'RECORD_GROUP_LIST';
    public const GROUP_ONE = 'RECORD_GROUP_ONE';

    public function __construct(
        string $value,
        DateTimeImmutable $date
    ) {
        $this->value = $value;
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public static function makeByCard(
        Card $card,
        string $value,
        DateTimeImmutable $date
    ): Record {
        $record = new self($value, $date);
        $record->setCard($card);
        $record->setFrontSide();
        return $record;
    }

    public static function makeFront(
        Card $card,
        string $value,
        DateTimeImmutable $date
    ): Record {
        $record = self::makeByCard($card, $value, $date);
        $record->setFrontSide();
        return $record;
    }

    public static function makeBack(
        Card $card,
        string $value,
        DateTimeImmutable $date
    ): Record {
        $record = self::makeByCard($card, $value, $date);
        $record->setBackSide();
        return $record;
    }

    private function setBackSide() {
        $this->side = 'backSide';
    }

    private function setFrontSide() {
        $this->side = 'frontSide';
    }

    public function setCard(Card $card) {
        $this->card = $card;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }
    public function getCard(): Card
    {
        return  $this->card;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function update(
        string $value,
        DateTimeImmutable $date
    ) {
        $this->value = $value;
        $this->updatedAt = $date;
    }

    public static function parseFromJson(
        int $id,
        string $value,
        DateTimeImmutable $date
    ): Record {
        $record = new self($value, $date);
        $record->setId($id);
        return $record;
    }
}
