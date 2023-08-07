<?php

namespace App\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;

class BookManager implements ManagerInterface
{
    private ?BookRepository $repository = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        #[Autowire(param: 'app.books_per_page')] private readonly int $booksPerPage,
    ) {}

    public function findByTitle(string $title): Book
    {
        return $this->repository->findByApproxTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        return $this->manager->getRepository(Book::class)->findBy([], ['id' => 'DESC'], $this->booksPerPage, $offset);
    }

    #[Required]
    public function setRepository(?BookRepository $repository): void
    {
        $this->repository = $repository;
    }
}
