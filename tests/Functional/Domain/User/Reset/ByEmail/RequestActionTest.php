<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Reset\ByEmail;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class RequestActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/user/reset/email/request/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid(): void
    {
        $this->makeRequest([
            'email' => getenv('TEST_USER_EMAIL'),
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab'
        ]);
        self::assertResponseOk($this->response);
        self::assertEmailCount(1);
        self::assertArrayHasKey('success', $this->content);

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');
        self::assertIsString($code);
    }

    public function testIsAlreadyRequested(): void
    {
        $email = getenv('TEST_USER_EMAIL');
        $this->makeRequest(['email' =>$email, 'password' => '12345678Ab', 'plainPassword' => '12345678Ab']);
        $this->makeRequest(['email' => $email, 'password' => '12345678Ab', 'plainPassword' => '12345678Ab']);

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('reset', $this->content['errors']['domain']);
    }

//    public function testIsNotActive(): void
//    {
//        $email = 'test5@test.test';
//        $this->makeRequest(['email' =>$email, 'password' => '12345678Ab', 'plainPassword' => '12345678Ab']);
//
//        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
//        self::assertArrayHasKey('errors', $this->content);
//        self::assertArrayHasKey('domain', $this->content['errors']);
//        self::assertArrayHasKey('user', $this->content['errors']['domain']);
//    }

    public function testNotExistingEmail() : void
    {
        $this->makeRequest(['email' => 'not@found.email', 'password' => '12345678Ab', 'plainPassword' => '12345678Ab']);
        $this->assertResponseCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('email', $this->content['errors']);
    }
}
