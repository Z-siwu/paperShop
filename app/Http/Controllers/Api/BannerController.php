<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Carousel;
use Illuminate\Http\Request;

class BannerController extends ApiController
{
    /**
     * 轮播信息
     */
    public function index(Request $request)
    {
        // 验证规则
        $validator = Validator::make($request->all(),
            [
                'type' => 'bail|required|max:10'
            ],
            [
                'type.required' => 'type参数缺失'
            ]
        );
        if ($validator->fails()) {
            return $this->failed($validator->errors(), 401);
        }
        $carouselInfo = Carousel::getCarouselByType($request->type);
        return $this->success($carouselInfo);
    }
}
