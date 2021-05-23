<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Email\Request;

use App\Validator\ExistEntityConstraint\ExistEntity;
use App\Validator\UniqueEntityConstraint\UniqueEntity;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

class Command
{
    /**
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEntity (
     *     class="App\Domain\User\Entity\User",
     *     attribute="email",
     *     message="Пользователь с таким адресом уже существует"
     * )
     * @Serializer\Type(name="string")
     * @SWG\Property(example="example@mail.com")
     */
    public $email;
}