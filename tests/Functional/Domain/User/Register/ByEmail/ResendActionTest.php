<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Register\ByEmail;

use App\DataFixtures\User\UserFixtures;
use App\Service\RedisService;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ResendActionTest extends AbstractTest
{
    protected $uri = '/user/register/email/resend/';
    protected $method = 'POST';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid() : void
    {
        $email = 'registerResend1@test.test';
        $this->makeRequest([
            'email' => $email,
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab'
        ], '/user/register/email/request/');

        $this->makeRequest(['email' => $email]);
        self::assertResponseOk($this->response);
        self::assertEmailCount(1);
        self::assertArrayHasKey('success', $this->content);
    }


    public function testSendCodeFromActiveUser() : void
    {
        $this->makeRequest(['email' => 'test1@test.test']);

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('user', $this->content['errors']['domain']);
    }

    public function testNonExistingToken() : void
    {
        $email = 'registerResend@test.test';
        $this->makeRequest(['email' => $email]);

        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('token', $this->content['errors']['domain']);
    }
}
