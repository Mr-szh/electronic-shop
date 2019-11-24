<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use App\Models\Category;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Jobs\SyncOneProductToES;

abstract class CommonProductsController extends Controller
{
    use HasResourceActions;

    // 定义一个抽象方法，返回当前管理的商品类型
    abstract public function getProductType();

    public function index(Content $content)
    {
        return $content
            ->header(Product::$typeMap[$this->getProductType()].'列表')
            ->body($this->grid());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑'.Product::$typeMap[$this->getProductType()])
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('创建'.Product::$typeMap[$this->getProductType()])
            ->body($this->form());
    }

    protected function grid()
    {
        $grid = new Grid(new Product());

        // 筛选出当前类型的商品，默认 ID 倒序排序
        $grid->model()->where('type', $this->getProductType())->orderBy('id', 'desc');
        // 调用自定义方法
        $this->customGrid($grid);

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    // 定义一个抽象方法，各个类型的控制器将实现本方法来定义列表应该展示哪些字段
    abstract protected function customGrid(Grid $grid);

    protected function form()
    {
        $form = new Form(new Product());

        // 在表单页面中添加一个名为 type 的隐藏字段，值为当前商品类型
        $form->hidden('type')->value($this->getProductType());
        
        $form->tab('商品基本信息', function($form) {
            $form->text('title', '商品名称')->rules('required')->creationRules('required|unique:products');
            $form->text('long_title', '商品长标题')->rules('required');
            $form->select('category_id', '类目')->options(function ($id) {
                $category = Category::find($id);
                if ($category) {
                    return [$category->id => $category->full_name];
                }
            })->ajax('/admin/api/categories?is_directory=0');
            
            $form->multipleImage('image', '封面图片')->rules('required|image|max:3')->removable()->sortable()->move('cover');
            $form->textarea('description', '产品描述')->rules('required');
            $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');
        })->tab('商品SKU', function($form) {    
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

        // 调用自定义方法
        $this->customForm($form);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->footer(function ($footer) {     
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
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

    // 定义一个抽象方法，各个类型的控制器将实现本方法来定义表单应该有哪些额外的字段
    abstract protected function customForm(Form $form);
}