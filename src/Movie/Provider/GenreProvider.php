<?php

namespace App\Movie\Provider;

use App\Entity\Genre;
use App\Movie\Omdb\Transformer\OmdbGenreTransformer;
use Doctrine\ORM\EntityManagerInterface;

class GenreProvider
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbGenreTransformer $genreTransformer
    ) {}

    public function getGenre(string $name): Genre
    {
        return $this->manager->getRepository(Genre::class)->findOneBy(['name' => $name])
            ?? $this->genreTransformer->transform($name);
    }

    public function getFromOmdbString(string $genreNames): iterable
    {
        foreach (explode(', ', $genreNames) as $name) {
            yield $this->getGenre($name);
        }
    }
}
