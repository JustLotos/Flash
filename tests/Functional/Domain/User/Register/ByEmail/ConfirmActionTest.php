<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Register\ByEmail;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $uri = '/user/register/email/confirm/';
    protected $method = 'GET';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid() : void
    {
        $mail = 'test@test.com';
        $this->makeRequest([
            'email' => $mail,
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab',
        ], '/user/register/email/request/', 'POST');

        self::assertResponseOk($this->response);
        self::assertEmailCount(1);

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);
        self::assertEmailHeaderSame($email, 'To', $mail);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a#confirm-link')->attr('data-token');
        self::assertIsString($code);

        $this->makeRequestWithAuth([], $this->uri.$mail.'/'.$code.'/');

        $content = new Crawler($this->response->getContent());

        $linkToRedirectAfter = '/lk/?registerByEmail=confirm';
        $link = $content->filter('a[href="'.$linkToRedirectAfter.'"]');

        self::assertTrue($this->response->isRedirect());
        self::assertEquals($linkToRedirectAfter, $link->text());
    }


    public function testNonExistingUser() : void
    {
        $mail = 'test@test.test';
        $this->makeRequestWithAuth([], $this->uri.$mail.'/'.'code'.'/');

        self::assertResponseCode(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('email', $this->content['errors']);
    }

    public function testNonExistingToken() : void
    {
        $mail = 'test5@test.test';
        $this->makeRequestWithAuth([], $this->uri.$mail.'/'.'code'.'/');

        self::assertResponseCode(Response::HTTP_NOT_FOUND, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('confirm', $this->content['errors']);
    }
}
