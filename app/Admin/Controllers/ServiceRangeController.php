<?php

namespace App\Admin\Controllers;

use App\Models\Dorm;
use App\Models\School;
use App\Models\ServiceRange;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ServiceRangeController extends Controller
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

            $content->header('配送员服务范围列表');
            $content->description('配送员服务范围管理');

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

            $content->header('配送员服务范围修改');
            $content->description('配送员服务范围管理');

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

            $content->header('配送员服务范围创建');
            $content->description('配送员服务范围管理');

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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
            // 禁用删除
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });

            $grid->model()->where('role', User::USER_ROLE_DELIVERY);

            $grid->id('序号')->sortable();
            $grid->name('姓名');
            $grid->email('邮箱');
//            $grid->serviceRanges('配送学校')->pluck('school_name')->label();
            $grid->dorms('配送范围')->pluck('dorm_name')->label();
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('name', '姓名');
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
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', '序号');
            $form->display('name', '姓名');
            $form->text('email', '邮箱');
            $form->listbox('dorms', '配送范围')->options(Dorm::all()->pluck('dorm_name', 'id'));
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
