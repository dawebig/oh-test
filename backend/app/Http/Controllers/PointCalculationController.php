<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculationRequest;
use Illuminate\Routing\Controller as BaseController;

class PointCalculationController extends BaseController
{
    public function calculate(CalculationRequest $request) {
        //dd($request);
    }
}

