<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Carousel as CarouselResource;

class Carousel extends Model
{

    const USE_NUM = 5; // 最多显示的数据记录数
    const BOOTH_TYPE_HOME = 1;
    const BOOTH_TYPE_HOME_STRING = '首页';

    const STATE_NORMAL = 1;
    const STATE_BANNED = 0;
    const STATE_NORMAL_STRING = '正常';
    const STATE_BANNED_STRING = '禁用';

    //
    protected $table = 'carousel';

    /**
     * 根据展位类型获取轮播
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    static public function getCarouselByType($type){
    	if(!$type){
    		return false;
    	}
    	return CarouselResource::collection(Carousel::where([
            ['booth_type', '=', $type],
            ['state', '=', static::STATE_NORMAL],
        ])->take(static::USE_NUM)->get());
    }

    public static function getBoothTypeDisplayMap()
    {
        return [
            self::BOOTH_TYPE_HOME => self::BOOTH_TYPE_HOME_STRING,
        ];
    }

    public static function getStateDisplayConfig()
    {
        return [
            'on' => [
                'value' => self::STATE_NORMAL,
                'text' => self::STATE_NORMAL_STRING,
            ],
            'off' => [
                'value' => self::STATE_BANNED,
                'text' => self::STATE_BANNED_STRING,
            ]
        ];
    }
}
