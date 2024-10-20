<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route(path: '/', name: 'index_page', methods: ['GET'])]
    public function indexAction(Request $request): Response
    {
        return $this->render('/homepage/index.html.twig');
    }
}
