<?php

namespace App\Controller\site\event;



use App\Entity\Event;
use App\Form\SubscribeType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement', name: 'event_')]
class EventController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}
    #[Route('/voir/{event}', name: 'show')]
    public function show(
        Event $event,
        Request $request,
    ): Response
    {
        $user = $this->getUser();
        $formSubscribe = $this->createForm(SubscribeType::class, $user);
        $formSubscribe->handleRequest($request);
        if ( $formSubscribe->isSubmitted() ) {
            if ( !$formSubscribe->isValid() ) {
                $this->addFlash('danger', 'Une erreur est survenue');
            } else {
                $this->em->flush();
                $this->addFlash('success', 'Les informations ont été mises à jour.');
                return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
            }
        }
        return $this->render('site/event/show.html.twig', [
            'event' => $event,
            'form_subscribe' => $formSubscribe->createView(),
        ]);
    }
}