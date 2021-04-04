<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Domain\User\Entity\User;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class CGetActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/deck/';

    public function testGetDecksValid() : void
    {
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertIsArray($this->content);
        self::assertArrayHasKey('id', $this->content[0]);
        self::assertArrayHasKey('name', $this->content[0]);
        self::assertArrayHasKey('description', $this->content[0]);
        self::assertArrayHasKey('createdAt', $this->content[0]);
        self::assertArrayHasKey('updatedAt', $this->content[0]);
    }

    public function testStrangeCGetDeckValid() : void
    {

        $user = self::getEntityManager()->getRepository(User::class)->findOneBy(['email' => self::STRANGER_EMAIL]);
        $deckStranger = self::getEntityManager()->getRepository(Deck::class)->findOneBy(['user' => $user]);

        $this->clientAuth->request('GET', $this->url, [], [], [], '');

        /** @var Response $response */
        $response = $this->clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $check = false;
        foreach ($content as $deck) {
            if($deck['id'] === $deckStranger->getId()) {
                $check = true;
            }
        }
        $this->assertFalse($check);
    }


    public function getFixtures() : array
    {
        return [ UserFixtures::class, DeckFixtures::class ];
    }
}
