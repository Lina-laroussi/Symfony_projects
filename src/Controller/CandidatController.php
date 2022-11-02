<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatController extends AbstractController
{
    #[Route('/candidat', name: 'app_candidat')]
    public function index(): Response
    {
        return $this->render('candidat/index.html.twig', [
            'controller_name' => 'CandidatController',
        ]);
    }


    #[Route('/candidats', name: 'candidats')]
    public function candidats(CandidatRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('candidat/candidats.html.twig', [
            'candidats' => $result,
        ]);
    }

    #[Route('/removeC/{id}', name: 'removeCandidat')]
    public function removeCandidat(ManagerRegistry $em, CandidatRepository $repo, $id): Response
    {
        $candidat = $repo->find($id);
        $result = $em->getManager();
        $result->remove($candidat);
        $result->flush();

        return $this->redirectToRoute('candidats');
    }

    #[Route('/addcandidat', name: 'add_candidat')]
    public function add(Request $req, ManagerRegistry $em): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($candidat);
            $result->flush();
            return $this->redirectToRoute('candidat');
        }

        return $this->render('candidat/Ajouter.html.twig', [
            'f' => $form->createView(),

        ]);
    }

    #[Route('/listcandidat', name: 'candidat')]
    public function list(CandidatRepository $repo): Response
    {
        $result = $repo->findAll();

        return $this->render('candidat/affiche.html.twig', [
            'candidates' => $result,
        ]);
    }
}
