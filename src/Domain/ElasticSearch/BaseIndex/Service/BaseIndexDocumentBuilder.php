<?php

namespace App\Domain\ElasticSearch\BaseIndex\Service;

class BaseIndexDocumentBuilder
{
    public function getIndex(): array
    {
        return [
            'index' => 'my_index',
            'id' => 'my_id'
        ];
    }

    public function getBody(): array
    {
        return ['title' => 'test'];
    }
}