<?php

namespace App\Movie\Omdb\Transformer;

use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    private const KEYS = [
        'Title',
        'Poster',
        'Year',
        'Released',
        'Country',
        'Plot',
        'Rated',
        'imdbID',
    ];

    public function transform(mixed $value)
    {
        if (!\is_array($value) || \count(array_diff(self::KEYS, array_keys($value))) > 0) {
            throw new \InvalidArgumentException("Invalid data.");
        }

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        return (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setRated($value['Rated'])
            ->setImdbId($value['imdbID'])
            ->setPrice(500)
        ;
    }

    public function reverseTransform(mixed $value)
    {
        throw new \RuntimeException('Not implemented.');
    }
}
