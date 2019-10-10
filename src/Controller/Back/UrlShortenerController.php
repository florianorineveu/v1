<?php

namespace App\Controller\Back;

use App\Entity\ShortenUrl;
use App\Form\ShortenUrlType;
use App\Services\UrlShortenerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/r", name="back_url_shortener_")
 */
class UrlShortenerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EntityManagerInterface $em, Request $request, UrlShortenerManager $urlShortenerManager)
    {
        $shortenUrl = new ShortenUrl();

        $form = $this->createForm(ShortenUrlType::class, $shortenUrl, ['full' => true]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $urlShortenerManager->generateShortenUrl($shortenUrl);
            $shortenUrl->setCreatedBy($this->getUser());

            $em->persist($shortenUrl);
            $em->flush();

            $this->addFlash('success', 'gg');

            return $this->redirectToRoute('back_url_shortener_index');
        }

        return $this->render('back/shorten_url/index.html.twig', [
            'form'         => $form->createView(),
            'shorten_url'  => $shortenUrl,
            'shorten_urls' => $em->getRepository(ShortenUrl::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{shortenUrlId}/toggle", name="toggle")
     *
     * @param EntityManagerInterface $em
     * @param int                    $shortenUrlId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggleStatus(EntityManagerInterface $em, $shortenUrlId)
    {
        $shortenUrl = $em->getRepository(ShortenUrl::class)->find($shortenUrlId);

        if ($shortenUrl) {
            $shortenUrl->setEnabled(!$shortenUrl->getEnabled());
            $em->flush();

            $this->addFlash('success', 'GG');
        } else {
            $this->addFlash('error', 'nope');
        }

        return $this->redirectToRoute('back_url_shortener_index');
    }

    /**
     * @Route("/{shortenUrlId}/remove", name="remove")
     *
     * @param EntityManagerInterface $em
     * @param int                    $shortenUrlId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(EntityManagerInterface $em, $shortenUrlId)
    {
        $shortenUrl = $em->getRepository(ShortenUrl::class)->find($shortenUrlId);

        if ($shortenUrl) {
            $em->remove($shortenUrl);
            $em->flush();

            $this->addFlash('success', 'GG');
        } else {
            $this->addFlash('error', 'nope');
        }

        return $this->redirectToRoute('back_url_shortener_index');
    }
}
