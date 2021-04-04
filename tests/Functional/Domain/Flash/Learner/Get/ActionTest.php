<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Flash\Learner\Get;

use App\DataFixtures\Flash\LearnerFixtures;
use App\Tests\AbstractTest;

class ActionTest extends AbstractTest
{
    protected $method = 'GET';
    protected $uri = '/flash/learner/current/';

    public function getFixtures() : array
    {
        return [LearnerFixtures::class];
    }

    public function testValid() : void
    {
        $this->makeRequestWithAuth();

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('id', $this->content);
        self::assertArrayHasKey('name', $this->content);
        self::assertArrayHasKey('first', $this->content['name']);
        self::assertArrayHasKey('last', $this->content['name']);
    }
}
