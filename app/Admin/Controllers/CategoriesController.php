<?php

namespace App\Admin\Controllers;

use App\Models\Category;
// use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use HasResourceActions;

    protected $title = 'App\Models\Category';

    public function index(Content $content)
    {
        return $content
            ->header('商品类目列表')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '名称');
        // $grid->column('parent_id', __('Parent id'));
        $grid->column('is_directory', '是否拥有子类目')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('level', '层级');
        $grid->column('path', '类目路径');
        $grid->column('created_at', '创建时间');
        // $grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            
            $filter->like('name', '名称')->placeholder('请输入类目名称');
            $filter->in('is_directory', '是否拥有子类目')->multipleSelect(['1' => '是', '0' => '否']);
            $filter->between('created_at', '创建时间')->date();
            
            $filter->scope('new', '最近创建/修改')
                ->whereDate('created_at', date('Y-m-d'));
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('parent_id', __('Parent id'));
        $show->field('is_directory', __('Is directory'));
        $show->field('level', __('Level'));
        $show->field('path', __('Path'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($isEditing = false)
    {
        $form = new Form(new Category);

        $form->text('name', '类目名称')->rules('required');

        if ($isEditing) {
            $form->display('is_directory', '是否拥有子类目')->with(function ($value) {
                return $value ? '是' :'否';
            });

            $form->display('parent.name', '父类目');
        } else {
            $form->radio('is_directory', '是否拥有子类目')
                ->options(['1' => '是', '0' => '否'])
                ->default('0')
                ->rules('required');
            
            // 代表下拉框的值通过 /admin/api/categories 接口搜索获取
            $form->select('parent_id', '父类目')->ajax('/admin/api/categories');
        }

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->footer(function ($footer) {     
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        return $form;
    }

    public function create(Content $content)
    {
        return $content
            ->header('创建商品类目')
            ->body($this->form(false));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑商品类目')
            ->body($this->form(true)->edit($id));
    }

    // 定义下拉框搜索接口
    public function apiIndex(Request $request)
    {
        // 用户输入的值通过 q 参数获取
        $search = $request->input('q');
        $result = Category::query()
            ->where('is_directory', boolval($request->input('is_directory', true)))
            ->where('name', 'like', '%'.$search.'%')
            ->paginate();

        // 把查询出来的结果重新组装成 Laravel-Admin 需要的格式
        $result->setCollection($result->getCollection()->map(function (Category $category) {
            return ['id' => $category->id, 'text' => $category->full_name];
        }));

        return $result;
    }
}
