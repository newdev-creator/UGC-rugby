<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function index(LoggerInterface $logger): Response
    {
        $logger->debug('ceci est un message de debug');
        $logger->info('ceci est un message d\'info');
        $logger->notice('ceci est un message de notice');
        $logger->warning('ceci est un message d\'avertissement');
        $logger->error('ceci est un message d\'erreur');
        $logger->critical('ceci est un message critique');
        $logger->alert('ceci est un message d\'alerte');
        $logger->emergency('ceci est un message d\'urgence');

        return $this->render('error/index.html.twig', [
            'controller_name' => 'ErrorController',
        ]);
    }
}
