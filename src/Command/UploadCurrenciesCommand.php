<?php

namespace App\Command;

use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:upload-currencies', description: 'Upload currencies')]
class UploadCurrenciesCommand extends Command
{
    public function __construct(private CurrencyService $currencyService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting upload currencies...');

        $this->currencyService->getAndCreateCurrenciesFromExternalService();

        $output->writeln(
            sprintf(
                '<info>Currencies has been uploaded successfully<info>. Amount of currencies: %d',
                count($this->currencyService->getAllCurrencies())
            )
        );

        return Command::SUCCESS;
    }
}
