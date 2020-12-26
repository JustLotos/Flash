<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\Types;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Status
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

    public const STATUS_WAIT = 'WAIT';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_BLOCKED = 'BLOCKED';

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::getAllStatus());
        $this->value = $value;
    }

    public static function createWait() {
        return new self(self::STATUS_WAIT);
    }

    public function changeStatus(string $value) {
        Assert::oneOf($value, self::getAllStatus());
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function getAllStatus(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_BLOCKED,
            self::STATUS_WAIT
        ];
    }

    public function isWait(): bool
    {
        return $this->value === self::STATUS_WAIT;
    }
    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
    }
    public function isBlocked(): bool
    {
        return $this->value === self::STATUS_BLOCKED;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }

        $this->value = self::STATUS_ACTIVE;
    }
    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new \DomainException('User is already blocked.');
        }
        $this->value = self::STATUS_BLOCKED;
    }
}
