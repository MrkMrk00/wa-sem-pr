<?php

namespace App\Controller;

use App\Form\CarSearchFormType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/cars", name="car_filtration.")
 */
class FiltrationController extends AbstractController
{
    /**
     * @Route(path="/", name="index")
     */
    public function filtration(Request $req, CarRepository $repo): Response
    {
        $search_form = $this->createForm(CarSearchFormType::class);
        $search_form->handleRequest($req);

        $cars = null;
        if ($search_form->isSubmitted() && $search_form->isValid()) {
            $cars = $repo->search($search_form->getData());
        }
        else {
            $cars = $repo->findAll();
        }

        return $this->render('pages/filtration.html.twig', [
            'active_link' => 'filtration',
            'search_form' => $search_form->createView(),
            'cars' => $cars
        ]);
    }
}