<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/departement', name: 'app_admin_departement_')]
class AdminDepartementController extends AbstractController
{
    #[Route('', name: 'lister')]
    public function index(DepartementRepository $departementRepository): Response
    {
        return $this->render('admin/departement/index.html.twig', [
            "departements" => $departementRepository->findAll()
        ]);
    }

    #[Route("/add", name: "ajouter")]
    #[Route("/update/{id}", name: "modifier", requirements: ["id" => "\d+"])]
    public function editer(DepartementRepository $departementRepository, EntityManagerInterface $em, Request $request, int $id = null): Response
    {
        $departement = $id ? $departementRepository->find($id) : new Departement();

        $form = $this->createForm(DepartementType::class, $departement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($departement);
            $em->flush();

            $this->addFlash("success", "Le département a bien été " . ($id ? "modifié" : "ajouté"));

            return $this->redirectToRoute("app_admin_departement_lister");
        }

        return $this->render("admin/departement/editer.html.twig", [
            "form" => $form
        ]);
    }

    #[Route("/delete/{id}", name: "supprimer", requirements: ["id" => "\d+"])]
    public function supprimer(int $id, EntityManagerInterface $em, DepartementRepository $departementRepository): Response
    {
        $departement = $departementRepository->find($id);

        if($departement)
        {
            $em->remove($departement);
            $em->flush();

            $this->addFlash("success", "le département a bien été supprimé");
        }

        return $this->redirectToRoute("app_admin_departement_lister");
    }

}
