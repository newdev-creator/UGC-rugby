<?php

namespace App\Controller\site\event;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/voir/{event}', name: 'show')]
    public function show(): Response
    {
        return $this->render('site/event/show.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
}