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
     * @Serializer\Groups({Card::GROUP_LIST, Card::GROUP_ONE, Deck::GROUP_ONE})
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
     * @Serializer\Groups({Card::GROUP_LIST})
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

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Serializer\Groups({Card::GROUP_LIST, Card::GROUP_ONE})
     * @Serializer\Type(name="string")
     */
    private $label;

    /** @var int
     *  @ORM\Column(type="integer", nullable=true)
     *  @Serializer\Groups({Card::GROUP_ONE})
     * @Serializer\Type(name="integer")
     */
    private $currentRepeatInterval;

    public const GROUP_LIST = 'CARD_GROUP_LIST';
    public const GROUP_ONE = 'CARD_GROUP_ONE';

    public function __construct(
        Deck $deck,
        Id $id,
        DateTimeImmutable $date,
        string $label = ''
    ) {
        $this->deck = $deck;
        $this->id = $id;
        $this->createdAt = $date;
        $this->updatedAt = $date;
        $this->state = 'new';
        $this->records = new ArrayCollection();
        $this->repeats = new ArrayCollection();
        $this->currentRepeatInterval = $deck->getSettings()->getBaseInterval();
        $this->label = $label;
    }

    public static function createWithRecords(
        Deck $deck,
        Id $id,
        DateTimeImmutable $date,
        array $records,
        string $label = ''
    ): self {
        $card = new self($deck, $id, $date, $label);
        foreach ($records as $record) {
            if ($record instanceof Record) {
                $card->addRecord($record);
                $record->setCard($card);
            }
        }
        return  $card;
    }

    public function setCurrentRepeatInterval(int $currentRepeatInterval)
    {
        $this->currentRepeatInterval = $currentRepeatInterval;
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

    public function getCurrentRepeatInterval(): int
    {
        return $this->currentRepeatInterval;
    }
}
