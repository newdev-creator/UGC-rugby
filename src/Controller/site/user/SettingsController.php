<?php

namespace App\Controller\site\user;

use App\Entity\User;
use App\Form\Settings_userType;
use App\Form\Settings_userPictureType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('profile/settings/', name: 'settings_')]
class SettingsController extends AbstractController
{
    // Inject the ManagerRegistry to access the EntityManager
    public function __construct(private ManagerRegistry $managerRegistry) {}
    
    #[Route('{user}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Request $request, 
        User $user,
        UserRepository $userRepository,
    ): Response
    {
        // verify that the logged in user is the owner of the profile
        if ($this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce profil');
        }

        // PERSONNAL DETAILS
        $settingsUserForm = $this->createForm(Settings_userType::class, $user);
        $settingsUserForm->handleRequest($request);

        if ($settingsUserForm->isSubmitted() && $settingsUserForm->isValid()) {
            $userRepository->save($user, true);
            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('settings_show', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('site/user/settings/settings.html.twig', [
            'user' => $user,
            'settings_user_form' => $settingsUserForm->createView(),
        ]);
    }
}
