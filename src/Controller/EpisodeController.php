<?php 

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController extends AbstractController
{
    /**
     * @Route("/episode/list", name="episode_list") 
     */
    public function list(EpisodeRepository $episodeRepository,PaginatorInterface $paginator, Request $request)
    {
        
        $episodes = $paginator->paginate(
            $episodeRepository->findAll(),
            $request->query->getInt('page',1),
            3
        );

        return $this->render("episode/list.html.twig",[
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/episode/show/{id}", name="episode_show")
     */
    public function show(int $id, EpisodeRepository $episodeRepository, Request $request, EntityManagerInterface $em)
    {
        $episode = $episodeRepository->find($id);

        if(!$episode)
        {
            $this->addFlash("danger","L'episode est introuvable.");
            return $this->redirectToRoute("episode_list");
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setEpisode($episode);

            $user = $this->getUser();

            $comment->setUser($user);

            $em->persist($comment);

            $em->flush();

            $this->addFlash("success","Le commentaire a bien été enregistré.");

            return $this->redirectToRoute("episode_show",['id' => $id]);

        }

        return $this->render("episode/show.html.twig",[
            'episode' => $episode,
            'form' => $form->createView()
        ]);
    }
}
