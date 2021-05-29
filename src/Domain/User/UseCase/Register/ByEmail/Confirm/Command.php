<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Confirm;

use App\Validator\ExistEntityConstraint\ExistEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type(name="string")
     */
    public $token;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ExistEntity(
     *     message="User not found",
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     * )
     * @Serializer\Type(name="string")
     */
    public $email;

    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }
}
