<?php

namespace App\Controller\api;

use App\Form\BodyStyleType;
use App\Form\EngineType;
use App\Form\ManufacturerType;
use App\Repository\BodyStyleRepository;
use App\Repository\EngineRepository;
use App\Repository\ManufacturerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api", name="ajax_forms.")
 */
class AjaxFormsController extends AbstractController
{
    /**
     * @Route(path="/manufacturer", name="manufacturer", methods={"POST"})
     */
    public function newManufacturer(Request $req, ManufacturerRepository $repo): JsonResponse
    {
        $form = $this->createForm(ManufacturerType::class);
        $form->handleRequest($req);
        $data = $form->getData();

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->json($form->getErrors(true), 400);

        $existing = $repo->findOneBy(['name' => $data->getName()]);
        if ($existing) {
            $form->addError(new FormError("This manufacturer already exists"));
            return $this->json($form->getErrors(true), 409);
        }

        $repo->add($data, true);
        return $this->json($data, 201);
    }

    /**
     * @Route(path="/body_style", name="body_style", methods={"POST"})
     */
    public function newBodyStyle(Request $req, BodyStyleRepository $repo): JsonResponse
    {
        $form = $this->createForm(BodyStyleType::class);
        $form->handleRequest($req);
        $data = $form->getData();

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->json($form->getErrors(true), 400);

        $existing = $repo->findOneBy(['name' => $data->getName()]);
        if ($existing) {
            $form->addError(new FormError("This body style already exists"));
            return $this->json($form->getErrors(true), 409);
        }

        $repo->add($data, true);
        return $this->json($data, 201);
    }

    /**
     * @Route(path="/engine", name="engine", methods={"POST"})
     */
    public function newEngine(Request $req, EngineRepository $repo): JsonResponse
    {
        $form = $this->createForm(EngineType::class);
        $form->handleRequest($req);
        $data = $form->getData();

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->json($form->getErrors(true), 400);

//        $existing = $repo->findOneBy(['name' => $data->getName()]);
//        if ($existing) {
//            $form->addError(new FormError("This body style already exists"));
//            return $this->json($form->getErrors(true), 409);
//        }

        $repo->add($data, true);
        return $this->json($data, 201);
    }
}