<?php

namespace App\Controller\site\user;

use App\Entity\User;
use App\Entity\UserChild;
use App\Form\User_UserChildType;
use App\Repository\UserChildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('/{user}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        User $user,
        Request $request,
        UserChildRepository $userChildRepository,
    ): Response
    {
        // verify that the logged in user is the owner of the profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        // Retrieve children isActive of the user logged in
        $children = $user->getChild();

        $activeChildren = [];
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }

        // Retrieve events in which children participate
        $events = [];
        foreach ($children as $child) {
            $childEvents = $child->getEvents()->toArray();
            foreach ($childEvents as $event) {
                if ($event->getIsActive()) {
                    $events[] = $event;
                }
            }
        }

        // Retrieve carpool in which children participate
        $carpools = [];
        foreach ($children as $child) {
            $childCarpools = $child->getCarpools()->toArray();
            foreach ($childCarpools as $carpool) {
                if ($carpool->getIsActive()) {
                    $carpools[] = $carpool;
                }
            }
        }

        // get carpools who user have create
        $userCarpoolsArray = [];
        $userCarpools = $user->getCarpool()->toArray();
        foreach ($userCarpools as $carpool) {
            if ($carpool->getIsActive()) {
                $userCarpoolsArray[] = $carpool;
            }
        }

        // create new child
        $userChild = new UserChild();
        $formNewChild = $this->createForm(User_UserChildType::class, $userChild);
        $formNewChild->handleRequest($request);

        if ($formNewChild->isSubmitted() && $formNewChild->isValid()) {
            $userChild = $formNewChild->getData();
            $userChild->setUser($user);
            
            try {
                $this->em->persist($userChild);
                $this->em->flush();
                
                $this->addFlash('success', 'Le profil de votre enfant a bien été créé');
                return $this->redirectToRoute('profile_show', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création du profil de votre enfant : ' . $e->getMessage());
            }
        }

        return $this->renderForm('site/user/profile.html.twig', [
            'user' => $user,
            'active_child' => $activeChildren,
            'user_carpools' => $userCarpoolsArray,
            'events' => $events,
            'carpools' => $carpools,
            'form_new_child' => $formNewChild,
        ]);
    }

    #[Route('/{user}/child/{child}/deactivate', name: 'deactivate_child', methods: ['POST'])]
    public function deactivateChild(
        User $user,
        UserChild $child,
        Request $request,
    ): Response {
        // verify that the logged in user is the owner of the child's profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        // set the isActive property of the child to false
        if ($this->isCsrfTokenValid('user_child_deactivate_'.$child->getId(), $request->request->get('_token'))) {
            $child->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', 'L\'enfant a bien été supprimé');
        }

        // redirect to the profile page
        return $this->redirectToRoute('profile_show', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
    }

}
