<?php

namespace App\Controller\Front;

use App\Services\Github;
use Github\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projets/", name="project_index")
     */
    public function index()
    {
        return $this->render('front/projects/index.html.twig');
    }

    /**
     * @Route("/projets/{slug}", name="project")
     */
    public function project(Github $github, $slug)
    {
        $availableProjects = [
            'alnilam',
            'athena',
            //'carre-rose-films',
            'le-tag-parfait',
            'newdeal',
        ];

        if (!in_array($slug, $availableProjects)) {
            throw $this->createNotFoundException();
        }

        $totalCommits   = null;
        $lastCommitDate = null;
        $githubProjects = [
            'alnilam',
            'athena',
            'carre-rose-films',
            'le-tag-parfait',
        ];

        if (in_array($slug, $githubProjects)) {
            $commits        = $github->getAllCommits($slug);
            $totalCommits   = count($commits);
            $lastCommitDate = (new \DateTime($commits[0]['commit']['committer']['date']))->setTimezone(new \DateTimeZone('Europe/Paris'));
        }

        return $this->render('front/projects/' . str_replace('-', '_', $slug) . '.html.twig', [
            'total_commits' => $totalCommits,
            'last_activity' => $lastCommitDate,
        ]);
    }
}
