<?php 

namespace App\Controller\Admin;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/admin/episode/list",name="admin_episode_list")
     */
    public function list(EpisodeRepository $episodeRepository)
    {
        //Faire appel à la base de donnees et de recuperer la liste des episodes
        $episodes = $episodeRepository->findAll();

        //J envoie la vue les episodes que je viens de recuperer
        return $this->render("admin/episode/list.html.twig",[
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/admin/episode/show/{id}",name="admin_episode_show")
     */
    public function show(int $id, EpisodeRepository $episodeRepository): Response
    {
        $episode = $episodeRepository->find($id);

        //Si l'episode ne peut pas etre trouve en bdd
        //alors je redirige vers la liste des episodes
        //Et j'affiche un message flash

        if(!$episode)
        {
            $this->addFlash("danger","L'episode est introuvable.");
            return $this->redirectToRoute("admin_episode_list");
        }

        return $this->render("admin/episode/show.html.twig",[
            'episode' => $episode
        ]);

    }

    /**
     * @Route("/admin/episode/delete/{id}",name="admin_episode_delete")
     */
    public function delete(int $id,EpisodeRepository $episodeRepository, EntityManagerInterface $em) : Response
    {
        $episode = $episodeRepository->find($id);

        if(!$episode)
        {
            $this->addFlash("danger","Cet épisode est introuvable");
            return $this->redirectToRoute("admin_episode_list");
        }

        $em->remove($episode);

        $em->flush();

        $this->addFlash("success","L'episode a bien été supprimé.");

        return $this->redirectToRoute("admin_episode_list");
    }
}