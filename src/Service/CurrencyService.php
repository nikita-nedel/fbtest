<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyService
{
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected EntityManagerInterface $entityManager,
        protected FreeCurrencyApiService $freeCurrencyApiService
    ) {
    }

    /**
     * @return Currency[]
     */
    public function getAllCurrencies(): array
    {
        return $this->currencyRepository->findAll();
    }

    /**
     * @return Currency[]
     */
    public function getActiveCurrencies(): array
    {
        return $this->currencyRepository->findAllActive();
    }

    public function convert(
        int|float|string $amount,
        string $currencyFromId,
        string $currencyToId
    ): array {

        $currencyFrom = $this->currencyRepository->findOneBy(['id' => $currencyFromId]);
        $currencyTo = $this->currencyRepository->findOneBy(['id' => $currencyToId]);

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        if (!$currencyFrom || !$currencyTo) {
            throw new EntityNotFoundException('Currency not found');
        }

        $baseAmount = $amount / $currencyFrom->getRate();

        return [
            'convertedAmount' => round($baseAmount * $currencyTo->getRate(), 2),
            'symbol' => $currencyTo->getSymbol(),
        ];
    }

    public function updateCurrencyAvailability(int $currencyId, bool $isAvailable): void
    {
        $currency = $this->currencyRepository->findOneById($currencyId);

        if ($currency && $currency->isAvailable() !== $isAvailable) {
            $currency->setAvailable($isAvailable);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function updateRates(): void
    {
        $data = $this->freeCurrencyApiService->sendRequest('latest');

        foreach ($data['data'] as $item) {
            $currency = $this->currencyRepository->findOneBy([
                'code' => $item['code']
            ]);

            if (!$currency) {
                continue;
            }

            $currency
                ->setRate($item['value']);

            $this->entityManager->persist($currency);
        }

        $this->entityManager->flush();
    }

    public function getAndCreateCurrenciesFromExternalService(): void
    {
        $data = $this->freeCurrencyApiService->sendRequest('currencies');

        foreach ($data['data'] as $item) {
            $currency = new Currency();

            $currency
                ->setCode($item['code'])
                ->setName($item['name'])
                ->setSymbol($item['symbol'])
                ->setAvailable(false);

            $this->entityManager->persist($currency);
        }

        $this->entityManager->flush();
    }

    public function getCurrencySymbolById(int $currencyId): ?string
    {
        return $this->currencyRepository->findOneById($currencyId)->getSymbol();
    }
}
