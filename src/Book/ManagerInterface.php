<?php

namespace App\Book;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(BookManager::class)]
interface ManagerInterface
{
    public function findByTitle(string $title): Book;

    public function getPaginated(int $offset): iterable;
}
