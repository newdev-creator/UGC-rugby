<?php

namespace App\Controller\site\user;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('profile/{user}/settings', name: 'settings_')]
class SettingsController extends AbstractController
{
    #[Route('', name: 'show')]
    public function show(
        User $user,
    ): Response
    {
        // verify that the logged in user is the owner of the profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        return $this->render('site/user/settings/settings.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
}
