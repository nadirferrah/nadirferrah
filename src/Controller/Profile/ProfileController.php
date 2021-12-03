<?php 

namespace App\Controller\Profile;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/show", name="profile_show")
     */
    public function show()
    {
        return $this->render("profile/show.html.twig");
    }

}

