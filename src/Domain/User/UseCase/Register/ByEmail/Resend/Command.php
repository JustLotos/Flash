<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Register\ByEmail\Resend;

use App\Validator\ExistEntityConstraint\ExistEntity;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @SWG\Definition()
 */
class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ExistEntity (
     *     message="Пользователя с таким адресом не существует",
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     * )
     * @Serializer\Type(name="string")
     */
    public $email;
}
