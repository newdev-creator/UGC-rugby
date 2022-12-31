<?php

namespace App\Controller\admin;

use App\Entity\UserChild;
use App\Form\UserChildType;
use App\Repository\UserChildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/child')]
class UserChildController extends AbstractController
{
    #[Route('/', name: 'app_user_child_index', methods: ['GET'])]
    public function index(UserChildRepository $userChildRepository): Response
    {
        return $this->render('user_child/index.html.twig', [
            'user_children' => $userChildRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_child_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserChildRepository $userChildRepository): Response
    {
        $userChild = new UserChild();
        $form = $this->createForm(UserChildType::class, $userChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userChildRepository->save($userChild, true);

            return $this->redirectToRoute('app_user_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_child/new.html.twig', [
            'user_child' => $userChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_child_show', methods: ['GET'])]
    public function show(UserChild $userChild): Response
    {
        return $this->render('user_child/show.html.twig', [
            'user_child' => $userChild,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_child_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserChild $userChild, UserChildRepository $userChildRepository): Response
    {
        $form = $this->createForm(UserChildType::class, $userChild);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userChildRepository->save($userChild, true);

            return $this->redirectToRoute('app_user_child_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_child/edit.html.twig', [
            'user_child' => $userChild,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_child_delete', methods: ['POST'])]
    public function delete(Request $request, UserChild $userChild, UserChildRepository $userChildRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userChild->getId(), $request->request->get('_token'))) {
            $userChildRepository->remove($userChild, true);
        }

        return $this->redirectToRoute('app_user_child_index', [], Response::HTTP_SEE_OTHER);
    }
}
