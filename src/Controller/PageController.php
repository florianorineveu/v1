<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/{slug}', name: 'page_show', requirements: ['slug' => '.+'], defaults: ['slug' => null], priority: -10)]
    public function __invoke(Page $page)
    {
        return $this->render('page/templates/' . $page->getTemplate() ?: 'default.html.twig', [
            'page' => $page,
        ]);
    }
}