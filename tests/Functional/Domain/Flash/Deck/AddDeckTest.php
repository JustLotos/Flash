<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Deck;

use App\DataFixtures\Flash\DeckFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class AddDeckTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/flash/deck/add/';

    public function getFixtures() : array
    {
        return [DeckFixtures::class];
    }

    public function testPostDeckValid() : void
    {
        $this->makeRequestWithAuth([
            'name' => 'deckName',
            'description' => 'description',
        ]);

        $this->assertResponseCode(Response::HTTP_CREATED, $this->response);
        $this->assertArrayHasKey('id', $this->content);
        $this->assertArrayHasKey('name', $this->content);
        $this->assertArrayHasKey('description', $this->content);
        $this->assertArrayHasKey('createdAt', $this->content);
        $this->assertArrayHasKey('updatedAt', $this->content);
    }
}
