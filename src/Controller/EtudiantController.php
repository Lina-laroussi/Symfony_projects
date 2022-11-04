<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Note;
use App\Form\EtudiantType;
use App\Form\NoteType;
use App\Form\RechercheType;
use App\Repository\EtudiantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }

    #[Route('/addEtudiant', name: 'add_etudiant')]
    public function addEtudiant(Request $req, ManagerRegistry $em): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($etudiant);
            $result->flush();
            return $this->redirectToRoute('listE');
        }

        return $this->render('etudiant/ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/listE', name: 'listE')]
    public function listEtudiant(ManagerRegistry $em): Response
    {
        $repo = $em->getRepository(Etudiant::class);
        $result = $repo->findAll();

        return $this->render('etudiant/list.html.twig', [
            'etudiants' => $result,
        ]);
    }

    #[Route('/removeE/{id}', name: 'removeE')]
    public function removeE(ManagerRegistry $em, $id): Response
    {
        $repo = $em->getRepository(Etudiant::class);
        $etudiant = $repo->find($id);
        $result = $em->getManager();
        $result->remove($etudiant);
        $result->flush();

        return $this->redirectToRoute('listE');
    }

    #[Route('/updateE/{id}', name: 'updateE')]
    public function updateE(ManagerRegistry $em, EtudiantRepository $repo, $id, Request $req): Response
    {
        $etudiant = $repo->find($id);
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->flush();
            return $this->redirectToRoute('listE');
        }

        return $this->render('etudiant/modifier.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/note/{id}', name: 'affecter_note')]
    public function affecterNote(ManagerRegistry $em, EtudiantRepository $repo, $id, Request $req): Response
    {
        $etudiant = $repo->find($id);
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $note->setEtudiant($etudiant);
            $result = $em->getManager();
            $result->persist($etudiant);
            $result->persist($note);
            $result->flush();
            $result->flush();
        }

        return $this->render('note/Ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/note', name: 'affecter_note1')]
    public function addNote(ManagerRegistry $em, Request $req): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($note);
            $result->flush();
        }

        return $this->render('note/Ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/fetchE', name: 'fetchE')]
    public function fetchEtudiantbyNote(Request $req, EtudiantRepository $repo): Response
    {
        $result = $repo->findAll();
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $niveau = $form->getData();
            $result = $repo->fetchEtudiantByNiveau($niveau);
            if (!$result) {
                return new Response('il nexsite pas un etudiant de ce niveau');
            }
        }

        return $this->render('etudiant/list.html.twig', [
            'etudiants' => $result,
            'f' => $form->createView()
        ]);
    }
}
