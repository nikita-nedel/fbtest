<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route(path: '/', name: 'index_page', methods: ['GET'])]
    public function indexAction(CurrencyService $currencyService): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        return $this->render('/homepage/index.html.twig', [
            'currencies' => $currencyService->getActiveCurrencies(),
        ]);
    }
}
