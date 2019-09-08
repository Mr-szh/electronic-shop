<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Widgets\Box;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->column('id', 'Id')->sortable();
        $grid->column('avatar', '头像')->image(50, 50);
        $grid->column('name', '用户名');
        $grid->column('email', '电子邮箱');
        $grid->column('email_verified_at', '是否完成邮箱验证')->display(function ($value) {
            return $value ? '是' : '否';
        });
        // $grid->column('password', '密码')->hide();
        $grid->column('sex', '性别')->using(['0' => '暂空', '1' => '男', '2' => '女']);
        $grid->column('birthday', '生日')->display(function ($value) {
            return $value ? $value : '暂空';
        });
        // $grid->column('remember_token', __('Remember token'))->hide();
        $grid->column('created_at', '注册时间');
        $grid->column('updated_at', '修改时间');
        // $grid->column('status', '状态')->editable('select', [0 => '禁用', 1 => '启用']);
        
        // 禁用控件
        $grid->disableCreateButton();
        $grid->disableRowSelector();
        $grid->disableActions();

        // 筛选条件
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            
            $filter->like('name', '请输入用户名');
            $filter->like('email', '请输入电子邮箱');
            $filter->in('sex', '请选择性别')->multipleSelect(['0' => '暂无', '1' => '男', '2' => '女']);
            $filter->between('created_at', '请选择注册时间的区间')->date();

            $filter->scope('new', '最近注册/修改')
                ->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('sex', __('Sex'));
        $show->field('birthday', __('Birthday'));
        $show->field('avatar', __('Avatar'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('sex', __('Sex'));
        $form->date('birthday', __('Birthday'))->default(date('Y-m-d'));
        $form->image('avatar', __('Avatar'))->default('http://electronic-shop/images/default.jpg');
        $form->text('remember_token', __('Remember token'));
        $form->text('status', __('Status'))->default('1');

        return $form;
    }

    // public function edit($id, Content $content)
    // {
    //     return $content
    //         ->header('编辑用户信息')
    //         ->body($this->form()->edit($id));
    // }

    public function update($id)
    {
        return $this->form()->update($id);
    }
}
