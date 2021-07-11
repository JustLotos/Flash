<?php

declare(strict_types=1);

namespace App\Domain\Sale\UseCase\AddPayment;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    private $ik_x_email;

    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    private $ik_am;


    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    private $ik_desc;

    public function getEmail(): string
    {
        return $this->ik_x_email;
    }

    public function getAmount(): string {
        return $this->ik_am;
    }

    public function getDescription(): string {
        return $this->ik_desc;
    }
}