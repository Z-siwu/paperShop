<?php

namespace App\Admin\Controllers;

use App\Models\Dorm;
use App\Models\School;
use Encore\Admin\Auth\Database\Administrator;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class DormController extends Controller
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

            $content->header('院校宿舍列表');
            $content->description('宿舍管理');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    // public function edit($id)
    // {
    //     return Admin::content(function (Content $content) use ($id) {

    //         $content->header('header');
    //         $content->description('description');


    //         $content->body($this->form()->edit($id));
    //     });
    // }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('新增学校');
            $content->description('');

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
        return Admin::grid(Dorm::class, function (Grid $grid) {
            $grid->id('序号')->sortable('DESC');
            $grid->column('dorm_name','宿舍名称')->editable();
            
            // 所属学校
            $school_list = School::select()->pluck('name', 'id');
            $grid->sid('所属学校')->select($school_list);
            
            $grid->created_at('创建时间');            
            $grid->create_user('创建人')->display(function($userId) {
                if ($userId > 0)
                {
                    return Administrator::find($userId)->username;
                }
                return '不留名';
            });

            $grid->updated_at('更新时间');
            $grid->update_user('更新人')->display(function($userId) {
                if ($userId > 0)
                {
                    return Administrator::find($userId)->username;
                }
                return '不留名';
            });

            // 搜索功能
            $grid->filter(function($filter){
                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
                // $filter->useModal();
                // 禁用id查询框
                // $filter->disableIdFilter();
                
                // 搜索某个学校下的所有宿舍
                $filter->equal('sid', '学校')
                    ->select(School::select()->pluck('school_name', 'id'));
            });
            // 禁用删除
            $grid->actions(function ($actions) {
                $actions->disableEdit();
            });
            // 禁用导出
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Dorm::class, function (Form $form) {
            $form->text('dorm_name','宿舍名称')->rules('required|min:1|max:20');
            // 所属学校
            $school_list = School::select()->pluck('school_name', 'id');
            $form->select('sid','所属学校')->options($school_list);
            
            $form->hidden('update_user');
            $form->hidden('create_user');
            
            //保存前回调 此处只修改创建人
            $form->saving(function (Form $form) {
                $form->update_user = Admin::user()->id;
                if (!is_null($form->model()->id))
                {
                    unset($form->create_user);
                }
                else
                {
                    $form->create_user = Admin::user()->id;                    
                }
            });
        });
    }

}
