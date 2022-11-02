<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Repository\CandidatRepository;
use App\Repository\SujetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SujetController extends AbstractController
{


    #[Route('/listSujet', name: 'sujets')]
    public function searchsujetbytitre(SujetRepository $repo, Request $req): Response
    {

        $result = $repo->findAll();
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $titre = $form->getData();
            $result = $repo->fetchSujetByTitre($titre);
        }
        return $this->render('sujet/list.html.twig', [
            'sujets' => $result,
            'f' => $form->createView()
        ]);
    }


    #[Route('/sujetadequat/{id}', name: 'sujet_adequat')]
    public function searchSujetAdequat(SujetRepository $repo, $id, CandidatRepository $candidat): Response
    {

        $user = $candidat->fetchCandidatById($id);
        $sujets = $repo->fetchSujet();

        return $this->render('candidat/sujet.html.twig', [
            'user' => $user,
            'sujets' => $sujets
        ]);
    }

    #[Route('/affecterSujet/{id}/{ref}', name: 'affecter_sujet')]
    public function affecterSujet(SujetRepository $repo, $id, CandidatRepository $candidat, $ref, ManagerRegistry $rg): Response
    {

        $user = $candidat->find($id);
        $sujet = $repo->find($ref);
        $user->setSujet($sujet);

        $result = $rg->getManager();
        $result->persist($sujet);
        $result->persist($user);
        $result->flush();
        $result->flush();

        return $this->redirectToRoute('candidat');
    }
}
