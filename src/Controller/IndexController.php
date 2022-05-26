<?php

namespace App\Controller;

use App\Form\BodyStyleType;
use App\Form\CarType;
use App\Form\EngineType;
use App\Form\ManufacturerType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="index")
     */
    public function index(Request $req, ManagerRegistry $registry): Response
    {
        return $this->render('index.html.twig', [
            'active_link' => 'index'
        ]);
    }

    /**
     * @Route(path="/new_car", name="new_car")
     * @IsGranted("ROLE_USER")
     */
    public function newCar(): Response
    {
        $manufacturer_form = $this->createForm(ManufacturerType::class, null, [
            'attr' => ['ajax-form' => '']
        ]);
        $body_style_form = $this->createForm(BodyStyleType::class, null, [
            'attr' => ['ajax-form' => '']
        ]);
        $engine_form = $this->createForm(EngineType::class, null, [
            'attr' => ['ajax-form' => '']
        ]);
        $car_form = $this->createForm(CarType::class);

        return $this->render('new_car.html.twig', [
            'active_link' => 'new_car',
            'manufacturer_form' => $manufacturer_form->createView(),
            'body_style_form' => $body_style_form->createView(),
            'engine_form' => $engine_form->createView(),
            'car_form' => $car_form->createView()
        ]);
    }
}
