<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\Types\Email;
use App\Domain\User\Entity\Types\Id;
use App\Domain\User\Entity\Types\Password;
use App\Domain\User\Entity\Types\Role;
use App\Domain\User\Entity\Types\Status;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use DomainException;
use DateTimeImmutable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 */
class User implements UserInterface
{
    /**
     * @var Id
     * @ORM\Column(type="users_user_id")
     * @ORM\Id
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $id;

    /**
     * @var Email
     * @ORM\Column(type="users_user_email", name="email")
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_SIMPLE})
     * @SWG\Property(example="test@test.test")
     */
    private $email;

    /**
     * @var Password
     * @ORM\Column(type="users_user_password", name="password")
     */
    private $password;

    /**
     * @var Status
     * @ORM\Embedded(class="App\Domain\User\Entity\Types\Status", columnPrefix="status_")
     * @Serializer\Groups({User::GROUP_SIMPLE})
     * @Serializer\Type(name="string")
     * @SWG\Property(enum={User::GROUP_SIMPLE})
     */
    private $status;

    /**
     * @var Role
     * @ORM\Column(type="users_user_role", length=16)
     * @Serializer\Type(name="string")
     * @Serializer\Groups({User::GROUP_SIMPLE})
     * @SWG\Property(example="ROLE_USER")
     */
    private $role;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({User::GROUP_DETAIL})
     */
    private $updatedAt;

    public const GROUP_SIMPLE = 'GROUP_SIMPLE';
    public const GROUP_DETAIL = 'GROUP_DETAIL';

    private function __construct() {}

    public static function createByEmail(
        Id $id,
        DateTimeImmutable $date,
        Role $role,
        Email $email,
        Password $password,
        Status $status
    ): self {
        $user = new self();
        $user->id = $id;
        $user->createdAt = $date;
        $user->updatedAt = $date;
        $user->role = $role;
        $user->email = $email;
        $user->password = $password;
        $user->status = $status;
        return $user;
    }

    public function requestRegisterByEmail(): void
    {
        if (!$this->status->isWait()) {
            throw new DomainException('User is already confirmed.');
        }

        $this->status->changeStatus(Status::STATUS_WAIT);
    }

    public function confirmRegisterByEmail(): void
    {
        if ($this->status->isActive()) {
            throw new DomainException('Confirm user in not requested.');
        }
        $this->status->activate();
    }

    public function requestResetPassword(): void
    {
        if (!$this->status->isActive()) {
            throw new DomainException(json_encode(['user' => 'User is not active.']));
        }
        $this->status->block();
    }
    public function confirmResetPassword(Password $password): void
    {
        if ($this->status->isActive()) {
            throw new DomainException('Resetting is not requested.');
        }

        $this->status->activate();
        $this->password = $password;
    }

    public function confirmChangeEmail(): void
    {
        if ($this->status->isActive()) {
            throw new DomainException('Changing is not requested.');
        }
        $this->status->activate();
    }

    public function changeRole(Role $role, DateTimeImmutable $date): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('Role is already same.');
        }

        $this->role = $role;
        $this->updatedAt = $date;
    }

    public function getId(): Id
    {
        return $this->id;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function getPassword(): ?Password
    {
        return $this->password;
    }
    public function getRoles(): array
    {
        return [$this->role->getName()];
    }
    public function getUsername(): string
    {
        return $this->getEmail()->getValue();
    }
    public function getDateCreated(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getDateUpdated(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setPassword(Password $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt() {}

    public function eraseCredentials() {}
}
