<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\EngineRepository;
use App\Repository\ManufacturerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="details.")
 */
class DetailsController extends AbstractController
{
    /**
     * @Route(path="/car/{id}", name="car")
     */
    public function car(int $id, CarRepository $repo): Response
    {
        $car = $repo->find($id);
        return $this->render('pages/details/car.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route(path="/manufacturer/{id}", name="manufacturer")
     */
    public function manufacturer(int $id, ManufacturerRepository $repo): Response
    {
        $manufacturer = $repo->find($id);
        return $this->render('pages/details/manufacturer.html.twig', [
            'manufacturer' => $manufacturer
        ]);
    }

    /**
     * @Route(path="/engine/{id}", name="engine")
     */
    public function engine(int $id, EngineRepository $repo): Response
    {
        $engine = $repo->find($id);
        return $this->render('pages/details/engine.html.twig', [
            'engine' => $engine
        ]);
    }
}
