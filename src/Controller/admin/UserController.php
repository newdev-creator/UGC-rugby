<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserType;
use App\Helpers\ConnectedUserByRoles;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/parents', name: 'admin_user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        ConnectedUserByRoles $connectedUserByRoles,
    ): Response
    {
        $rolesUser = $connectedUserByRoles->connectedUser();

        $userIsActive = $userRepository->getUsers($rolesUser, $isActive = true);
        // $userIsNotActive = $userRepository->getUsers(false);
        return $this->render('admin/user/index.html.twig', [
            'users' => $userIsActive,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            $this->addFlash('success', 'L\'utilisateur a bien été créé');
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{user}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{user}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        User $user, 
        UserRepository $userRepository
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('delete/{user}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('admin_user_delete_'.$user->getId(), $request->request->get('_token'))) {
            $user->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été supprimé');
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
