<?php

namespace App\Controller\api;

use App\Form\ManufacturerType;
use App\Repository\ManufacturerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api", name="ajax_forms.")
 */
class AjaxFormsController extends AbstractController
{
    public function handleForm()
    {

    }

    /**
     * @Route(path="/manufacturer", name="manufacturer", methods={"POST"})
     */
    public function newManufacturer(Request $req, ManufacturerRepository $repo): Response
    {
        $form = $this->createForm(ManufacturerType::class);
        $form->handleRequest($req);
        $manufacturer = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->add($manufacturer, true);
            return $this->json($manufacturer, 201);
        }

        return $this->json($form->getErrors(true), 400);
    }
}