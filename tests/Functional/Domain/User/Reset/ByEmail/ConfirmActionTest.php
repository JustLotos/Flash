<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Reset\ByEmail;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\RawMessage;

class ConfirmActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/user/reset/email/confirm';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid()
    {
        $mail = 'test5@test.test';

        $this->makeRequest([
            'email' => $mail,
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab'
        ], '/user/reset/email/request/', 'POST');

        /** @var RawMessage $email */
        $email = self::getMailerMessage(0);

        $crawler = new Crawler($email->serialize());
        $code = $crawler->filter('a.confirm-link')->attr('data-token');
        $client = $this->makeRequest([
            'email' => $mail,
            'token' => $code,
            'password' => '12345678Ba',
            'plainPassword' => '12345678Ba'
        ], "$this->uri/");

        self::assertTrue($this->response->isRedirect());

        /** @var Response $response */
        $response = $client->getResponse();
        $content = new Crawler($response->getContent());

        $link = $content->filter('a[href="/?resetByEmail=confirm"]');
        self::assertEquals('/?resetByEmail=confirm', $link->text());
    }

    public function testNotRequested()
    {
        $this->makeRequest([
            'email' => 'test5@test.test',
            'token' => 'test',
            'password' => '12345678Ba',
            'plainPassword' => '12345678Ba'
        ], $this->uri.'/');

        self::assertResponseNotFound($this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('domain', $this->content['errors']);
        self::assertArrayHasKey('reset', $this->content['errors']['domain']);
    }
}
