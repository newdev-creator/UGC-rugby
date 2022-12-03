<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarterController extends AbstractController
{
    #[Route('/starter', name: 'app_starter')]
    public function index(): Response
    {
        return $this->render('starter/index.html.twig', [
            'controller_name' => 'StarterController',
        ]);
    }
}
