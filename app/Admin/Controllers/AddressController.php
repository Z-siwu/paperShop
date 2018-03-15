<?php

namespace App\Admin\Controllers;

use App\Models\Address;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class AddressController extends Controller
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

            $content->header('客户地址信息');
            $content->description('地址管理');

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
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
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
        return Admin::grid(Address::class, function (Grid $grid) {

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableRowSelector();

            $grid->id('序号')->sortable();

            $grid->province('省');
            $grid->city('市');
            $grid->area('县/区');
            $grid->school_name('学校');
            $grid->dorm_name('宿舍');
            $grid->addr('详细地址');
            $grid->true_name('真实姓名');
            $grid->mobile('手机号');
            $grid->is_default('默认地址')->display(function ($isDefault) {
                return Address::getIsDefaultDisplayMap()[$isDefault] ?? 'null';
            })->badge('info');
            $grid->status('状态')->display(function ($status) {
                return Address::getStateDisplayMap()[$status] ?? 'null';
            })->badge('info');

            $grid->created_at();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->like('true_name', '真实姓名');
                $filter->equal('is_default', '默认地址')
                    ->radio(Address::getIsDefaultDisplayMap());
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
        return Admin::form(Address::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
