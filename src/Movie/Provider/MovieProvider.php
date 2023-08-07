<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Omdb\OmdbApiConsumer;
use App\Movie\Omdb\SearchTypeEnum;
use App\Movie\Omdb\Transformer\OmdbMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
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
        $data = $this->consumer->fetchMovie($type, $value);
        $repository = $this->manager->getRepository(Movie::class);

        if ($movie = $repository->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->movieTransformer->transform($data);
        $genres = $this->genreProvider->getFromOmdbString($data['Genre']);
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
