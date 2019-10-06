<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="io_index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->redirectToRoute('home');
    }
}
