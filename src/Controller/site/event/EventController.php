<?php

namespace App\Controller\site\event;



use App\Entity\Event;
use App\Form\SubscribeType;
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
        $formSubscribe = $this->createForm(SubscribeType::class);
        $formSubscribe->handleRequest($request);

        if ($formSubscribe->isSubmitted() && $formSubscribe->isValid()) {
            $children = $formSubscribe->get('child')->getData();
            foreach ( $children as $child ) {
                $event->addChild($child);
            }
            $this->em->flush();
            $this->addFlash('success', 'Vous êtes inscrit à l\'événement');
            return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
        }
        return $this->render('site/event/show.html.twig', [
            'event' => $event,
            'form_subscribe' => $formSubscribe->createView(),
        ]);
    }
}