<?php

namespace App\Controller\site;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'app_home')]
    public function index(EventRepository $er): Response
    {
        if (!$this->getUser()) {
            return $this->render('site/security/login.html.twig');
        } else {
            return $this->render('site/home/index.html.twig', [
                'events' => $er->findBy(['isActive' => true], ['date' => 'DESC']),
            ]);
        }
    }
}
