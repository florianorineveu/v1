<?php

namespace App\Controller\Front;

use App\Repository\ProjectRepository;
use App\Services\Github;
use Doctrine\Common\Collections\Criteria;
use Github\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projets/", name="project_index")
     */
    public function index(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findBy([
            'enabled' => true,
        ], [
            'sort' => Criteria::ASC,
            'name' => Criteria::ASC,
        ]);

        return $this->render('front/projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projets/{slug}", name="project")
     */
    public function project(Github $github, ProjectRepository $projectRepository, $slug)
    {
        $project = $projectRepository->findOneBy([
            'enabled' => true,
            'slug'    => $slug,
        ]);

        if (!$project) {
            throw $this->createNotFoundException();
        }

        $githubActivity = null;

        /*if ($project->getGithubActivity()) {
            $githubActivity = $project->getGithubActivity();
        } elseif ($project->getGithubOwner() && $project->getGithubRepository()) {
            $githubActivity = new \stdClass();
            $commits        = $github->getAllCommits(
                $project->getGithubOwner(),
                $project->getGithubRepository(),
                $project->getGithubBranch()
            );

            $githubActivity->totalCommits   = count($commits);
            $githubActivity->lastCommitDate = (new \DateTime($commits[0]['commit']['committer']['date']))->setTimezone(new \DateTimeZone('Europe/Paris'));
        }*/

        return $this->render('front/projects/show.html.twig', [
            'project' => $project,
        ]);
    }
}
