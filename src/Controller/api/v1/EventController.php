<?php

namespace App\Controller\api\v1;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/event', name: 'api_v1_event_')]
class EventController extends AbstractController
{
    #[Route('', name: 'api_event_index')]
    public function index(EventRepository $er): Response
    {
        return $this->json($er->findBy(['isActive' => true], ['date' => 'DESC']), 200, [], ['groups' => 'event:read']);
    }

    #[Route('/{event}', name: 'api_event_show')]
    public function show(Event $event): Response
    {
        return $this->json($event, 200, [], ['groups' => 'event:read']);
    }
}