<?php

declare(strict_types=1);

namespace App\Domain\ElasticSearch\BaseIndex\UseCase\IndexingDocuments;

use App\Domain\ElasticSearch\BaseIndex\Service\ElasticService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReindexDocumentCommand extends Command
{
    // docker-compose exec php bin/console app:es-get-index
    protected static $defaultName = 'app:es-reindex';

    /**
     * @var ElasticService
     */
    protected $elasticService;

    public function __construct(ElasticService $elasticService, string $name = null)
    {
        parent::__construct($name);
        $this->elasticService = $elasticService;
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->elasticService->reindexDocumentAction();

        $io = new SymfonyStyle($input, $output);
        $output->writeln('Результат реиндекса');
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
