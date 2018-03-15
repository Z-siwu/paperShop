<?php

namespace App\Admin\Controllers;

use App\Models\School;
use App\Models\Addrjson;
use Encore\Admin\Auth\Database\Administrator;
// use App\Models\Dorm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Alert;
use Encore\Admin\Widgets\Callout;

class SchoolController extends Controller
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

            $content->header('合作院校列表');
            $content->description('学校管理');
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

            $content->header('编辑');
            $content->description('description');

            $content->row(function (Row $row) {

                $words = '在编辑学校的时候，由于考虑到数据来取带来的服务器压力，固地区需要重新选择，如果2,3地区数据没有出现，需要重新聚焦1级地区，如原来选择重庆，编辑的时候没有显示2,3级地区选择其他省一次然后在重新选择重新即可（缓存策略导致）';

                $row->column(10, function (Column $column) use ($words) {
                    $column->append((new Alert($words, '提示'))->style('success')->icon('user'));
                });

            });
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
        return Admin::grid(School::class, function (Grid $grid) {
            $grid->model()->where('id', '>', 1);
            $grid->id('序号')->sortable('DESC');
            $grid->aid_p('省')->display(function($id) {
                if ($id > 0)
                {
                    return Addrjson::find($id)->area_name;
                }
            });
            $grid->aid_c('市')->display(function($id) {
                if ($id > 0)
                {
                    return Addrjson::find($id)->area_name;
                }
            });
            $grid->aid_a('区')->display(function($id) {
                if ($id > 0)
                {
                    return Addrjson::find($id)->area_name;
                }
            });
            $grid->column('school_name','学校名称')->editable();

            $grid->create_user('创建人')->display(function($userId) {
                if ($userId > 0)
                {
                    return Administrator::find($userId)->username;
                }
                return '不留名';
            });
            $grid->update_user('更新人')->display(function($userId) {
                if ($userId > 0)
                {
                    return Administrator::find($userId)->username;
                }
                return '不留名';
            });
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');
            $states = [
                'on'  => ['value' => 2, 'text' => '支持中', 'color' => 'primary'],
                'off' => ['value'  => 1, 'text' => '不支持', 'color' => 'default'],
            ];
            $grid->status('状态')->switch($states);

            // 搜索功能
            $grid->filter(function($filter){
                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
                // $filter->useModal();

                // 禁用id查询框
                // $filter->disableIdFilter();
                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('school_name', '学校名称');
                $filter->equal('status', '状态')->select([1=>'不支持',2=>'支持中']);
            });
            // 禁用行删除
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
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
        return Admin::form(School::class, function (Form $form) {
            $form->text('school_name','学校名称')->rules('required|min:1|max:20');

            // 地址级联
            $form->select('aid_p', '地址：省')->options(
                Addrjson::select('area_id','area_name')->where(['area_parent_id'=>0])->pluck('area_name', 'area_id')
            )->load('aid_c', '/api/addr_json/city', 'area_id', 'area_name');
            $form->select('aid_c', '市')->options(function ($id) {})->load('aid_a', '/api/addr_json/city', 'area_id', 'area_name');
            $form->select('aid_a', '区')->options(function ($id) {});

            $form->hidden('update_user');
            $form->hidden('create_user');
            $states = [
                'on'  => ['value' => 2, 'text' => '支持中', 'color' => 'success'],
                'off' => ['value' => 1, 'text' => '不支持', 'color' => 'danger'],
            ];
            $form->switch('status', '支持状态')->states($states);
            // $form->saved(function (Form $form) {
            //     if($form->model()->id!=0){
            //         $info = School::find($form->model()->id);
            //         $info = School::find($info->parent_id);
            //         $info->save();
            //     }
            // });
            $form->divide();
            $form->hasMany('dorm', '宿舍', function (Form\NestedForm $form) {
                $form->hidden('create_user')->value(Admin::user()->id);
                $form->hidden('update_user')->value(Admin::user()->id);
                $form->text('dorm_name','宿舍名称')->rules('required|min:1|max:20');
            });
            
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
