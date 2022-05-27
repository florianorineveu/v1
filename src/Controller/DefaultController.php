<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Project;
use App\Entity\TemplateBlock;
use App\Model\TemplateBlock\TextBlock;
use App\Repository\PageRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(/*EntityManagerInterface $em, */ProjectRepository $projectRepository, Request $request)
    {
        /*$blockConfiguration = new TextBlock();
        $blockConfiguration->content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis consectetur corporis delectus dolor doloremque id impedit ipsam ipsum labore, laudantium magnam magni maiores nulla officiis pariatur quae tenetur voluptatem voluptates?';
        $block   = (new TemplateBlock())
            ->setTitle('Contenu')
            ->setPosition(10)
            ->setConfiguration($blockConfiguration)
        ;
        $project = $em->getRepository(Project::class)->findOneBy([]);
        $project
            ->addBlock($block)
        ;

        $em->flush();*/
        $alnilamBirthday = new \DateTime('2020-01-31 18:42');
        $dateDiff        = date_diff($alnilamBirthday, new \DateTime());

        return $this->render('page/home.html.twig', [
            'birthday_count_days' => $dateDiff->days,
            'last_projects'       => $projectRepository->findBy([
                'enabled' => true,
            ]),
        ]);
    }

    #[Route('/aleatoire', name: 'random')]
    public function random(
        PageRepository $pageRepository,
        ProjectRepository $projectRepository,
        Request $request,
        RouterInterface $router
    ) {
        $refererUrl   = $request->headers->get('referer');
        $referer      = parse_url($refererUrl, PHP_URL_PATH);
        $refererRoute = $router->match($referer)['_route'];

        $availableRoutes = [
            'page',
            'project_index',
            'project',
            'home',
        ];

        $destination = $availableRoutes[array_rand($availableRoutes)];

        if (!in_array($destination, ['page', 'project', $refererRoute])) {
            return $this->redirectToRoute($destination);
        }

        ${'available' . ucfirst($destination) . 's'} = ${$destination . 'Repository'}->findBy([
            'enabled' => true,
        ]);

        return $this->redirectToRoute($destination, [
            'slug' => ${'available' . ucfirst($destination) . 's'}[array_rand(${'available' . ucfirst($destination) . 's'})]->getSlug(),
        ]);
    }

    #[Route('/sitemap.xml', name: 'sitemap')]
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
