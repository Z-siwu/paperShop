<?php

namespace App\Http\Controllers\Api;

use App\Models\Special;

class NavController extends ApiController
{
    /**
     * 专题导航信息
     */
    public function special()
    {
        $specialList = Special::getSpecialList();
        return $this->success($specialList);
    }
}
