<?php

declare(strict_types=1);

namespace App\Domain\User\UseCase\Change\Password;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"Command", "After"})
 */
class Command
{

    /**
     * @Assert\Length(min="8", max="255" )
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[a-z])\S*$/",
     *     message="Password must contain at least 1 lowercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[A-Z])\S*$/",
     *     message="Password must contain at least 1 uppercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Serializer\Type(name="string")
     * @SWG\Property(example="12345678Ab")
     */
    public $currentPassword;

    /**
     * @Assert\Length(min="8", max="255" )
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[a-z])\S*$/",
     *     message="Password must contain at least 1 lowercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[A-Z])\S*$/",
     *     message="Password must contain at least 1 uppercase letter"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S*[\d])\S*$/",
     *     message="Password must contain at least 1 number"
     * )
     * @Serializer\Type(name="string")
     * @SWG\Property(example="12345678Abb")
     */
    public $newPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\IdenticalTo(propertyPath="newPassword")
     * @Serializer\Type(name="string")
     * @SWG\Property(example="12345678Abb")
     */
    public $plainPassword;
}
