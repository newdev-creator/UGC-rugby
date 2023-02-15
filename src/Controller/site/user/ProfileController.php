<?php

namespace App\Controller\site\user;

use App\Entity\User;
use App\Entity\UserChild;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/{user}', name: 'show')]
    public function show(
        User $user,
        UserRepository $ur,
        UserChild $uc,
    ): Response
    {
        // verify that the logged in user is the owner of the profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        // Retrieve children of the user logged in
        $children = $user->getChild();

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

        // get carpools who user are create
        $userCarpoolsArray = [];
        $userCarpools = $user->getCarpool()->toArray();
        foreach ($userCarpools as $carpool) {
            if ($carpool->getIsActive()) {
                $userCarpoolsArray[] = $carpool;
            }
        }

        return $this->render('site/user/profile.html.twig', [
            'user' => $user,
            'user_carpools' => $userCarpoolsArray,
            'events' => $events,
            'carpools' => $carpools,
        ]);
    }
}
