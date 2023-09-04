<?php

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route("/admin/festival", name: "app_admin_festival_")]
class AdminFestivalController extends AbstractController
{
    #[Route('', name: 'lister')]
    public function lister(FestivalRepository $festivalRepository): Response
    {
        return $this->render('admin/festival/index.html.twig', [
            "festivals" => $festivalRepository->findAll()
        ]);
    }

    #[Route("/add", name: "ajouter")]
    #[Route("/update/{id}", name: "modifier", requirements: ["id" => "\d+"])]
    public function editer(FestivalRepository $festivalRepository, EntityManagerInterface $em, Request $request, SluggerInterface $slugger, int $id = null): Response
    {
        $festival = $id ? $festivalRepository->find($id) : new Festival();

        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $afficheFile */
            $afficheFile = $form->get('affiche')->getData();

            if($afficheFile)
            {
                $originalFilename = pathinfo($afficheFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $afficheFile->guessExtension();

                try {
                    $afficheFile->move(
                        $this->getParameter("affiches_directory"),
                        $newFilename
                    );
                } catch (FileException $exception)
                {

                }

                $festival->setAffiche($newFilename);
            }

            $em->persist($festival);
            $em->flush();

            $this->addFlash("success", "Le festival a bien été " . ($id ? "modifié" : "ajouté"));

            return $this->redirectToRoute("app_admin_festival_lister");
        }

        return $this->render("admin/festival/editer.html.twig", [
            "form" => $form
        ]);
    }

    #[Route("/delete/{id}", name: "supprimer", requirements: ["id" => "\d+"])]
    public function supprimer(int $id, EntityManagerInterface $em, FestivalRepository $festivalRepository): Response
    {
        $festival = $festivalRepository->find($id);

        if($festival)
        {
            $em->remove($festival);
            $em->flush();

            $this->addFlash("success", "le festival a bien été supprimé");
        }

        return $this->redirectToRoute("app_admin_festival_lister");
    }
}
