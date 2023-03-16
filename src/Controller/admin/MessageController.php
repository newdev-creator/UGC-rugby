<?php

namespace App\Controller\admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/message', name: 'admin_message_')]
class MessageController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('/admin/message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->save($message, true);
            $this->addFlash('success', 'Le message a bien été créé');

            return $this->redirectToRoute('admin_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{message}', name: 'show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('admin/message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('edit/{message}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Message $message,
        MessageRepository $messageRepository
    ): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->save($message, true);
            $this->addFlash('success', 'Le message a bien été modifié');

            return $this->redirectToRoute('admin_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{message}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Message $message,
        MessageRepository $messageRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('admin_message_delete_'.$message->getId(), $request->request->get('_token'))) {
            $message->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', 'Le message a bien été supprimé');
        }

        return $this->redirectToRoute('admin_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
