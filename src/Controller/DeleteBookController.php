<?php

namespace App\Controller;

use App\Entity\Book;
use App\Security\Voter\BookVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBookController extends AbstractController
{
    #[Route('/book/{id<\d+>}/delete', name: 'app_book_delete')]
    public function __invoke(?Book $book, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted(BookVoter::DELETE, $book);

        $id = $book->getId();
        $manager->remove($book);
        $manager->flush();

        return $this->render('book/index.html.twig', [
            'controller_name' => 'DeleteBookController - id : '.$id
        ]);
    }
}
