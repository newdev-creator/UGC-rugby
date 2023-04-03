<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarterController extends AbstractController
{
    #[Route('/starter', name: 'app_starter')]
    public function index(
        EventRepository $er,
        Request $request,
    ): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $events = $er->findSearch($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('starter/_events.html.twig', ['events' => $events]),
                'pagination' => $this->renderView('starter/_pagination.html.twig', ['events' => $events]),
            ]);
        }

        return $this->render('starter/index.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }
}
