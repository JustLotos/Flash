<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Change\Email;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/user/change/email/confirm';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid()
    {
        $data = ['email' => 'email@test.test'];
        $this->makeRequestWithAuth($data,'/user/change/email/request/','POST');

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);
        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');
        $this->makeRequestWithAuth([], $this->uri.'/'.$code.'/');

        self::assertArrayHasKey('token', $this->content);
        self::assertArrayHasKey('status', $this->content);
        self::assertArrayHasKey('refreshToken', $this->content);
        self::assertArrayHasKey('role', $this->content);
        self::assertArrayHasKey('email', $this->content);
        self::assertEmailCount(1);
    }

    public function testNotRequested() {
        $this->makeRequestWithAuth([], $this->uri.'/code/');

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('token',  $this->content['errors']['domain']);
    }

    public function testNotValidToken() {
        $data = ['email' => 'email@test.test'];
        $this->makeRequestWithAuth($data,'/user/change/email/request/','POST');
        $this->makeRequestWithAuth([], $this->uri.'/code/');

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('token',  $this->content['errors']['domain']);
    }
}
