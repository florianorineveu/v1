<?php

namespace App\Controller\Front;

use Github\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="legacy")
     */
    public function legacy()
    {
        return $this->render('front/legacy.html.twig', []);
    }

    /**
     * @Route("/index", name="home")
     */
    public function index()
    {
        $alnilamBirthday = new \DateTime('2020-01-31 18:42');
        $now             = new \DateTime();
        $dateDiff        = date_diff($alnilamBirthday, $now);

        /*$client = new Client();
        $client->authenticate(
            getenv('GITHUB_SECRET'),
            null,
            Client::AUTH_URL_TOKEN
        );*/

        //$repositories = $client->api('current_user')->repositories('fnev-eu');
        //$repositories = $client->currentUser()->repositories();;

        /*$commits = $client->api('repo')->commits()->setPerPage(100)->all('fnev-eu', 'Carre-Rose-Films', array('sha' => 'master'));


        if ($commits[99]) {
            $lastCommit = $commits[99];
            unset($commits[99]);
            $commits = array_merge($commits, $client->api('repo')->commits()->setPerPage(100)->all('fnev-eu', 'Carre-Rose-Films', array('sha' => $lastCommit['sha'])));
        }

        dump($commits);*/

        /*$lastCommit = $client->api('repo')->commits()->show('fnev-eu', 'Carre-Rose-Films', 'a8f2def7a25d30bca4465bad10fbc8ca2e10fc8a');

        dump(
            (new \DateTime($lastCommit['commit']['committer']['date']))->setTimezone(new \DateTimeZone('Europe/Paris'))
        );

        dump(new \DateTime());

        die();*/

        return $this->render('front/index.html.twig', [
            'birthday_count_days' => $dateDiff->days,
        ]);
    }

    /**
     * @Route("/projets/{slug}", name="project")
     */
    public function project($slug)
    {
        $availableProjects = [
            'carre-rose',
            'le-tag-parfait',
            'newdeal',
            'wiztopic',
        ];

        if (!in_array($slug, $availableProjects)) {
            throw $this->createNotFoundException();
        }

        return $this->render('front/projects/' . str_replace('-', '_', $slug) . '.html.twig');
    }

    /**
     * @Route("/a-propos", name="about")
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
}
