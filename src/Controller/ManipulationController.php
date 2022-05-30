<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Image;
use App\Form\CarImageType;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
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
     * @Route(path="/car/{id}", name="car", methods={"GET"})
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
            'active_link' => 'my_cars',
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
        foreach ($images as $image) {
            $fileName = $uploader->upload($image);
            if (!$fileName) {
                $this->addFlash(
                    'error',
                    'Failed to upload image ' . htmlspecialchars($image->getClientOriginalName())
                );
                return;
            }
            $imageObject = (new Image())
                ->setFileName($fileName)
                ->setCar($car);

            try {
                $repo->add($imageObject, true);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Failed to persist image to database: ' . $e->getMessage()
                );
                return;
            }
        }
        $this->addFlash(
            'success',
            'Images uploaded successfully'
        );
    }

    /**
     * @Route(path="/delete_image", name="delete_image", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteImage(Request $req, ImageRepository $repo): Response
    {
        $image_id = (int)$req->request->get('id');
        $image_object = $repo->find($image_id);

        if (!$image_object) {
            $this->addFlash(
                'error',
                'Image not found!'
            );
            return $this->redirectToRoute('index');
        }

        if ($this->getUser() !== $image_object->getCar()->getAddedBy()) {
            $this->addFlash(
                'error',
                'The image is not of your car!'
            );
            $this->redirectToRoute('index');
        }

        $fs = new Filesystem();
        $image_path = $this->getParameter('images_dir') . '/' . $image_object->getFileName();

        try {
            $repo->remove($image_object, true);
            $fs->remove($image_path);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Failed to delete image: ' . $e->getMessage()
            );
            return $this->redirectToRoute('edit.car', [
                'id' => $image_object->getCar()->getId()
            ]);
        }

        $this->addFlash(
            'success',
            'Successfully removed image!'
        );
        return $this->redirectToRoute('edit.car', [
            'id' => $image_object->getCar()->getId()
        ]);
    }
}