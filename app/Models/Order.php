<?php

namespace App\Models;

use App\Http\Resources\OrderAndOrderGoodsList;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const LOCK_STATE_NORMAL = 0;//正常
    const LOCK_STATE_WAIT_PAY = 1;//订单待付款
    const LOCK_STATE_PAY_FAILED = 2;//支付失败
    const LOCK_STATE_REFUND = 3;//订单退款中
    const LOCK_STATE_EXCEPTION = 4;//订单异常
    const LOCK_STATE_NORMAL_STRING = '正常';//正常
    const LOCK_STATE_WAIT_PAY_STRING = '订单待付款';//订单待付款
    const LOCK_STATE_PAY_FAILED_STRING = '支付失败';//支付失败
    const LOCK_STATE_REFUND_STRING = '退款中';//订单退款中
    const LOCK_STATE_EXCEPTION_STRING = '订单异常';//订单异常

    const STATUS_INVALID = 0;//订单关闭或无效，用户取消也置0
    const STATUS_WAIT_PAY = 10;//订单待支付
    const STATUS_ALREADY_PAID = 22;//订单支付完成
    const STATUS_RECEIVED = 26;//确认接单
    const STATUS_DELIVERING = 32;//确认配送
    const STATUS_COMPLETED = 40;//订单收货完成
    const STATUS_INVALID_STRING = '订单无效';//订单关闭或无效，用户取消也置0
    const STATUS_WAIT_PAY_STRING = '待支付';//订单待支付
    const STATUS_ALREADY_PAID_STRING = '支付完成';//订单支付完成
    const STATUS_RECEIVED_STRING = '已接单';//确认接单
    const STATUS_DELIVERING_STRING = '已配送';//确认配送
    const STATUS_COMPLETED_STRING = '已收货';//订单收货完成
    //
    protected $table = 'order';
    protected $fillable = [
        'uid',
        'sid',
        'did',
        'cancel_date',
        'pay_date',
        'school_name',
        'dorm_name',
        'pay_method',
        'order_sn',
        'paycode',
        'trade_no',
        'total_price',
        'addr_price',
        'pay_price',
        'aid_p',
        'aid_c',
        'aid_a',
        'addr_prov',
        'addr_city',
        'addr_area',
        'addr_detail',
        'user_name',
        'user_mobile',
        'cancel_admin',
        'remark',
        'msg',
        'lock_state',
        'status',
    ];

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'oid');
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGood::class, 'oid');
    }

    public static function getLockStateDisplayMap()
    {
        return [
            self::LOCK_STATE_NORMAL => self::LOCK_STATE_NORMAL_STRING,
            self::LOCK_STATE_WAIT_PAY => self::LOCK_STATE_WAIT_PAY_STRING,
            self::LOCK_STATE_PAY_FAILED => self::LOCK_STATE_PAY_FAILED_STRING,
            self::LOCK_STATE_REFUND => self::LOCK_STATE_REFUND_STRING,
            self::LOCK_STATE_EXCEPTION => self::LOCK_STATE_EXCEPTION_STRING,
        ];
    }

    public static function getStatusDisplayMap()
    {
        return [
            self::STATUS_INVALID => self::STATUS_INVALID_STRING,
            self::STATUS_WAIT_PAY => self::STATUS_WAIT_PAY_STRING,
            self::STATUS_ALREADY_PAID => self::STATUS_ALREADY_PAID_STRING,
            self::STATUS_RECEIVED => self::STATUS_RECEIVED_STRING,
            self::STATUS_DELIVERING => self::STATUS_DELIVERING_STRING,
            self::STATUS_COMPLETED => self::STATUS_COMPLETED_STRING,
        ];
    }

    /**
     * 获取订单 及 订单商品列表
     */
    public static function getOrderAndOrderGoodsList($condition)
    {
        return static::with('orderGoods')->where($condition)->orderBy('id', 'desc')->get();
    }

    /**
     * 获取订单数量
     */
    public static function countOrder($condition)
    {
        $n = static::where($condition)->count();
        return $n ? $n : 0;
    }
}
