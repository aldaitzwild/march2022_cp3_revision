<?php

namespace App\Service;

use App\Entity\Coordinate;
use App\Repository\CoordinateRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class GameService 
{

    private CoordinateRepository $coordinateRepository;
    private RequestStack $requestStack;


    public function __construct(
        CoordinateRepository $coordinateRepository,
        RequestStack $requestStack
        )
    {
        $this->coordinateRepository = $coordinateRepository;
        $this->requestStack = $requestStack;
    }

    public function bombACoordinatePlayer(int $x, int $y) {
        $coordinate = $this->coordinateRepository->findOneBy(['x' => $x, 'y' => $y]);

        if($coordinate) {
            $coordinate->setHasBeenBombed(true);
            $this->coordinateRepository->add($coordinate, true);
            $boat = $coordinate->getBoat();

            if($boat->isSunk()) {
                $this->requestStack->getSession()->getFlashBag()->add('success', 'Bateau coulé !!!');
            }

            $this->requestStack->getSession()->getFlashBag()->add('success', 'Bateau touché !!!');
    
            } else {
            $coordinate = new Coordinate();
            $coordinate->setX($x);
            $coordinate->setY($y);
            $coordinate->setHasBeenBombed(true);
            $this->coordinateRepository->add($coordinate, true);

            $this->requestStack->getSession()->getFlashBag()->add('warning', 'coup manqué ...');
        }
    }



    public function bombACoordinateCPU(): bool
    {


        $x = rand(0, 9);
        $y = rand(0, 9);
        $coordinate = $this->coordinateRepository->findOneBy(['x' => $x, 'y' => $y]);

        if($coordinate && $coordinate->isHasBeenBombed())
            return $this->bombACoordinateCPU();


        if($coordinate) {
            $coordinate->setHasBeenBombed(true);
            $this->coordinateRepository->add($coordinate, true);
            $boat = $coordinate->getBoat();
            } else {
            $coordinate = new Coordinate();
            $coordinate->setX($x);
            $coordinate->setY($y);
            $coordinate->setHasBeenBombed(true);
            $this->coordinateRepository->add($coordinate, true);
        }

        return true;
    }
}