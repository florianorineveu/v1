<?php

namespace App\Controller\Front;

use App\Entity\ShortenUrl;
use App\Form\ShortenUrlType;
use App\Services\UrlShortenerManager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/r", name="url_shortener_")
 */
class UrlShortenerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param EntityManagerInterface $em
     * @param Request                $request
     * @param SessionInterface       $session
     * @param UrlShortenerManager    $urlShortenerManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        EntityManagerInterface $em,
        Request $request,
        SessionInterface $session,
        UrlShortenerManager $urlShortenerManager
    ) {
        $shortenUrl = new ShortenUrl();
        $form       = $this->createForm(ShortenUrlType::class, $shortenUrl);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $shortenUrls     = $session->get('shorten_urls');
            $existShortenUrl = $em->getRepository(ShortenUrl::class)->findOneBy([
                'url'       => $shortenUrl->getUrl(),
                'enabled'   => true,
                'createdBy' => null,
            ]);

            if ($existShortenUrl) {
                array_unshift($shortenUrls, $existShortenUrl);
            } else {
                $urlShortenerManager->generateShortenUrl($shortenUrl);

                $em->persist($shortenUrl);
                $em->flush();

                array_unshift($shortenUrls, $shortenUrl);
            }

            $session->set('shorten_urls', $shortenUrls);

            return $this->redirectToRoute('url_shortener_index');
        }

        return $this->render('front/tools/url_shortener/index.html.twig', [
            'form'        => $form->createView(),
        ]);
    }

    /**
     * @Route("/{path}", name="redirect")
     *
     * @param EntityManagerInterface $em
     * @param string                 $path
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     */
    public function redirectToRealUrl(EntityManagerInterface $em, $path)
    {
        $shortenUrl = $em->getRepository(ShortenUrl::class)->findOneBy([
            'shortenUrl' => $path,
            'enabled'    => true,
        ]);

        if (!$shortenUrl) {
            throw $this->createNotFoundException();
        }

        $shortenUrl->setUsed($shortenUrl->getUsed() + 1);
        $em->flush();

        return $this->redirect($shortenUrl->getUrl());
    }
}
