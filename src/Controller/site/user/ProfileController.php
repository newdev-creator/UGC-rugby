<?php

namespace App\Controller\site\user;

use App\Entity\User;
use App\Entity\UserChild;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/{user}', name: 'show')]
    public function show(
        User $user,
        UserChild $uc,
    ): Response
    {
        // verify that the logged in user is the owner of the profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        // Retrieve children of the user
        $children = $user->getChild();

        // Retrieve events in which children participate
        $events = [];
        foreach ($children as $child) {
            $events[] = $child->getEvents()->toArray();
        }
        $events = array_merge(...$events);

        // Retrieve carpool in which children participate
        $carpools = [];
        foreach ($children as $child) {
            $carpools[] = $child->getCarpools()->toArray();
        }
        $carpools = array_merge(...$carpools);

        return $this->render('site/user/profile.html.twig', [
            'user' => $user,
            'events' => $events,
            'carpools' => $carpools,
        ]);
    }
}
