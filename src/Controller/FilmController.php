<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Form\RechercheType;
use App\Repository\FilmRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/film', name: 'app_film')]
    public function index(): Response
    {
        return $this->render('film/index.html.twig', [
            'controller_name' => 'FilmController',
        ]);
    }

    #[Route('/addFilm', name: 'add_film')]
    public function addFilm(Request $req, ManagerRegistry $em): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->persist($film);
            $result->flush();
            $result->flush();


            return $this->redirectToRoute('list_film');
        }

        return $this->render('film/addFilm.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/listFilm', name: 'list_film')]
    public function listFilm(FilmRepository $repo): Response
    {

        //$result = $this->getDoctrine()->getRepository(Film::class)->findAll();
        $result = $repo->findAll();

        return $this->render('film/listFilm.html.twig', [
            'films' => $result,
        ]);
    }

    #[Route('/removeFilm/{id}', name: 'remove_film')]
    public function remove(FilmRepository $repo, $id, ManagerRegistry $em): Response
    {
        $film = $repo->find($id);
        $result = $em->getManager();
        $result->remove($film);
        $result->flush();

        return $this->redirectToRoute('list_film');
    }


    #[Route('/removeF/{id}', name: 'removeFilm')]
    public function removeFilm(FilmRepository $repo, $id, ManagerRegistry $em): Response
    {

        //$film = $repo->find($id);
        $film = $repo->fetchFilm($id);
        if ($film) {
            $film = $repo->find($id);
            $result = $em->getManager();
            $result->remove($film);
            $result->flush();
            return $this->redirectToRoute('list_film');
        } else {
            return new Response("erreur on peut pas supprimer un film");
        }
    }

    #[Route('/updateFilm/{id}', name: 'update_film')]
    public function updateFilm(Request $req, $id, ManagerRegistry $em, filmRepository $repo): Response
    {
        $film = $repo->findById($id);
        dd($film);
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $em->getManager();
            $result->flush();
            return $this->redirectToRoute('list_film');
        }

        return $this->render('film/updateFilm.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/rechercherFilm', name: 'search_film')]
    public function rechercherFilm(Request $req, ManagerRegistry $em, filmRepository $repo): Response
    {

        $result = $repo->fetchFilms();
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $titre = $form->getData();
            $result = $repo->fetchFilmByTitre($titre);
        }

        return $this->render('film/rechercheFilm.html.twig', [
            'f' => $form->createView(),
            'films' => $result
        ]);
    }

    #[Route('/query', name: 'query')]
    public function queryBuilder(filmRepository $repo): Response
    {

        $result = $repo->fetchFilmByLettre();
        dd($result);

        return $this->render('film/rechercheFilm.html.twig', []);
    }
}
