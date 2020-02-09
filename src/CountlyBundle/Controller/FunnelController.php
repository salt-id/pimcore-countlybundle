<?php
/**
 * Created by PhpStorm.
 * User: Yulius Ardian Febrianto <yuliusardin@gmail.com>
 * Date: 09/02/2020
 * Time: 14:54
 */

namespace SaltId\CountlyBundle\Controller;

use SaltId\CountlyBundle\Countly\Api\v1\Funnels\Funnels;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/funnels")
 */
class FunnelController extends AbstractController
{
    /** @var Funnels $funnels */
    private $funnels;

    public function __construct()
    {
        $this->funnels = new Funnels();
    }

    /**
     * @Route("/")
     * @return JsonResponse
     */
    public function defaultAction()
    {
        $getFunnels = $this->funnels->getFunnels() ?? [];

        $response['status'] = true;
        $response['data'] = $getFunnels;

        return $this->json($response, 200);
    }

    /**
     * @Route("/{funnelId}")
     * @param $funnelId
     * @return JsonResponse
     */
    public function detailAction($funnelId)
    {
        $getFunnelDetail = $this->funnels->getFunnelDetail($funnelId) ?? [];

        $response['status'] = true;
        $response['data'] = $getFunnelDetail;

        return $this->json($response, 200);
    }
}