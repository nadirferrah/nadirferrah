<?php 

namespace App\Controller\Admin;

use App\Entity\Episode;
use App\Form\EpisodeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController extends AbstractController
{
    /**
     * @Route("/admin/episode/create",name="admin_episode_create")
     */
    public function create(Request $request,EntityManagerInterface $em)
    {
        //J'ai besoin de creer une instance de la classe Episode (un objet Episode)
        $episode = new Episode();

        //J'ai besoin de mon formulaire, a qui je vais donner l'instance de ma classe
        $form = $this->createForm(EpisodeType::class, $episode);

        //Je demande a mon formulaire de checker , de s'occuper la requete
        $form->handleRequest($request);

        //Si t as bien vu que il y avait des donnees à traiter dans la requete
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($episode);

            $em->flush();

            $this->addFlash("success","L'épisode " . $episode->getName() . " a bien été ajouté.");

            return $this->redirectToRoute("admin_episode_create");
        }

        return $this->render("admin/episode/create.html.twig",[
            'form' => $form->createView()
        ]);
    }
}