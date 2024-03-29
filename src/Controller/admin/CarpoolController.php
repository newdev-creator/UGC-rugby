<?php

namespace App\Controller\admin;

use App\Entity\Carpool;
use App\Form\CarpoolType;
use App\Helpers\ConnectedUserByRoles;
use App\Repository\CarpoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/covoiturage', name: 'admin_carpool_')]
class CarpoolController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        CarpoolRepository $carpoolRepository,
        ConnectedUserByRoles $connectedUserByRoles,
        Request $request
    ): Response
    {
        $rolesUser = $connectedUserByRoles->connectedUser();

        $carpoolIsActive = $carpoolRepository->getCarpools($rolesUser, $isActive = true);
        $carpoolIsNotActive = $carpoolRepository->getCarpools($rolesUser, $isActive = false);
        $isDelete = $request->query->get('is_delete');
        if ($isDelete === "1") {
            $carpools = $carpoolIsNotActive;
        } else {
            $carpools = $carpoolIsActive;
        }

        return $this->render('admin/carpool/index.html.twig', [
            'carpools' => $carpools,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarpoolRepository $carpoolRepository): Response
    {
        $carpool = new Carpool();
        $form = $this->createForm(CarpoolType::class, $carpool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carpoolRepository->save($carpool, true);
            $this->addFlash('success', 'Le covoiturage a bien été créé');
            return $this->redirectToRoute('admin_carpool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/carpool/new.html.twig', [
            'carpool' => $carpool,
            'form' => $form,
        ]);
    }

    #[Route('/{carpool}', name: 'show', methods: ['GET'])]
    public function show(Carpool $carpool): Response
    {
        return $this->render('admin/carpool/show.html.twig', [
            'carpool' => $carpool,
        ]);
    }

    #[Route('/edit/{carpool}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carpool $carpool, CarpoolRepository $carpoolRepository): Response
    {
        $form = $this->createForm(CarpoolType::class, $carpool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carpoolRepository->save($carpool, true);
            $this->addFlash('success', 'Le covoiturage a bien été modifié');
            return $this->redirectToRoute('admin_carpool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/carpool/edit.html.twig', [
            'carpool' => $carpool,
            'form' => $form,
        ]);
    }

    #[Route('/archive/{carpool}', name: 'archive', methods: ['POST'])]
    public function archive(
        Request $request,
        Carpool $carpool,
    ): Response
    {
        if ($this->isCsrfTokenValid('admin_carpool_archive_'.$carpool->getId(), $request->request->get('_token'))) {
            $carpool->setIsActive(0);
            $this->em->flush();
            $this->addFlash('success', "Le covoiturage a bien été archivé");
        }

        return $this->redirectToRoute('admin_carpool_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{carpool}', name: 'delete', methods: ['POST'])]
    public  function delete(
        Request $request,
        Carpool $carpool,
        CarpoolRepository $carpoolRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('admin_carpool_delete_'.$carpool->getId(), $request->request->get('_token'))) {
            $carpoolRepository->remove($carpool);
        }
        $this->em->flush();
        $this->addFlash('success', "Le covoiturage a bien été supprimé");
        return $this->redirectToRoute('admin_carpool_index', [], Response::HTTP_SEE_OTHER);
    }

}
