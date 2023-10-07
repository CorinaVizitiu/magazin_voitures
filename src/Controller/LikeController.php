<?php

namespace App\Controller;

use App\Entity\Voiture;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/voiture{id}', name: 'like_voiture')]
    public function like(Voiture $voiture , EntityManagerInterface $manager): Response
    { $user = $this->getUser();
        if($voiture->isLikedByUser($user)) {
            $voiture->removeLike($user);
            $manager->flush();
             
            return $this->json(['message' => 'The  like has been deleted '],);
        }
$voiture->addLike($user);
$manager->flush();
    return $this->json(['message' => 'The like has been added '],);
    }
}
