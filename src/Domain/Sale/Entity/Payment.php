<?php

declare(strict_types=1);

namespace App\Domain\Sale\Entity;

use App\Domain\User\Entity\Types\Id;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="sale_payments")
 */
class Payment
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({Payment::GROUP_DETAIL})
     */
    private $id;

    /**
     * @var Id
     * @ORM\Column(type="users_user_id")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({Payment::GROUP_DETAIL})
     */
    private $customer;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({Payment::GROUP_DETAIL})
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({Payment::GROUP_DETAIL})
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({Payment::GROUP_DETAIL})
     */
    private $singed;


    public const GROUP_SIMPLE = 'SALE_GROUP_SIMPLE';
    public const GROUP_DETAIL = 'SALE_GROUP_DETAIL';

    public function __construct(
        Id $id,
        string $description,
        string $amount,
        string $singed
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->amount = $amount;
        $this->singed = $singed;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): Id {
        return $this->customer;
    }
}
