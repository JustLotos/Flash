<?php

declare(strict_types=1);

namespace App\Domain\ElasticSearch\FlashBackIndex\Deck\UseCase;

use App\Domain\ElasticSearch\BaseIndex\Service\ElasticService;
use App\Domain\ElasticSearch\FlashBackIndex\Deck\UseCase\IndexingDocuments\ReindexCommand as BaseReindexCommand;
use App\Domain\ElasticSearch\FlashBackIndex\Deck\Service\DeckIndexBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateIndexCommand extends BaseReindexCommand
{
    // docker-compose exec php bin/console app:es-flash-deck-create
    protected static $defaultName = 'app:es-flash-deck-create';

    protected function configure(): void {}

    public function __construct(
        ElasticService $elasticService,
        DeckIndexBuilder $indexBuilder,
        string $name = null
    ) {
        parent::__construct($elasticService, $name);
        $this->elasticService = new ElasticService($indexBuilder);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->elasticService->createIndexAction();
        $io = new SymfonyStyle($input, $output);
        $output->writeln('Результат создания');
        var_dump($result);
//        $output->writeln('Результат реиндекса');
//        $io->($result);
        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return 0;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
