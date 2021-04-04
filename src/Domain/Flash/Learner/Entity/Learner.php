<?php

declare(strict_types=1);

namespace App\Domain\Flash\Learner\Entity;

use App\Domain\Flash\Learner\Entity\Types\Id;
use App\Domain\Flash\Learner\Entity\Types\Name;
use App\Domain\Flash\Learner\Entity\Types\Session;
use App\Domain\Flash\Learner\Entity\Types\Settings;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="flash_learners")
 */
class Learner
{
    /**
     * @var Id
     * @ORM\Column(type="flash_learner_id")
     * @ORM\Id
     * @Serializer\Groups({Learner::GROUP_SIMPLE})
     */
    private $id;

    /**
     * @var Name
     * @ORM\Embedded(class="App\Domain\Flash\Learner\Entity\Types\Name")
     * @Serializer\Type(name="App\Domain\Flash\Learner\Entity\Types\Name")
     * @Serializer\Groups({Learner::GROUP_SIMPLE})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Flash\Deck\Entity\Deck", mappedBy="learner", orphanRemoval=true, cascade={"persist"})
     */
    private $decks;


    public const GROUP_SIMPLE   = 'GROUP_SIMPLE';
    public const GROUP_SETTINGS = 'GROUP_SETTINGS';
    public const GROUP_DETAILS  = 'GROUP_DETAILS';

    private function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ?Name {
        return $this->name;
    }

    public static function create(Id $id, Name $name = null): self
    {
        $learner = new self($id);
        $learner->changeName($name ?: new Name());
        return $learner;
    }

    public function changeName(Name $name): self
    {
        $this->name = $name;
        return $this;
    }
}
