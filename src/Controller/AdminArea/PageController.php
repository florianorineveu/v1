<?php

namespace App\Controller\AdminArea;

use App\Entity\Page;
use App\Form\AdminArea\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/page', name: 'admin_page_')]
class PageController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('admin_area/page/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, PageRepository $pageRepository): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageRepository->add($page);
            return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_area/page/new.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('admin_area/page/show.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Page $page, PageRepository $pageRepository): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageRepository->add($page);
            return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_area/page/edit.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Page $page, PageRepository $pageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $pageRepository->remove($page);
        }

        return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
    }
}