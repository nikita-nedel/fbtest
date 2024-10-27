<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/currency')]
class CurrencyController extends AbstractController
{
    public function __construct(protected CurrencyService $currencyService)
    {
    }

    #[Route('/all', name: 'currencies_list', methods: ['GET'])]
    public function listAction(): Response
    {
        return $this->render('currency/list.html.twig', [
            'currencies' => $this->currencyService->getActiveCurrencies(),
            'title' => 'Currencies'
        ]);
    }

    #[Route('/manage', name: 'currencies_manage', methods: ['GET'])]
    public function manageCurrencies(): Response
    {
        $allCurrencies = $this->currencyService->getAllCurrencies();

        return $this->render('currency/manage.html.twig', [
            'title' => 'Manage Currencies',
            'currencies' => $allCurrencies,
        ]);
    }

    #[Route('/updateAvailability', name: 'currencies_availability', methods: ['POST'])]
    public function updateAvailability(Request $request): Response
    {
        $currencyItem = $request->toArray();
        $this->currencyService->updateCurrencyAvailability(
            currencyId: $currencyItem['currencyId'],
            isAvailable: (bool)$currencyItem['isAvailable'],
        );

        return new JsonResponse(['data' => 'Success']);
    }

    #[Route('/convert', name: 'currencies_convert', methods: ['POST'])]
    public function convertAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $baseCurrency = $data['baseCurrency'];
        $targetCurrency = $data['targetCurrency'];
        $amount = $data['amount'];

        try {

            $convertedAmount = $this->currencyService->convert(
                amount: $amount,
                currencyFromId: $baseCurrency,
                currencyToId: $targetCurrency
            );

            return new JsonResponse(
                [
                    $convertedAmount
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
