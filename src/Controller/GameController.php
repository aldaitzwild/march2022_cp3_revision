<?php

namespace App\Controller;

use App\Entity\Coordinate;
use App\Repository\CoordinateRepository;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'welcome_game')]
    #[Route('/game', name: 'app_game')]
    public function index(CoordinateRepository $coordinateRepository): Response
    {
        $coordinates = $coordinateRepository->findAll();
        $game = [];

        foreach($coordinates as $coordinate) {
            $game[$coordinate->getX()][$coordinate->getY()] = $coordinate;
        }

        return $this->render('game/index.html.twig', ['game' => $game]);
    }

    #[Route('/bombify/{x<\d+>}/{y<\d+>}', name: 'bomb_boat')]
    public function bomb(
        int $x, 
        int $y, 
        CoordinateRepository $coordinateRepository,
        GameService $gameService
        ): Response
    {
        
        $gameService->bombACoordinatePlayer($x, $y);

        $gameService->bombACoordinateCPU();

        return $this->redirectToRoute('app_game');
    }
}
