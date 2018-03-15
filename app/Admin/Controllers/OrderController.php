<?php

namespace App\Admin\Controllers;

use App\Logics\OrderLogic;
use App\Models\Order;
use App\Models\OrderGood;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Widgets\Alert;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('订单列表');
            $content->description('订单管理');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        $orderModel = Order::find($id);
        if ($orderModel->lock_state != 0) {
            $content = Admin::content(function (Content $content) use ($id) {

                $content->header('订单信息');
                $content->description('订单管理');

                $content->body($this->form()->view($id));
            });
        } else {
            $content = Admin::content(function (Content $content) use ($id) {

                $content->header('订单信息修改');
                $content->description('订单管理');

                $content->body($this->form()->edit($id));
            });
        }
        return $content;
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->actions(function ($actions){
                $actions->disableDelete();
                $keyID = $actions->getKey();
                $eachOrder = Order::find($keyID);
                if ($eachOrder) {
                    $lockState = $eachOrder->lock_state;
                    $status = $eachOrder->status;
                    if ($lockState != 0) {
                        $actions->disableEdit();
                        $actions->append('<a href="/admin/orders/'.$keyID.'"><i class="fa fa-eye"></i></a>');
                    } else if (($status == Order::STATUS_ALREADY_PAID) && empty($eachOrder->sid)) {
                        $actions->disableEdit();
                        $actions->append('<a href="/admin/orders/'.$keyID.'/receive">确认接单</a>');
                    } else if (($status == Order::STATUS_RECEIVED) && empty($eachOrder->sid)) {
                        $actions->disableEdit();
                        $actions->append('<a href="/admin/orders/'.$keyID.'/delivery">确认配送</a>');
                    }
                }
            });

            $grid->id('序号')->sortable();
            $grid->order_sn('订单编号');
            $grid->pay_price('支付金额');
            $grid->dorm_name('宿舍名');
            $grid->logistics_num('物流单号')->editable();
            $grid->column('delivery.delivery_name', '配送员');
            $grid->column('delivery.delivery_mobile', '配送员手机');
            $grid->user_name('收货人');
            $grid->user_mobile('收货人手机')->editable();
            $grid->msg('用户留言');
            $grid->lock_state('锁定状态')->display(function ($lockState) {
                return Order::getLockStateDisplayMap()[$lockState] ?? '';
            })->label('info');
            $grid->status('订单状态')->display(function ($status) {
                return Order::getStatusDisplayMap()[$status] ?? '';
            })->label('info');

            // 这里是多个信息一起显示
            $grid->column('查看详情')->expand(function () {
                $lists = Order::where('id',$this->id)->get()->toArray();
                $row_arr =array();
                if($lists){
                    foreach ($lists as $k => $v) {
                        // 支付信息
                        $pay_status = $v['status'] >= 22 ? '已支付' : '未支付';
                        $pay_info = [
                            '订单编号：'.$v['order_sn'],
                            '支付信息：'.$pay_status,
                        ];
                        if(!empty($v['pay_date']))
                        {
                            $pay_info[] = '支付时间：'.$v['pay_date'];
                            $pay_info[] = '支付交易号：'.$v['paycode'];
                        }
                        $row_arr[] = $pay_info;

                        // 收获信息
                        $addr_info = [
                            '收货人：'.$v['user_name'],
                            '收货人手机号：'.$v['user_mobile'],
                            '收货地址：'.$v['addr_prov'].' '.$v['addr_city'].' '.$v['addr_area'].' '.$v['addr_detail'],
                        ];
                        $row_arr[] = $addr_info;

                        // 学校信息
                        if (!empty($v['sid']))
                        {
                            $school_info = [
                                '学校：'.$v['school_name'],'宿舍：'.$v['dorm_name'],
                                '用户留言：'.$v['msg'],
                            ];
                            $row_arr[] = $school_info;
                        }

                        // 订单商品信息
                        $orderitems = OrderGood::where(['oid'=>$v['id']])->get()->toArray();
                        if (!empty($orderitems))
                        {
                            foreach ($orderitems as $per_item)
                            {
                                $info_str = $per_item['goods_name'];
                                $order_items_info = [$info_str, '数量：'.$per_item['num'], '价格：'.$per_item['goods_price']];
                                $row_arr[] = $order_items_info;
                            }
                        }
                        // 订单操作信息
                    }
                }

                return new Table(['订单信息'], $row_arr);
            }, '查看');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->equal('order_sn', '订单编号');
                $filter->equal('dorm_name', '宿舍名');
                $filter->equal('logistics_num', '物流单号');
                $filter->equal('delivery.delivery_name', '配送员');
                $filter->equal('delivery.delivery_mobile', '配送员手机');
                $filter->equal('user_mobile', '收货人手机');
                $filter->equal('lock_state', '锁定状态')->select();
                $filter->equal('status', '订单状态')->select();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {
//            var_dump($form->input('id'));

            $form->tab('基础信息', function (Form $form) {
                $form->display('id', '序号');
                $form->display('order_sn', '订单编号');

                $form->display('msg', '用户留言');
                $form->display('remark', '备注');
                $form->display('remark_img', '备注图片');

                $form->display('lock_state', '锁定状态')->with(function ($value) {
                    return Order::getLockStateDisplayMap()[$value] ?? '';
                });
                $form->display('status', '订单状态')->with(function ($value) {
                    return Order::getStatusDisplayMap()[$value] ?? '';
                });
                $form->display('cancel_admin', '后台取消操作人');
                $form->display('cancel_date', '取消时间');

                $form->display('created_at', '创建时间');
                $form->display('updated_at', '更新时间');
            })->tab('订单金额信息', function (Form $form) {
                $form->display('total_price', '订单总金额');
                $form->display('coupon_price', '代金券减免金额');
                $form->display('pay_price', '支付金额');
                $form->display('pay_method', '支付方式');
                $form->display('paycode', '支付交易号');
                $form->display('pay_date', '支付时间');
                $form->display('trade_no', '支付宝微信交易号');
            })->tab('配送信息', function (Form $form) {
                $form->text('logistics_num', '物流号');
                $form->display('delivery.delivery_name', '配送员');
                $form->display('delivery.delivery_mobile', '配送员手机');
                $form->display('delivery.desc', '说明');
                $form->display('delivery.remark', '备注');
                $form->display('delivery.status', '配送状态');
            })->tab('收货信息', function (Form $form) {
                $form->display('addr_prov', '收货地址-省');
                $form->display('addr_city', '收货地址-市');
                $form->display('addr_area', '收货地址-县/区');
                $form->display('addr_detail', '详细地址');
                $form->display('school_name', '收货地址-学校');
                $form->display('dorm_name', '收货地址-宿舍');
                $form->display('user_name', '收货人');
                $form->text('user_mobile', '收货人手机号')
                    ->rules('required');
            });
//            $form->hasMany('ordergoods', '商品', function (Form\NestedForm $form) {
//                $form->disableSubmit();
//                $form->display('goods_name', '商品名');
//                $form->display('goods_image', '商品图片');
//                $form->display('goods_desc', '商品说明');
//                $form->display('goods_price', '商品金额');
//                $form->display('goods_num', '商品购买数量');
//                $form->display('addr_price', '运费');
//                $form->display('coupon_price', '代金券减免金额');
//            });
        });
    }

    public function receive($id)
    {
        $orderLogic = new OrderLogic();
        list($resaultFlag, $errMsg) = $orderLogic->receiveOrder($id);
        if ($resaultFlag) {
            admin_toastr('接单成功!');
            return redirect('admin/orders');
        } else {
            admin_toastr($errMsg, 'error');
            return redirect('admin/orders');
        }
    }

    public function delivery($id)
    {
        $orderLogic = new OrderLogic();
        list($resaultFlag, $errMsg) = $orderLogic->deliveryOrder($id);
        if ($resaultFlag) {
            admin_toastr('配送成功!');
            return redirect('admin/orders');
        } else {
            admin_toastr($errMsg, 'error');
            return redirect('admin/orders');
        }
    }
}
