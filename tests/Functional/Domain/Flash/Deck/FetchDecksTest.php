<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\Tests\AbstractTest;

class FetchDecksTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/deck/';

    public function getFixtures() : array
    {
        return [ DeckFixtures::class ];
    }

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
}
