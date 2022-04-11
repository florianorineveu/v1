<?php

namespace App\Controller\AdminArea;

use App\Form\AdminArea\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'admin_security_')]
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('admin_area/security/login.html.twig', [
            'form' => $this->createForm(LoginType::class)->createView(),
        ]);
    }
}
