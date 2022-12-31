<?php

namespace App\Controller\admin;

use App\Entity\UserChild;
use App\Form\UserChildType;
use App\Repository\UserChildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user/child', name: 'admin_user_child_')]
class UserChildController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserChildRepository $userChildRepository): Response
    {
        return $this->render('admin/user_child/index.html.twig', [
            'user_children' => $userChildRepository->findBy(['isActive' => true]),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserChildRepository $userChildRepository): Response
    {
        $userChild = new UserChild();
        $form = $this->createForm(UserChildType::class, $userChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userChildRepository->save($userChild, true);
            $this->addFlash('success', 'L\'enfant a bien été créé');
            return $this->redirectToRoute('admin_user_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user_child/new.html.twig', [
            'user_child' => $userChild,
            'form' => $form,
        ]);
    }

    #[Route('/{child}', name: 'show', methods: ['GET'])]
    public function show(UserChild $userChild): Response
    {
        return $this->render('admin/user_child/show.html.twig', [
            'user_child' => $userChild,
        ]);
    }

    #[Route('/edit/{child}', name: 'admin_user_child_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserChild $userChild, UserChildRepository $userChildRepository): Response
    {
        $form = $this->createForm(UserChildType::class, $userChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userChildRepository->save($userChild, true);
            $this->addFlash('success', 'L\'enfant a bien été modifié');
            return $this->redirectToRoute('admin_user_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user_child/edit.html.twig', [
            'user_child' => $userChild,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{child}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, UserChild $userChild, UserChildRepository $userChildRepository): Response
    {
        if ($this->isCsrfTokenValid('admin_user_child_delete_'.$userChild->getId(), $request->request->get('_token'))) {
            $userChild->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', 'L\'enfant a bien été supprimé');
        }

        return $this->redirectToRoute('admin_user_child_index', [], Response::HTTP_SEE_OTHER);
    }
}
