<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Change\Password;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class ActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/user/change/password/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid(): void
    {
        $this->makeRequestWithAuth([
            'currentPassword' => getenv('TEST_USER_PASSWORD'),
            'newPassword' => '123456789Ab',
            'plainPassword' => '123456789Ab'
        ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('success', $this->content);

        $this->makeRequest([
            'email' => getenv('TEST_USER_EMAIL'),
            'password' => '123456789Ab'
        ], '/auth/login/');

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('token', $this->content);
        self::assertArrayHasKey('refreshToken', $this->content);
        self::assertArrayHasKey('role', $this->content);
        self::assertArrayHasKey('status', $this->content);
    }


    public function testInvalidCurrentPassword(): void
    {
        $this->makeRequestWithAuth([
            'currentPassword' => '123456789Ab_invalid',
            'newPassword' => '123456789Ab',
            'plainPassword' => '123456789Ab'
        ]);

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('currentPassword', $this->content['errors']['domain']);
    }

    public function testValidation(): void
    {
        $this->makeRequestWithAuth([
            'currentPassword' => 'invalid',
            'newPassword' => 'asdfasdf',
        ]);

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('newPassword', $this->content['errors']);
        self::assertArrayHasKey('currentPassword', $this->content['errors']);


        $this->makeRequestWithAuth([
            'currentPassword' => getenv('TEST_USER_PASSWORD'),
            'newPassword' => getenv('TEST_USER_PASSWORD'),
            'plainPassword' => 'asdfasdfas'
        ]);

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('plainPassword', $this->content['errors']);

    }

}
