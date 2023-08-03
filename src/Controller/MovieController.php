<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_movie_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_movie_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function save(?Movie $movie): Response
    {
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        return $this->render('movie/save.html.twig', [
            'form' => $form,
        ]);
    }

    public function decadesMenu(): Response
    {
        $decades = [
            '1980',
            '1990',
            '2010',
        ];

        return $this->render('includes/_decades.html.twig', [
            'decades' => $decades,
        ])->setMaxAge(3600);
    }
}
