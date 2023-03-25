<?php

namespace App\Http\Controllers;

use App\Services\PointCalculationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PointCalculationController extends BaseController
{
    private PointCalculationService $service;

    /**
     * @param PointCalculationService $service
     */
    public function __construct(PointCalculationService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request) {
        return $this->service->calculatePoint($request);
    }
}

