<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\Entity;

use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Repeat\Entity\Repeat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity
 * @ORM\Table(name="flash_cards")
 */
class Card
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="flash_card_id")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({Card::GROUP_LIST, Deck::GROUP_ONE})
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="DateTimeImmutable")
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="DateTimeImmutable")
     */
    private $updatedAt;

    /**
     * @var Deck
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Deck\Entity\Deck", inversedBy="cards")
     * @ORM\JoinColumn(name="deck_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $deck;

    /**
     * @var PersistentCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Flash\Record\Entity\Record",
     *     mappedBy="card",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="App\Domain\Flash\Record\Entity\Record")
     */
    private $records;

    /**
     * @var PersistentCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Flash\Repeat\Entity\Repeat",
     *     mappedBy="card",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="App\Domain\Flash\Repeat\Entity\Repeat")
     */
    private $repeats;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="string")
     */
    private $state;

    public const GROUP_LIST = 'GROUP_LIST';
    public const GROUP_ONE = 'GROUP_ONE';

    public function __construct(
        Deck $deck,
        Id $id,
        DateTimeImmutable $date
    ) {
        $this->deck = $deck;
        $this->id = $id;
        $this->createdAt = $date;
        $this->updatedAt = $date;
        $this->state = 'new';
        $this->records = new ArrayCollection();
        $this->repeats = new ArrayCollection();
    }

    public static function createWithRecords(
        Deck $deck,
        Id $id,
        DateTimeImmutable $date,
        array $records
    ): self {
        $card = new self($deck, $id, $date);
        foreach ($records as $record) {
            if ($record instanceof Record) {
                $card->addRecord($record);
                $record->setCard($card);
            }
        }
        return  $card;
    }

    public function updateWithRecords(
        DateTimeImmutable $date,
        array $records
    ): self {
        $this->updatedAt = $date;
        return $this;
    }

    public function getRepeats(): Collection
    {
        return $this->repeats;
    }

    public function addRepeat(Repeat $repeat)
    {
        if (!$this->repeats->contains($repeat)) {
            $this->repeats->add($repeat);
        }

        return $this;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDeck(): Deck
    {
        return  $this->deck;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Record $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
        }

        return $this;
    }

    public function removeRecord(Record $record): self
    {
        if ($this->records->contains($record)) {
            $this->records->removeElement($record);
        }

        return $this;
    }


    public function isNew(): bool
    {
        return (bool)$this->state;
    }
}
