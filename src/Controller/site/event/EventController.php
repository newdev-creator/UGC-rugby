<?php

namespace App\Controller\site\event;



use App\Entity\Carpool;
use App\Entity\Event;
use App\Form\SubscribeCarpoolType;
use App\Form\SubscribeEventType;
use App\Repository\CarpoolRepository;
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
        CarpoolRepository $cr,
    ): Response
    {
        // FORM SUBSCRIBE EVENT
        $formSubscribeEvent = $this->createForm(SubscribeEventType::class);
        $formSubscribeEvent->handleRequest($request);

        if ($formSubscribeEvent->isSubmitted() && $formSubscribeEvent->isValid()) {
            $children = $formSubscribeEvent->get('child')->getData();
            foreach ( $children as $child ) {
                $event->addChild($child);
            }
            $this->em->flush();
            $this->addFlash('success', 'Vous êtes inscrit à l\'événement');
            return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
        }

        // FORM NEW CARPOOL
        $carpool = new Carpool();
        $formNewCarpool = $this->createForm(SubscribeCarpoolType::class, $carpool);
        $formNewCarpool->handleRequest($request);

        if ($formNewCarpool->isSubmitted() && $formNewCarpool->isValid()) {
            $carpool = $formNewCarpool->getData();
            $carpool->setEvent($event);
            $this->em->persist($carpool);
            $cr->save($carpool);
            $this->em->flush();
            $this->addFlash('success', 'Vous avez créé un covoiturage');
            return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
        }

        return $this->render('site/event/show.html.twig', [
            'event' => $event,
            'form_subscribe' => $formSubscribeEvent->createView(),
            'form_new_carpool' => $formNewCarpool->createView(),
        ]);
    }
}