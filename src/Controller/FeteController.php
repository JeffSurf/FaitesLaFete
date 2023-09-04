<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeteController extends AbstractController
{
    public function index(FestivalRepository $festivalRepository, DepartementRepository $departementRepository): Response
    {
        return $this->render('accueil.html.twig', [
            "festivals" => $festivalRepository->findBy(array(), array("id" => "DESC"), 3),
            "departements" => $departementRepository->findAll()
        ]);
    }
}
