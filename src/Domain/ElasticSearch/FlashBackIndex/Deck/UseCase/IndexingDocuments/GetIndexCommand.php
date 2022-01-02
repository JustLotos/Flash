<?php

declare(strict_types=1);

namespace App\Domain\ElasticSearch\FlashBackIndex\Deck\UseCase\IndexingDocuments;

use App\Domain\ElasticSearch\BaseIndex\Service\ElasticService;
use App\Domain\ElasticSearch\FlashBackIndex\Deck\Service\DeckDocumentIndexBuilder;
use App\Domain\ElasticSearch\FlashBackIndex\Deck\Service\DeckIndexBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetIndexCommand extends Command
{
    // docker-compose exec php bin/console app:es-flash-deck-get-index
    protected static $defaultName = 'app:es-flash-deck-get-index';

    /**
     * @var DeckDocumentIndexBuilder
     */
    private $indexBuilder;
    /**
     * @var ElasticService
     */
    private $elasticService;

    public function __construct(
        ElasticService           $elasticService,
        DeckDocumentIndexBuilder $indexBuilder,
        string                   $name = null
    ) {
        parent::__construct($name);
        $this->indexBuilder = $indexBuilder;
        $this->elasticService = $elasticService;
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->elasticService->getInstance()->get([
            'id' => 'deckId',
            'index' => 'deck'
        ]);
        $output->writeln('Индекс');
        var_dump($result);

        return 0;
    }
}
