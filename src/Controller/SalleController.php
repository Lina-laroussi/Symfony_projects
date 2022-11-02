<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalleController extends AbstractController
{
    #[Route('/salle', name: 'app_salle')]
    public function index(): Response
    {
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    #[Route('/addsalle', name: 'add_salle')]
    public function addSalle(Request $req, ManagerRegistry $em): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($salle);
            $result->flush();

            return $this->redirectToRoute('list_salle');
        }

        return $this->render('salle/addSalle.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/listSalle', name: 'list_salle')]
    public function listSalle(SalleRepository $repo): Response
    {

        $result = $repo->findAll();

        return $this->render('salle/listSalle.html.twig', [
            'salles' => $result,
        ]);
    }

    #[Route('/removeSalle/{id}', name: 'remove_salle')]
    public function remove(SalleRepository $repo, $id, ManagerRegistry $em): Response
    {
        $salle = $repo->find($id);
        $result = $em->getManager();
        $result->remove($salle);
        $result->flush();

        return $this->redirectToRoute('list_salle');
    }

    #[Route('/updatesalle/{id}', name: 'update_salle')]
    public function updateSalle(Request $req, $id, ManagerRegistry $em, SalleRepository $repo): Response
    {
        $salle = $repo->find($id);
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->flush();
            return $this->redirectToRoute('list_salle');
        }

        return $this->render('salle/updateSalle.html.twig', [
            'f' => $form->createView(),
        ]);
    }
}
