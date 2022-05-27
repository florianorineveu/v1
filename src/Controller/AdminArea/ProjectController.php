<?php

namespace App\Controller\AdminArea;

use App\Entity\Project;
use App\Entity\TemplateBlock;
use App\Form\AdminArea\ProjectType;
use App\Model\TemplateBlock\ImageBlock;
use App\Repository\ProjectRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/project', name: 'admin_project_')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('admin_area/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository->add($project);
            return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_area/project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('admin_area/project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(FileUploader $fileUploader, Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        /*$imageBlock = new TemplateBlock();
        $imageBlock->setTitle('Image')->setPosition(10)->setConfiguration(new ImageBlock());
        $project->addBlock($imageBlock);*/
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($project->getBlocks() as $block) {
                // TODO: Make it recursive
                if ($block->getConfiguration() instanceof ImageBlock) {
                    /** @var UploadedFile $imageFile */
                    $imageFile = $form->get('blocks')[2]->get('configuration')->get('imageFile')->getData();
                    $newFilename = $fileUploader->upload($imageFile);

                    if (!$newFilename) {
                        $this->addFlash('error', 'Erreur upload.');
                        die();
                    }

                    $project->getBlocks()[2]->getConfiguration()->imagePath = $newFilename;
                }
            }

            $projectRepository->add($project);
            return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_area/project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $projectRepository->remove($project);
        }

        return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
    }
}