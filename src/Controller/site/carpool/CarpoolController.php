<?php

namespace App\Controller\site\carpool;

use App\Repository\CarpoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/covoiturage', name: 'carpool_')]
class CarpoolController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(CarpoolRepository $cr): Response
    {
        return $this->render('site/carpool/index.html.twig', [
            'carpools' => $cr->findBy(['isActive' => true], ['addedAt' => 'DESC']),
        ]);
    }
}