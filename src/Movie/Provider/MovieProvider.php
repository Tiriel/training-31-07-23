<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Omdb\OmdbApiConsumer;
use App\Movie\Omdb\SearchTypeEnum;
use App\Movie\Omdb\Transformer\OmdbMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(SearchTypeEnum::Title, $title);
    }

    public function getMovie(SearchTypeEnum $type, string $value): Movie
    {
        $this->io?->text('Calling OMdb API.');
        $data = $this->consumer->fetchMovie($type, $value);
        $repository = $this->manager->getRepository(Movie::class);

        $this->io?->text('Searching in database with OMDb information.');
        if ($movie = $repository->findOneBy(['title' => $data['Title']])) {
            $this->io?->info('Movie already in database!');

            return $movie;
        }

        $movie = $this->movieTransformer->transform($data);
        $genres = $this->genreProvider->getFromOmdbString($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        $this->io?->text('Persisting the movie in database.');
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
