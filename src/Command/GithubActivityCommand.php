<?php

namespace App\Command;

use App\Entity\Project;
use App\Services\Github;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GithubActivityCommand extends Command
{
    protected static $defaultName = 'app:github-activity';

    private $em;
    private $github;

    public function __construct(
        EntityManagerInterface $entityManager,
        Github $github,
        string $name = null
    ) {
        parent::__construct($name);

        $this->em     = $entityManager;
        $this->github = $github;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('project-id', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io        = new SymfonyStyle($input, $output);
        $projectId = $input->getArgument('project-id');
        $projects  = [];

        if ($projectId) {
            $project = $this->em->getRepository(Project::class)->find($projectId);
            if (!$project) {
                $io->error('This project does not exist!');

                return 1;
            }

            $projects[] = $project;
        } else {
            $projects = $this->em->getRepository(Project::class)->findForGithubActivity();
        }

        foreach ($projects as $project) {
            $io->note(sprintf('Update activity for: %s', $project->getName() . '(' . $project->getId() . ')'));
            $this->github->updateActivity($project);
        }

        $this->em->flush();

        $io->success('Github activities updated!');

        return 0;
    }
}
