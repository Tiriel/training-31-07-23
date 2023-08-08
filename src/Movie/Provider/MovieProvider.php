<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Omdb\OmdbApiConsumer;
use App\Movie\Omdb\SearchTypeEnum;
use App\Movie\Omdb\Transformer\OmdbMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly OmdbApiConsumer $consumer,
        private readonly OmdbMovieTransformer $movieTransformer,
        private readonly GenreProvider $genreProvider,
        private readonly Security $security,
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

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->io?->note('You are performing the search as an Admin.');
        }

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
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
