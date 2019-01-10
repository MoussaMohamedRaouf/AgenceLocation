<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /*
    * @var Environment
    */

    private $twig;

    public function __construct(Environment $twig){
      $this->twig = $twig;
    }

    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository): Response
    {
    $properties = $repository->findAll();
    return $this->render('pages/home.html.twig', [
      'properties' => $properties
    ]);
  }
}
