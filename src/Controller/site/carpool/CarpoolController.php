<?php

namespace App\Controller\site\carpool;

use App\Entity\Carpool;
use App\Form\SubscribeCarpoolType;
use App\Repository\CarpoolRepository;
use App\Repository\UserChildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/covoiturage', name: 'carpool_')]
class CarpoolController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CarpoolRepository $carpoolRepository): Response
    {
        return $this->render('site/carpool/index.html.twig', [
            'carpools' => $carpoolRepository->findBy(['isActive' => true]),
        ]);
    }

    #[Route('/voir/{carpool}', name: 'show')]
    public function show(
        Carpool $carpool,
        Request $request,
        UserChildRepository $ucr,
    ): Response
    {
        // FORM SUBSCRIBE CARPOOL
        $formSubscribeCarpool = $this->createForm(SubscribeCarpoolType::class);
        $formSubscribeCarpool->handleRequest($request);

        if ($formSubscribeCarpool->isSubmitted() && $formSubscribeCarpool->isValid()) {
            $children = $formSubscribeCarpool->get('child')->getData();
            foreach ( $children as $child ) {
                $carpool->addChild($child);
            }
            $this->em->flush();
            $this->addFlash('success', 'Vous êtes inscrit au covoiturage');
            return $this->redirectToRoute('carpool_show', ['carpool' => $carpool->getId()]);
        }

        // FORM DELETE CHILD FROM CARPOOL
        if ( $request->request->get('delete_child_carpool') ) {
            $child = $ucr->find($request->request->get('delete_child_carpool'));
            $carpool->removeChild($child);
            $this->em->flush();
            $this->addFlash('success', 'Vous avez été désinscrit du covoiturage');
            return $this->redirectToRoute('carpool_show', ['carpool' => $carpool->getId()]);
        }

        return $this->render('site/carpool/show.html.twig', [
            'carpool' => $carpool,
            'form_subscribe_carpool' => $formSubscribeCarpool->createView(),
        ]);
    }
}