<?php

namespace App\Task\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'home', methods: "GET")]
    public function __invoke(): Response
    {
        return $this->render('pages/home.html.twig');
    }

}