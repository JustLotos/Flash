<?php

declare(strict_types=1);

namespace App\Domain\ElasticSearch\FlashBackIndex\Deck\UseCase\IndexingDocuments;

use App\Domain\ElasticSearch\BaseIndex\Service\ElasticService;
use App\Domain\ElasticSearch\FlashBackIndex\Deck\Service\DeckDocumentIndexBuilder;
use App\Domain\Flash\Card\Entity\Card;
use App\Domain\Flash\Repeat\Entity\Repeat;
use App\Domain\Flash\Record\Entity\Record;
use App\Domain\Flash\Deck\DeckRepository;
use App\Domain\Flash\Deck\Entity\Deck;
use App\Service\SerializeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReindexCommand extends Command
{
    // docker-compose exec php bin/console app:es-flash-deck-reindex
    protected static $defaultName = 'app:es-flash-deck-reindex';

    /**
     * @var DeckDocumentIndexBuilder
     */
    private $indexBuilder;
    /**
     * @var ElasticService
     */
    private $elasticService;
    /**
     * @var DeckRepository
     */
    private $deckRepository;
    /**
     * @var SerializeService
     */
    private $serializeService;

    protected function configure(): void {}

    public function __construct(
        ElasticService           $elasticService,
        DeckDocumentIndexBuilder $indexBuilder,
        DeckRepository $deckRepository,
        SerializeService $serializeService,
        string                   $name = null
    ) {
        parent::__construct($name);
        $this->indexBuilder = $indexBuilder;
        $this->elasticService = $elasticService;
        $this->deckRepository = $deckRepository;
        $this->serializeService = $serializeService;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->elasticService->getInstance()->indices()->delete(['index' => 'deck']);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }

        $this->elasticService->getInstance()->indices()->create([
            'index' => 'deck',
            'body' => [
                'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
                'analysis' => [
                    'filter' => [
                        'shingle' => [
                            'type' => 'shingle'
                        ]
                    ],
                    'char_filter' => [
                        'pre_negs' => [
                            'type' => 'pattern_replace',
                            'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b',
                            'replacement' => '~$1 $2'
                        ],
                        'post_negs' => [
                            'type' => 'pattern_replace',
                            'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)',
                            'replacement' => '$1 ~$2'
                        ]
                    ],
                    'analyzer' => [
                        'reuters' => [
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => ['lowercase', 'stop', 'kstem']
                        ]
                    ]
                ]
            ],
                'mappings' => [
                    '_source' => ['enabled' => true],
                    'properties' => [
                        'name' => ['type' => 'keyword'],
                        'description' => ['type' => 'text'],
                        'learnerId' => ['type' => 'keyword']
                    ]
                ]]
        ]);
        $decks = $this->deckRepository->findAll();

        /** @var Deck $deck */
        foreach ($decks as $deck) {
            $params = array();
            $jsonBody = $this->serializeService->serialize($deck, [
                Deck::GROUP_LIST,
                Deck::GROUP_ONE,
                Card::GROUP_ONE,
                Repeat::GROUP_ONE,
                Record::GROUP_ONE
            ]);
            $params['body'] = json_decode($jsonBody);
            $params['index'] = 'deck';
            $this->elasticService->getInstance()->index($params);
        }

        $response = $this->elasticService->getInstance()->search(['index' => 'deck']);
        return 0;
    }
}
