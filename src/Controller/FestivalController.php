<?php

namespace App\Controller;

use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/festival', name: "app_festival_")]
class FestivalController extends AbstractController
{
    #[Route('/{id}', name: 'voir', requirements: ["id" => "\d+"])]
    public function voir(int $id, FestivalRepository $festivalRepository): Response
    {
        $festival = $festivalRepository->find($id);

        if(!$festival)
            throw $this->createNotFoundException("Le festival n'existe pas");

        return $this->render('festival/voirFestival.html.twig', [
            "festival" => $festival
        ]);
    }
}
