<?php

declare(strict_types=1);

namespace App\Domain\Flash\Card\Entity;

use App\Domain\Flash\Card\Entity\Types\Id;
use App\Domain\Flash\Deck\Entity\Deck;
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
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @var Deck
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Deck\Entity\Deck", inversedBy="cards")
     * @ORM\JoinColumn(name="deck_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $deck;

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
}
