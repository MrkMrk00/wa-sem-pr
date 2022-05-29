<?php

namespace App\Controller;

use App\Form\BodyStyleType;
use App\Form\CarType;
use App\Form\EngineType;
use App\Form\ManufacturerType;
use App\Repository\CarRepository;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="index")
     */
    public function index(Connection $conn, CarRepository $repo): Response
    {
        $top_ten_cars = $repo->getTenTopRated($conn);
        return $this->render('pages/index.html.twig', [
            'active_link' => 'index',
            'top_ten_cars' => $top_ten_cars
        ]);
    }

    /**
     * @Route(path="/my_cars", name="my_cars")
     * @IsGranted("ROLE_USER")
     */
    public function myCars(): Response
    {
        $cars = $this->getUser()->getCars();

        return $this->render('pages/my_cars.html.twig', [
            'active_link' => 'my_cars',
            'cars' => $cars
        ]);
    }

    /**
     * @Route(path="/new_car", name="new_car")
     * @IsGranted("ROLE_USER")
     */
    public function newCar(Request $req, CarRepository $repo): Response
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
        $car_form->handleRequest($req);

        if ($car_form->isSubmitted() && $car_form->isValid()) {
            try {
                $data = $car_form->getData();
                $data->setAddedBy($this->getUser());

                $repo->add($data, true);
                $this->addFlash(
                    'success',
                    'Car successfully added: ' . $data
                );
                return $this->redirectToRoute('details.car', ['id' => $data->getId()]);
            } catch (\Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
                $car_form->addError(new FormError('Failed to add car to database'));
            }
        }

        return $this->render('pages/new_car.html.twig', [
            'active_link' => 'new_car',
            'manufacturer_form' => $manufacturer_form->createView(),
            'body_style_form' => $body_style_form->createView(),
            'engine_form' => $engine_form->createView(),
            'car_form' => $car_form->createView()
        ]);
    }
}
