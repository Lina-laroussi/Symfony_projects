<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(UtilisateurRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('utilisateur/list.html.twig', [
            'utilisateurs' => $result,
        ]);
    }

    #[Route('/updateU/{cin}', name: 'update_user')]
    public function update(UtilisateurRepository $repo, $cin, ManagerRegistry $em, Request $req): Response
    {

        $user = $repo->find($cin);
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($user);
            $result->flush();
        }
        return $this->render('utilisateur/Modifier.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/removeU/{cin}', name: 'remove_user')]
    public function remove(UtilisateurRepository $repo, $cin, ManagerRegistry $em, Request $req): Response
    {

        $user = $repo->find($cin);
        $result = $em->getManager();
        $result->remove($user);
        $result->flush();
        return $this->redirectToRoute('list');
    }

    #[Route('/addU', name: 'add_user')]
    public function add(ManagerRegistry $em, Request $req): Response
    {

        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($user);
            $result->flush();
        }
        return $this->render('utilisateur/Ajouter.html.twig', [
            'f' => $form->createView(),
        ]);
    }


    //DQL(doctrine query language)
    #[Route('/fetch', name: 'fetch')]
    public function fetchUser(UtilisateurRepository $repo): Response
    {

        $result = $repo->fetchUser();
        dd($result);

        return $this->render('utilisateur/Ajouter.html.twig', [
            'users' => $result,
        ]);
    }

    //queryBuilder

    #[Route('/fetch1', name: 'fetch1')]
    public function fetchUser1(UtilisateurRepository $repo): Response
    {

        $result = $repo->fetchUser1();
        dd($result);

        return $this->render('utilisateur/Ajouter.html.twig', [
            'users' => $result,
        ]);
    }

    #[Route('/count/{ref}', name: 'count')]
    public function fetchCountUser(UtilisateurRepository $repo, $ref): Response
    {

        $result = $repo->fetchUserByAgence($ref);
        dd($result);

        return $this->render('utilisateur/Ajouter.html.twig', [
            'users' => $result,
        ]);
    }
}
