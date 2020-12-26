<?php

declare(strict_types=1);

namespace App\Domain\Flash\Deck\Entity;

use App\Domain\Flash\Learner\Entity\Learner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use DateTimeImmutable;

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
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string | null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @var Learner
     * @ORM\ManyToOne(targetEntity="App\Domain\Flash\Entity\Learner\Learner", inversedBy="decks")
     * @ORM\JoinColumn(name="learner_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $learner;

    public function __construct(Learner $learner, string $name, DateTimeImmutable $date, string $description = '')
    {
        $this->learner = $learner;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public function update(string $name, string $description = ''): Deck
    {
        $this->name = $name;
        $this->description = $description;
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
}
