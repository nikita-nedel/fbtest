<?php

namespace App\Command;

use App\Service\CurrencyService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:update-currency-rates')]
class UpdateCurrencyRatesCommand extends Command
{
    public function __construct(private CurrencyService $currencyService)
    {
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->currencyService->updateRates();
        $output->writeln('<info>Currency rates updated.</info>');

        return Command::SUCCESS;
    }
}
