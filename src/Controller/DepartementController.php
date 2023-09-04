<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/departement', name: 'app_departement_')]
class DepartementController extends AbstractController
{
    #[Route('/{id}', name: 'voir',  requirements: ["id" => "\d+"])]
    public function voir(int $id, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($id);

        if(!$departement)
            throw $this->createNotFoundException("Le département recherché n'existe pas");

        return $this->render('departement/voirDep.html.twig', [
            "departement" => $departement
        ]);
    }
}
