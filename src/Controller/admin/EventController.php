<?php

namespace App\Controller\admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/evenement', name: 'admin_event_')]
class EventController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        EventRepository $eventRepository,
        Request $request
    ): Response
    {
        $isDelete = $request->query->get('is_delete');
        if ($isDelete === "1") {
            $events = $eventRepository->findBy(['isActive' => false], ['addedAt' => 'DESC']);
        } else {
            $events = $eventRepository->findBy(['isActive' => true], ['addedAt' => 'DESC']);
        }

        return $this->render('admin/event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);
            $this->addFlash('success', 'L\'événement a bien été créé');
            return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{event}', name: 'show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/edit/{event}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Event $event,
        EventRepository $eventRepository
    ): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);
            $this->addFlash('success', "L'événement {$event->getTitle()} a bien été modifié");
            return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/archive/{event}', name: 'archive', methods: ['POST'])]
    public function archive(
        Request $request,
        Event $event,
    ): Response
    {
        if ($this->isCsrfTokenValid('admin_event_archive_'.$event->getId(), $request->request->get('_token'))) {
            $event->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', "L'événement {$event->getTitle()} a bien été archivé");
        }

        return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{event}', name: 'delete', methods: ['POST'])]
    public  function delete(
        Request $request,
        Event $event,
        EventRepository $eventRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('admin_event_delete_'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event);
        }
        $this->em->flush();
        $this->addFlash('success', "L'événement {$event->getTitle()} a bien été supprimé");
        return $this->redirectToRoute('admin_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
