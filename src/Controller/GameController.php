<?php

namespace App\Controller;

use App\Repository\CoordinateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'welcome_game')]
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        return $this->render('game/index.html.twig');
    }

    #[Route('/bombify/{x<\d+>}/{y<\d+>}', name: 'bomb_boat')]
    public function bomb(
        int $x, 
        int $y, 
        CoordinateRepository $coordinateRepository): Response
    {
        $coordinate = $coordinateRepository->findOneBy(['x' => $x, 'y' => $y]);

        if($coordinate) {
            $this->addFlash('success', 'bateau touché !');
        } else {
            $this->addFlash('warning', 'coup manqué...');
        }

        return $this->redirectToRoute('app_game');
    }
}
