<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UserAutocompleteField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'home_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createFormBuilder()
            ->add('user', UserAutocompleteField::class)
            ->getForm()
        ;

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
