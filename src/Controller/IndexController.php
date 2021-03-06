<?php

namespace App\Controller;

use App\Entity\CarReview;
use App\Form\BodyStyleType;
use App\Form\CarType;
use App\Form\EngineType;
use App\Form\ManufacturerType;
use App\Form\RatingFormType;
use App\Repository\CarRepository;
use App\Repository\CarReviewRepository;
use App\Repository\ImageRepository;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route(path="/", name="index")
     */
    public function index(CarRepository $car_repo, ImageRepository $image_repo): Response
    {
        $cars_raw = $car_repo->getTenTopRated();
        $top_ten_cars = $image_repo->attachImagesOnCars($cars_raw);

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
     * @Route(path="/rate", name="rate_cars")
     * @IsGranted("ROLE_USER")
     */
    public function rateCars(Request $req, CarRepository $repo, CarReviewRepository $review_repo): Response
    {
        $form = $this->createForm(RatingFormType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $review_repo->add($data, true);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to persist review ' . $e->getMessage()
                );
                return $this->redirectToRoute('rate_cars');
            }
            $this->addFlash(
                'success',
                'Reviewed successfully'
            );
            return $this->redirectToRoute('rate_cars');
        }

        $car = $repo->getCarForRating($this->getUser());
        $review = (new CarReview())
            ->setUser($this->getUser())
            ->setCar($car)
            ->setTimestamp(new \DateTime('now'));

        $form->setData($review);

        if (!$car) {
            return $this->render('render_it.html.twig', [
                'it' => 'No car available for rating!',
                'active_link' => 'rate_cars'
            ]);
        }


        return $this->render('pages/rate_cars.html.twig', [
            'active_link' => 'rate_cars',
            'car' => $car,
            'form' => $form->createView()
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
