<?php

namespace App\Domain\ElasticSearch\FlashBackIndex\Deck\Service;

use App\Domain\ElasticSearch\BaseIndex\Service\BaseIndexBuilder;
use App\Domain\Flash\Deck\DeckRepository;

class DeckIndexBuilder extends BaseIndexBuilder
{
    const INDEX = 'deck_index';
    const INDEX_ID = 'deck_index_id';
    /**
     * @var DeckRepository
     */
    private $deckRepository;

    public function __construct(DeckRepository $deckRepository)
    {
        $this->deckRepository = $deckRepository;
    }

    public function getIndex(): array
    {
        return ['index' => self::INDEX];
    }

    public function getMapping(): array
    {
        return [
//            'title' => [
//                'type' => 'text',
//                'analyzer' => 'reuters',
//                'copy_to' => 'combined'
//            ]
        ];
    }
}