<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JardiFreeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/accountcompleted', name: "account_completed")]
    public function accountComplete(): Response
    {
        $user = $this->getUser();
        return $this->render('pages/account_complete.html.twig',[
            'user' => $user
        ]);
    }


    #[IsGranted("ROLE_USER")]
    #[Route('/profil', name: "profil")]
    public function profil(): Response
    {
        return$this->render('pages/profil.html.twig');
    }




}
