<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\Types\ConfirmToken;
use App\Domain\User\Service\TokenService;
use App\Tests\Builders\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Domain\User\Entity\User;

class ByEmailCreationTest extends TestCase
{
    public function testRegisterByEmail(): void
    {
        /** Create */
        $user = UserBuilder::getNewByEmail();
        $settings = UserBuilder::getDefaultSettings();
        /** Assertion */
        self::assertEquals($settings['id'], $user->getId()->getValue());
        self::assertEquals($settings['dateCreate']->getTimestamp(), $user->getDateCreated()->getTimestamp());
        self::assertEquals($settings['dateCreate']->getTimestamp(), $user->getDateUpdated()->getTimestamp());
        self::assertEquals($settings['role']->getName(), $user->getRole()->getName());
        self::assertEquals($settings['email'], $user->getEmail());
        self::assertEquals($settings['password'], $user->getPassword());
        self::assertEquals(true, $user->getStatus()->isWait());
    }

    /** Проверка исключения о том что учетная запись подтверждена */
    public function testRequestConfirmRegisterByEmailAlreadyConfirmed(): void
    {
        /** @var User $user */
        $user = UserBuilder::getNewByEmail();
        $user->getStatus()->activate();
        self::expectExceptionMessage('User is already confirmed.');
        $user->requestRegisterByEmail();
    }

    /** Проверка исключения о том что запрос на подтверждение учетной записи уже сделан*/
    public function testRequestConfirmRegisterByEmailAlreadyReset(): void
    {
        $user = UserBuilder::getNewByEmail();
        self::expectExceptionMessage('Resetting is already requested.');
        $user->requestRegisterByEmail();
        $user->requestRegisterByEmail();
    }
}
