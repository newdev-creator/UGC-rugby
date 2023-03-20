<?php

namespace App\Controller\site\event;

use App\Entity\Carpool;
use App\Entity\Event;
use App\Form\NewParentCarpoolType;
use App\Form\SubscribeEventType;
use App\Repository\UserChildRepository;
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
        UserChildRepository $ucr,
    ): Response
    {
        // FORM SUBSCRIBE EVENT
        $formSubscribeEvent = $this->createForm(SubscribeEventType::class);
        $formSubscribeEvent->handleRequest($request);

        if ($formSubscribeEvent->isSubmitted() && $formSubscribeEvent->isValid()) {
            $children = $formSubscribeEvent->get('child')->getData();
            foreach ( $children as $child ) {
                $event->addChild($child);
                $event->setNbRegistrant($event->getNbRegistrant() + 1);
            }
            $this->em->flush();
            $this->addFlash('success', 'Vous êtes inscrit à l\'événement');
            return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
        }

        // FORM DELETE CHILD FROM EVENT
        if ( $request->request->get('delete_child') ) {
            $child = $ucr->find($request->request->get('delete_child'));
            $event->removeChild($child);
            $event->setNbRegistrant($event->getNbRegistrant() - 1);
            // and we remove the child from all the carpools of the event
            foreach ( $event->getCarpool() as $carpool ) {
                $carpool->removeChild($child);
            }
            $this->em->flush();
            $this->addFlash('success', 'Vous avez été désinscrit de l\'événement');
            return $this->redirectToRoute('event_show', ['event' => $event->getId()]);
        }


        // FORM NEW CARPOOL
        $carpool = new Carpool();
        $formNewCarpool = $this->createForm(NewParentCarpoolType::class, $carpool);
        $formNewCarpool->handleRequest($request);

        if ($formNewCarpool->isSubmitted() && $formNewCarpool->isValid()) {
            $carpool = $formNewCarpool->getData();

            $carpool->setEvent($event);
            $carpool->addUser($this->getUser());
            foreach ( $carpool->getUsers() as $user ) {
                $user->addCarpool($carpool);
            }
            $this->em->persist($carpool);
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