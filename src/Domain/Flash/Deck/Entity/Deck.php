<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\Entity;

use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Deck\Entity\Types\Settings;
use App\Domain\Flash\Learner\Entity\Learner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use DateTimeImmutable;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="flash_decks")
 */
class Deck
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Deck::GROUP_LIST, Deck::GROUP_ONE, Card::GROUP_LIST})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({Deck::GROUP_LIST, Deck::GROUP_ONE})
     */
    private $name;

    /**
     * @var string | null
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups({Deck::GROUP_LIST, Deck::GROUP_ONE})
     */
    private $description;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $createdAt;

    /**
     * @var Settings
     * @ORM\Embedded(class="App\Domain\Flash\Deck\Entity\Types\Settings", columnPrefix="settings_")
     * @Serializer\Groups({Deck::GROUP_ONE})
     */
    private $settings;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Deck::GROUP_LIST, Deck::GROUP_ONE})
     */
    private $updatedAt;

    /**
     * @var Learner
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Learner\Entity\Learner", inversedBy="decks")
     * @ORM\JoinColumn(name="learner_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $learner;

    /**
     * @var PersistentCollection
     * @Serializer\Groups({Deck::GROUP_ONE})
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Flash\Card\Entity\Card",
     *     mappedBy="deck",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $cards;

    public const GROUP_LIST = 'DECK_GROUP_LIST';
    public const GROUP_ONE = 'DECK_GROUP_ONE';


    public function __construct(
        Learner $learner,
        string $name,
        DateTimeImmutable $date,
        Settings $settings,
        ?string $description = ''
    ) {
        $this->learner = $learner;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $date;
        $this->updatedAt = $date;
        $this->cards = new ArrayCollection();
        $this->settings = $settings;
    }

    public function update(string $name, DateTimeImmutable $updatedAt, ?string $description = ''): Deck
    {
        $this->name = $name;
        $this->description = $description;
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getLearner(): Learner
    {
        return $this->learner;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getDescription() : ?string
    {
        return $this->description;
    }
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSettings(): Settings
    {
        return $this->settings;
    }

    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
        }

        return $this;
    }

    public function removeChild(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
        }

        return $this;
    }
}
