<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/artiste', name: 'app_admin_artiste_')]
class AdminArtisteController extends AbstractController
{
    #[Route('', name: 'lister')]
    public function lister(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('admin/artiste/index.html.twig', [
            "artistes" => $artisteRepository->findAll()
        ]);
    }

    #[Route("/add", name: "ajouter")]
    #[Route("/update/{id}", name: "modifier", requirements: ["id" => "\d+"])]
    public function editer(ArtisteRepository $artisteRepository, EntityManagerInterface $em, Request $request, int $id = null): Response
    {
        $artiste = $id ? $artisteRepository->find($id) : new Artiste();

        $form = $this->createForm(ArtisteType::class, $artiste);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($artiste);
            $em->flush();

            $this->addFlash("success", "L'artiste a bien été " . ($id ? "modifié" : "ajouté"));

            return $this->redirectToRoute("app_admin_artiste_lister");
        }

        return $this->render("admin/artiste/editer.html.twig", [
            "form" => $form
        ]);
    }

    #[Route("/delete/{id}", name: "supprimer", requirements: ["id" => "\d+"])]
    public function supprimer(int $id, EntityManagerInterface $em, ArtisteRepository $artisteRepository): Response
    {
        $artiste = $artisteRepository->find($id);

        if($artiste)
        {
            $em->remove($artiste);
            $em->flush();

            $this->addFlash("success", "L'artiste a bien été supprimé");
        }

        return $this->redirectToRoute("app_admin_artiste_lister");
    }

}
