<?php

declare(strict_types=1);

namespace App\Domain\Flash\Record\Entity;

use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Card\Entity\Types\Id;
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
     * @var Id
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
     * @var Card
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Card\Entity\Card", inversedBy="records")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $card;

    public const GROUP_LIST = 'GROUP_LIST';
    public const GROUP_ONE = 'GROUP_ONE';

    public function __construct(
        Card $card,
        string $value,
        DateTimeImmutable $date
    ) {
        $this->card = $card;
        $this->value = $value;
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public function getId(): Id
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

    public function getValue(): string
    {
        return $this->value;
    }
}
