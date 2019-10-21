<?php

namespace App\Admin\Controllers;

use App\Models\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    use HasResourceActions;

    protected $title = '社区管理'; 

    public function index(Content $content)
    {  
        return $content
            ->header('帖子列表')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Topic);

        $grid->model()->with(['user', 'category']);
        // ->where('role', '=', 'admin')

        $grid->column('id', 'ID')->sortable();
        $grid->column('title', '帖子标题');
        $grid->column('category.name', '分类');
        $grid->column('user.name', '楼主');
        $grid->column('role', '角色')->using(['user' => '用户', 'admin' => '管理员']);
        $grid->column('reply_count', '回复数');
        $grid->column('url', 'URL');

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->in('category.name', '分类')->multipleSelect(['1' => '分享', '2' => '问答', '3' => '公告', '4' => '定制']);
            $filter->in('role', '角色')->multipleSelect(['user' => '用户', 'admin' => '管理员']);
            
            $filter->scope('new', '最近发帖')
                ->whereDate('created_at', date('Y-m-d'));
        });

        $grid->actions(function ($actions) {
            $actions->disableView();

            if ($actions->row->role == 'user') {
                $actions->disableEdit();
            }
        });

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new Topic);

        $form->text('title', '帖子标题')->rules('required|min:2', [
            'required' => '帖子标题不能为空',
            'min' => '帖子标题不能少于两个字符',
        ]);
        $form->select('category_id', '分类')->options([1 => '分享', 2 => '问答', '3' => '公告', '4' => '定制']);
        $form->UEditor('body', '帖子内容')->rules('required|min:3');

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });

        $form->saving(function (Form $form) {
            $id = DB::table('topics')->max('id') + 1;
            $url = 'http://electronic-shop/topics/'.$id;
            $user_id = Auth::guard('admin')->user()->toArray()['id'];

            $form->model()->user_id = $user_id;
            $form->model()->url = $url;
            $form->model()->role = 'admin';
        });

        return $form;
    }

    public function create(Content $content)
    {
        return $content
            ->header('新增帖子')
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑帖子')
            ->body($this->form()->edit($id));
    }
}
