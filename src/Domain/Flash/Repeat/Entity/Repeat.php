<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\Entity;

use App\Domain\Flash\Card\Entity\Card;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity
 * @ORM\Table(name="flash_repeats")
 */
class Repeat
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Repeat::GROUP_LIST, Repeat::GROUP_ONE})
     */
    private $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Repeat::GROUP_ONE})
     * @Serializer\Type(name="DateTimeImmutable")
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({Repeat::GROUP_ONE})
     * @Serializer\Type(name="DateTimeImmutable")
     */
    private $updatedAt;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Serializer\Groups({Repeat::GROUP_ONE})
     * @Serializer\Type(name="integer")
     */
    private $time;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Serializer\Type(name="float")
     * @Serializer\Groups({Repeat::GROUP_LIST, Repeat::GROUP_ONE})
     */
    private $ratingScore;

    /**
     * @var Card
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Card\Entity\Card", inversedBy="repeats")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $card;

    public const GROUP_LIST = 'GROUP_LIST';
    public const GROUP_ONE = 'GROUP_ONE';

    public function __construct(Card $card, DateTimeImmutable $date, float $ratingScore, int $time) {
        $this->card = $card;
        $this->createdAt = $date;
        $this->updatedAt = $date;
        $this->time = $time;
        $this->ratingScore = $ratingScore;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getRatingScore(): float
    {
        return $this->ratingScore;
    }

}
