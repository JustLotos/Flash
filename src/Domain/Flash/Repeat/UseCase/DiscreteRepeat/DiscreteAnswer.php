<?php

declare(strict_types=1);

namespace App\Domain\Flash\Repeat\UseCase\DiscreteRepeat;

use App\Domain\Flash\Service\AnswerMangerService\Models\IAnswer;
use DateInterval;
use DateTimeImmutable;
use DomainException;
use JMS\Serializer\Annotation as Serializer;

class DiscreteAnswer implements IAnswer
{
    /**
     * @Serializer\Type(name="DateTimeImmutable")
     */
    public $date;

    /**
     * @Serializer\Type(name="DateInterval")
     */
    public $time;

    /**
     * @Serializer\Type(name="string")
     */
    public $status;

    /** Состояния для повторения */
    public const FORGOT = 'FORGOT';
    public const RECOGNIZE = 'RECOGNIZE';
    public const REMEMBER = 'REMEMBER';
    public const KNOW = 'KNOW';

    public function __construct(DateTimeImmutable $date, DateInterval $time, string $status)
    {
        if (!in_array($status, self::getStates())) {
            throw new DomainException('Invalid state value value this should be one of: '. implode(', ', static::getStates()));
        }
        $this->status= $status;
        $this->date = $date;
        $this->time = $time;
    }

    private function evaluate(): array
    {
        return [
            self::FORGOT=>0.5,
            self::RECOGNIZE=>1,
            self::REMEMBER=>1.5,
            self::KNOW=>2.5
        ];
    }

    public function getEstimateAnswer(): float
    {
        $states = $this->evaluate();
        return $states[$this->status];
    }

    public function isSuccess(): bool
    {
        return in_array($this->status, self::getSuccessState());
    }
    public function isFailure(): bool
    {
        return in_array($this->status, self::getFailureState());
    }
    public static function getStates(): array
    {
        return array_merge(self::getFailureState(), self::getSuccessState());
    }
    public static function getSuccessState(): array
    {
        return [self::REMEMBER, self::KNOW];
    }
    public static function getFailureState(): array
    {
        return [self::FORGOT, self::RECOGNIZE];
    }

    public function getTime(): DateInterval
    {
        return $this->time;
    }
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
