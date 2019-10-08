<?php

namespace App\Admin\Controllers;

use App\Models\Product;
// use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use App\Models\Category;
use App\Jobs\SyncOneProductToES;

class ProductsController extends Controller
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    use HasResourceActions;

    protected $title = '商品列表';

    public function index(Content $content)
    {
        return $content
            ->header('商品列表')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->model()->with(['category']);

        $grid->column('id', 'ID')->sortable();
        $grid->column('title', '商品名称');
        // $grid->column('description', __('Description'));
        // $grid->column('image', __('Image'));
        // $grid->column('images', __('Images'));

        $grid->column('category.name', '类目');

        $grid->column('on_sale', '是否上架')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('price', '单价');
        $grid->column('sold_count', '销量');
        $grid->column('rating', '评分');
        $grid->column('review_count', '评论数');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '修改时间');

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            
            $filter->column(1/2, function ($filter) {
                $filter->like('title', '商品名称')->placeholder('请输入商品名称');
                $filter->in('on_sale', '商品状态')->multipleSelect(['true' => '上架', 'false' => '下架']);
                $filter->between('created_at', '创建时间')->date();
            });
            
            $filter->column(1/2, function ($filter) {
                $filter->between('price', '单价区间');
                $filter->group('sold_count', '销量', function ($group) {
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                    $group->equal('等于');
                })->integer()->placeholder('请输入销量');
                $filter->group('review_count', '评论数', function ($group) {
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                    $group->equal('等于');
                })->integer()->placeholder('请输入评论数');
            });
            
            $filter->scope('new', '最近创建/修改')
                ->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
        });

        $grid->actions(function ($actions) {
            // 去掉删除
            // $actions->disableDelete();
            // 去掉编辑
            // $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('image', __('Image'));
        $show->field('images', __('Images'));
        $show->field('on_sale', __('On sale'));
        $show->field('rating', __('Rating'));
        $show->field('sold_count', __('Sold count'));
        $show->field('review_count', __('Review count'));
        $show->field('price', __('Price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->tab('商品基本信息', function($form) {
            $form->text('title', '商品名称')->rules('required')->creationRules('required|unique:products');
            $form->text('long_title', '商品长标题')->rules('required');

            $form->select('category_id', '类目')->options(function ($id) {
                $category = Category::find($id);
                if ($category) {
                    return [$category->id => $category->full_name];
                }
            })->ajax('/admin/api/categories?is_directory=0');

            $form->textarea('description', '产品参数')->rules('required');
            // $form->image('image', '封面图片')->rules('required|image')->move('cover');
            $form->multipleImage('image', '封面图片')->rules('required|image|max:3')->removable()->sortable()->move('cover');
            $form->radio('on_sale', '是否上架')->options(['1' => '是', '0'=> '否'])->default('0');
        })->tab('商品SKU', function($form) {
            // 直接添加一对多的关联模型
            $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
                $form->text('title', 'SKU 名称')->rules('required');
                $form->text('description', 'SKU 描述')->rules('required');
                $form->text('price', '单价')->rules('required|numeric|min:0.01');
                $form->text('stock', '剩余库存')->rules('required|integer|min:0');
            });
        })->tab('商品属性', function($form) {
            $form->hasMany('properties', '商品属性', function (Form\NestedForm $form) {
                $form->text('name', '属性名')->rules('required');
                $form->text('value', '属性值')->rules('required');
            });
        })->tab('商品详情图', function($form) {
            $form->multipleImage('images', '详情图')->rules('image')->removable()->sortable()->move('details/'.time());
        });
        
        // $form->decimal('rating', __('Rating'))->default(5.00);
        // $form->number('sold_count', __('Sold count'));
        // $form->number('review_count', __('Review count'));
        // $form->decimal('price', __('Price'));

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

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        $form->saved(function (Form $form) {
            $product = $form->model();
            $this->dispatch(new SyncOneProductToES($product));
        });
        
        return $form;
    }

    public function create(Content $content)
    {
        return $content
            ->header('创建商品')
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑商品')
            ->body($this->form()->edit($id));
    }
}
