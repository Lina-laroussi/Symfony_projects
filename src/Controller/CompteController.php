<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Form\CompteType;
use App\Repository\CompteRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(): Response
    {
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }

    #[Route('/add/{cin}', name: 'add_compte')]
    public function add_compte(ManagerRegistry $rg, Request $req, $cin, UtilisateurRepository $repo): Response
    {
        $compte = new Compte();
        $user = $repo->find($cin);
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $compte->setUtilisateur($user);
            $result = $rg->getManager();
            $result->persist($compte);
            $result->persist($user);
            $result->flush();
            $result->flush();
        }

        return $this->render('compte/Ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    #[Route('/add', name: 'add')]
    public function add_compte1(ManagerRegistry $rg, Request $req): Response
    {
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $rg->getManager();
            $result->persist($compte);
            $result->flush();
            $result->flush();
        }

        return $this->render('compte/Ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/comptes', name: 'comptes')]
    public function list(CompteRepository $repo): Response
    {

        $result = $repo->findAll();

        return $this->render('compte/list.html.twig', [
            'comptes' => $result,
        ]);
    }


    #[Route('/removecompte/{ref}', name: 'removeC')]
    public function remove(ManagerRegistry $em, $ref, CompteRepository $repo): Response
    {
        $compte = $repo->find($ref);
        $result = $em->getManager();
        $result->remove($compte);
        $result->flush();

        return $this->redirectToRoute('comptes');
    }

    #[Route('/updatecompte/{ref}', name: 'updateC')]
    public function update(ManagerRegistry $em, $ref, CompteRepository $repo, Request $req): Response
    {
        $compte = $repo->find($ref);
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($compte);
            $result->flush();
        }

        return $this->render('compte/modifier.html.twig', [
            'f' => $form->createView(),
        ]);
    }
}
