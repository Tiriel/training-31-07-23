<?php

namespace App\Command;

use App\Movie\Provider\MovieProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Add a short description for your command',
)]
class MovieFindCommand extends Command
{
    private ?string $title = null;
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly MovieProvider $movieProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::OPTIONAL, 'The title of the movie you are searching for.')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->title = $input->getArgument('title');
        $this->io = new SymfonyStyle($input, $output);
        $this->movieProvider->setIo($this->io);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$this->title) {
            $this->title = $this->io->ask("What is the title of the movie you are searching for ?");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title(sprintf("You are searching for the movie \"%s\".", $this->title));

        try {
            $movie = $this->movieProvider->getMovieByTitle($this->title);
        } catch (NotFoundHttpException) {
            $this->io->error('Movie not found on OMDb or in database!');

            return Command::FAILURE;
        }

        $this->io->table(
            ['Id', 'IMDb Id', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );

        $this->io->success('Your movie is in the database!');

        return Command::SUCCESS;
    }
}
