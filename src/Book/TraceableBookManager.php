<?php

namespace App\Book;

use App\Entity\Book;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('dev')]
#[AsDecorator(BookManager::class)]
class TraceableBookManager implements ManagerInterface
{
    public function __construct(
        private readonly ManagerInterface $bookManager,
        private readonly LoggerInterface $logger,
    ) {}

    public function findByTitle(string $title): Book
    {
        $this->logger->log('info', 'Searching a book with title '.$title);

        return $this->bookManager->findByTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        $this->logger->log('info', 'getting paginated');

        return $this->bookManager->getPaginated($offset);
    }
}
