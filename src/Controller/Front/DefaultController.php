<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $alnilamBirthday = new \DateTime('2020-01-31 18:42');
        $now             = new \DateTime();
        $dateDiff        = date_diff($alnilamBirthday, $now);

        return $this->render('front/index.html.twig', [
            'birthday_count_days' => $dateDiff->days,
        ]);
    }

    /**
     * @Route("/florian", name="about")
     */
    public function about()
    {
        return $this->render('front/about.html.twig');
    }

    /**
     * @Route("/uses", name="uses")
     */
    public function uses()
    {
        return $this->render('front/uses.html.twig');
    }

    /**
     * @Route("/random", name="random")
     */
    public function random(Request $request, RouterInterface $router)
    {
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
            $availableProjects = [
                'athena',
                'alnilam',
            ];

            return $this->redirectToRoute($destination, [
                'slug' => $availableProjects[array_rand($availableProjects)]
            ]);
        }

        return $this->redirectToRoute('random');
    }
}
