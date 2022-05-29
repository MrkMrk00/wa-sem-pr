<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarImageType;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/edit", name="edit.")
 */
class ManipulationController extends AbstractController
{
    /**
     * @Route(path="/car/{id}", name="car")
     * @IsGranted("ROLE_USER")
     */
    public function editCar(
        int             $id,
        Request         $req,
        CarRepository   $repo,
        ImageRepository $image_repo,
        FileUploader    $file_uploader
    ): Response
    {
        $car = $repo->find($id);
        if (!$car) {
            $this->addFlash('error', 'That car does not exist!');
            return $this->redirectToRoute('index');
        }

        if ($car->getAddedBy() !== $this->getUser()) {
            $this->addFlash('error', 'That is not your car!');
            return $this->redirectToRoute('index');
        }

        $images_form = $this->createForm(CarImageType::class);
        $images_form->handleRequest($req);
        $this->handleUploadImages($car, $images_form, $image_repo, $file_uploader);

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $repo->add($form->getData(), true);
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occured: ' . $e->getMessage());
                return $this->redirectToRoute('index');
            }
            $this->addFlash('success', 'Car edited');
            return $this->redirectToRoute('details.car', [
                'id' => $form->getData()->getId()
            ]);
        }

        return $this->render('pages/edit_car.html.twig', [
            'car_form' => $form->createView(),
            'images_form' => $images_form->createView(),
            'car' => $car
        ]);
    }

    public function handleUploadImages(
        Car             $car,
        FormInterface   $form,
        ImageRepository $repo,
        FileUploader    $uploader): void
    {
        if (!$form->isSubmitted() || !$form->isValid())
            return;

        $images = $form->getData()['images'];

    }
}