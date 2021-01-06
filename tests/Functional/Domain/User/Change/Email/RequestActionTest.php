<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Change\Email;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\RawMessage;

class RequestActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/user/change/email/request/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid(): void
    {
        $this->makeRequestWithAuth(['email' => getenv('TEST_USER_EMAIL')]);

        self::assertResponseOk($this->response);
        self::assertEmailCount(1);
        self::assertArrayHasKey('success', $this->content);

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');
        self::assertIsString($code);
    }

    public function testNotValidEmails() : void
    {
        $this->makeRequestWithAuth(['email' => getenv('TEST_USER_EMAIL')]);
        self::assertResponseCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('email', $this->content['errors']);
    }

    public function testTokenSent(): void
    {
        $this->makeRequestWithAuth(['email' => 'test@test.test']);
        $this->makeRequestWithAuth(['email' => 'test@test.test']);

        self::assertResponseCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('token',  $this->content['errors']['domain']);

    }
}
