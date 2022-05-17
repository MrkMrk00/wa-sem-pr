<?php

namespace App\Controller;

use App\Form\BodyStyleType;
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
        $body_style_form = $this->createForm(BodyStyleType::class);
        $manufacturer_form = $this->createForm(ManufacturerType::class);

        return $this->render('new_car.html.twig', [
            'active_link' => 'new_car',
            'new_body_style_form' => $body_style_form->createView(),
            'manufacturer_form' => $manufacturer_form->createView()
        ]);
    }
}
