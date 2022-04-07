<?php

namespace App\Controller;


use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractController
{
    #[Route('/{slug}', name: 'home', requirements: ['slug' => '.+'], defaults: ['slug' => null])]
    public function page(Page $page)
    {
        return $this->render('front/index.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/aleatoire", name="random")
     */
    public function random(
        ProjectRepository $projectRepository,
        Request $request,
        RouterInterface $router
    ) {
        $refererUrl   = $request->headers->get('referer');
        $referer      = parse_url($refererUrl, PHP_URL_PATH);
        $refererRoute = $router->match($referer)['_route'];

        $availableRoutes = [
            'about',
            'uses',
            //'case_study',
            'project_index',
            'project',
            'home',
        ];

        $destination = $availableRoutes[array_rand($availableRoutes)];

        if (!in_array($destination, ['case_study', 'project', $refererRoute])) {
            return $this->redirectToRoute($destination);
        }

        if ('case_study' === $destination) {
            $availableCaseStudies = [
                'carre-rose',
                'wiztopic',
            ];

            return $this->redirectToRoute($destination, [
                'slug' => $availableCaseStudies[array_rand($availableCaseStudies)]
            ]);
        }

        if ('project' === $destination) {
            $availableProjects = $projectRepository->findBy([
                'enabled' => true,
            ]);

            return $this->redirectToRoute($destination, [
                'slug' => $availableProjects[array_rand($availableProjects)]->getSlug(),
            ]);
        }

        return $this->redirectToRoute('random');
    }

    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function sitemap(ProjectRepository $projectRepository)
    {
        return new Response(
            $this->renderView('front/sitemap.xml.twig', [
                'projects' => $projectRepository->findBy([
                    'enabled' => true,
                ])
            ]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/xml']
        );
    }
}
