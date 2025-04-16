<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Groupe;  // importe le grp si non erreur



class AdminSecuController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(InscriptionType::class,$utilisateur);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute("app_welcome");
        }

        return $this->render('admin_secu/inscription.html.twig',[
            "form" => $form->createView(),
        ]);
    }

    #[Route('/init-groupes', name: 'init_groupes')]
    public function initGroupes(EntityManagerInterface $em): Response
    {
        $noms = ['Chef', 'Second', 'Employé', 'Stagiaire'];

        foreach ($noms as $nom) {
            $groupe = new Groupe();
            $groupe->setNom($nom);
            $groupe->setDescription("Rôle de type $nom");
            $groupe->setEstpublic(true);
            $groupe->setDatecreation(new \DateTime());
            $em->persist($groupe);
        }

        $em->flush();

        return new Response('Groupes ajoutés avec succès ✅');
    }
}
