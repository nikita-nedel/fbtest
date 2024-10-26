<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyService
{
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected EntityManagerInterface $entityManager
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
        int|float $amount,
        string $currencyFrom,
        string $currencyTo
    ): Currency {

    }

    public function updateCurrencyAvailability(int $currencyId, bool $isAvailable): void
    {
        $currency = $this->currencyRepository->findOneById($currencyId);

        if ($currency && $currency->isActive() !== $isAvailable) {
            $currency->setActive($isAvailable);
            $this->entityManager->flush();
        }
    }
}
