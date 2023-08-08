<?php

namespace App\Security\Voter\Movie;

interface MovieVoterInterface
{
    public const RATED = 'movie.rated';
    public const EDIT = 'movie.edit';
    public const DELETE = 'movie.delete';
}
