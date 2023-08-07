<?php

namespace App\Controller;

use App\Book\BookManager;
use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}',
        name: 'app_book_show',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 2],
        methods: ['GET'],
        //condition: "request.headers.get('X-Custom-Header') == 'foo'"
    )]
    public function show(int $id = 1): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::show - id : '.$id,
        ]);
    }

    #[Route('/{title}',
        name: 'app_book_title',
        methods: ['GET'],
    )]
    public function title(string $title, BookManager $bookManager): Response
    {
        $book = $bookManager->findByTitle($title);

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::title : '.$book->getTitle(),
        ]);
    }
}
