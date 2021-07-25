<?php

declare(strict_types=1);

namespace App\Domain\Sale\UseCase\AddPayment;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Serializer\Type(name="string")
     */
    private $ik_x_email;

    /**
     * @Serializer\Type(name="string")
     */
    private $ik_am;


    /**
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


    public function __construct($data)
    {
        $this->ik_am = $data['ik_am'];
        $this->ik_x_email = $data['ik_x_email'];
        $this->ik_desc = $data['ik_desc'];
    }
}