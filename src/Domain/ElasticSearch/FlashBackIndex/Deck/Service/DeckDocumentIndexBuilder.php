<?php

namespace App\Domain\ElasticSearch\FlashBackIndex\Deck\Service;

use App\Domain\ElasticSearch\BaseIndex\Service\BaseIndexDocumentBuilder;
use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\SerializeService;
use Doctrine\Common\Collections\ArrayCollection;

class DeckDocumentIndexBuilder extends BaseIndexDocumentBuilder
{
    /**
     * @var DeckRepository
     */
    private $deckRepository;
    /**
     * @var SerializeService
     */
    private $serializeService;

    public function __construct(DeckRepository $deckRepository, SerializeService $serializeService)
    {
        $this->deckRepository = $deckRepository;
        $this->serializeService = $serializeService;
    }

    public function getIndex(): array
    {
        return [
//            'id' => 'deckId',
            'index' => 'deck'
        ];
    }

    public function getBody(): array {
        /** @var ArrayCollection $decks */
        $decks = $this->deckRepository->findAll();
        $body = [];
        /** @var Deck $deck */
        foreach ($decks as $deck) {
            $body[] = [ 'index' => ['_index' => 'deck'] ];
            $body[] = [
                'name' => $deck->getName()
            ];
        }

        return $body;
    }
}