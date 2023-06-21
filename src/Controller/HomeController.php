<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('user', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Please select',
                'query_builder' => function (UserRepository $repository) {
                    return $repository->createQueryBuilder('u')
                        ->leftJoin('u.team', 't')
                        ->addSelect('t')
                    ;
                },
            ])
            ->getForm()
        ;

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
