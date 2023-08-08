<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloController extends AbstractController
{
    #[Route('/hello/{name}', name: 'app_hello_index', requirements: ['name' => '[a-zA-Z- ]+'], defaults: ['name' => 'World'])]
    public function index(string $name, #[Autowire(param: 'app.sf_version')] string $sfVersion): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            dump($sfVersion);
        }

        return $this->render('hello/subtitle.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
