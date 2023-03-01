<?php

namespace App\Controller\site;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'app_home')]
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
                'content' => $this->renderView('site/home/_partials/_events.html.twig', ['events' => $events]),
                'pagination' => $this->renderView('site/home/_partials/_pagination.html.twig', ['events' => $events]),
            ]);
        }

        return $this->render('site/home/index.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }
}
