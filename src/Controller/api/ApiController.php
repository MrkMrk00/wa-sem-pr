<?php

namespace App\Controller\api;

use App\Repository\BodyStyleRepository;
use App\Repository\EngineRepository;
use App\Repository\ManufacturerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api", name="api.")
 */
class ApiController extends AbstractController
{
    /**
     * @Route(path="/body_style", name="body_style", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function body_style(BodyStyleRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    /**
     * @Route(path="/manufacturer", name="manufacturer", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function manufacturer(ManufacturerRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    /**
     * @Route(path="/engine", name="engine", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function engine(EngineRepository $repo): JsonResponse
    {
        $engines = $repo->findAll();
        return $this->json(
            array_map(
                function($engine){
                    return ['id' => $engine->getId(), 'name' => (string)$engine];
                },
                $engines
            )
        );
    }
}