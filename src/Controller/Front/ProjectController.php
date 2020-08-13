<?php

namespace App\Controller\Front;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Criteria;
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
    public function project(ProjectRepository $projectRepository, $slug)
    {
        $project = $projectRepository->findOneBy([
            'enabled' => true,
            'slug'    => $slug,
        ]);

        if (!$project) {
            throw $this->createNotFoundException();
        }

        return $this->render('front/projects/show.html.twig', [
            'project' => $project,
        ]);
    }
}
