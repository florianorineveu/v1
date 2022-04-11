<?php

namespace App\Controller\AdminArea;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pages', 'admin_page_')]
class PageController extends AbstractController
{
    #[Route('/', 'index')]
    public function index(PageRepository $pageRepository)
    {
        return $this->render('admin_area/pages/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }
}