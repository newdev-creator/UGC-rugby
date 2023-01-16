<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarterController extends AbstractController
{
    #[Route('/starter', name: 'app_starter')]
    public function index(EventRepository $er): Response
    {
        $events = $er->findSearch();
        return $this->render('site/home/index.html.twig', [
            'events' => $events,
        ]);
    }
}
