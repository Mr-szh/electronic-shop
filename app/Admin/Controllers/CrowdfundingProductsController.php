<?php

namespace App\Admin\Controllers;

use App\Models\Product;
// use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Category;
use App\Models\CrowdfundingProduct;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;

// class CrowdfundingProductsController extends Controller
class CrowdfundingProductsController extends CommonProductsController
{
    public function getProductType()
    {
        return Product::TYPE_CROWDFUNDING;
    }

    protected function customGrid(Grid $grid)
    {
        $grid->id('ID')->sortable();
        $grid->column('title', '商品名称');
        $grid->column('on_sale', '已上架')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('price', '价格');
        $grid->column('crowdfunding.target_amount', '目标金额');
        $grid->column('crowdfunding.end_at', '结束时间');
        $grid->column('crowdfunding.total_amount', '目前金额');
        $grid->column('crowdfunding.status', ' 状态')->display(function ($value) {
            return CrowdfundingProduct::$statusMap[$value];
        }); 

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            
            $filter->column(1/2, function ($filter) {
                $filter->like('title', '商品名称')->placeholder('请输入商品名称');
                $filter->in('on_sale', '商品状态')->multipleSelect(['true' => '上架', 'false' => '下架']);
            });
            
            $filter->column(1/2, function ($filter) {
                $filter->between('crowdfunding.end_at', '结束时间')->date();
                $filter->between('price', '价格区间');
            });
            
            $filter->scope('new', '最近创建/修改')
                ->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
        });
    }

    protected function customForm(Form $form)
    {
        $form->tab('众筹属性', function($form) {
            $form->text('crowdfunding.target_amount', '众筹目标金额')->rules('required|numeric|min:0.01');
            $form->datetime('crowdfunding.end_at', '众筹结束时间')->rules('required|date');
        });
    }

    // protected function grid()
    // {
    //     $grid = new Grid(new Product);

    //     $grid->model()->where('type', Product::TYPE_CROWDFUNDING);
        
    //     $grid->id('ID')->sortable();
    //     // $grid->column('type', __('Type'));
    //     // $grid->column('category_id', __('Category id'));
    //     $grid->column('title', '商品名称');
    //     // $grid->column('long_title', __('Long title'));
    //     // $grid->column('description', __('Description'));
    //     // $grid->column('image', __('Image'));
    //     // $grid->column('images', __('Images'));
    //     $grid->column('on_sale', '已上架')->display(function ($value) {
    //         return $value ? '是' : '否';
    //     });
    //     // $grid->column('rating', __('Rating'));
    //     // $grid->column('sold_count', __('Sold count'));
    //     // $grid->column('review_count', __('Review count'));
    //     $grid->column('price', '价格');

    //     $grid->column('crowdfunding.target_amount', '目标金额');
    //     $grid->column('crowdfunding.end_at', '结束时间');
    //     $grid->column('crowdfunding.total_amount', '目前金额');
    //     $grid->column('crowdfunding.status', ' 状态')->display(function ($value) {
    //         return CrowdfundingProduct::$statusMap[$value];
    //     }); 

    //     // $grid->column('created_at', __('Created at'));
    //     // $grid->column('updated_at', __('Updated at'));

    //     $grid->actions(function ($actions) {
    //         $actions->disableView();
    //         $actions->disableDelete();
    //     });

    //     $grid->tools(function ($tools) {
    //         $tools->batch(function ($batch) {
    //             $batch->disableDelete();
    //         });
    //     });

    //     return $grid;
    // }

    // protected function detail($id)
    // {
    //     $show = new Show(Product::findOrFail($id));

    //     $show->field('id', __('Id'));
    //     $show->field('type', __('Type'));
    //     $show->field('category_id', __('Category id'));
    //     $show->field('title', __('Title'));
    //     $show->field('long_title', __('Long title'));
    //     $show->field('description', __('Description'));
    //     $show->field('image', __('Image'));
    //     $show->field('images', __('Images'));
    //     $show->field('on_sale', __('On sale'));
    //     $show->field('rating', __('Rating'));
    //     $show->field('sold_count', __('Sold count'));
    //     $show->field('review_count', __('Review count'));
    //     $show->field('price', __('Price'));
    //     $show->field('created_at', __('Created at'));
    //     $show->field('updated_at', __('Updated at'));

    //     return $show;
    // }

    // protected function form()
    // {
    //     $form = new Form(new Product);

    //     $form->tab('众筹商品基本信息', function($form) {
    //         $form->hidden('type')->value(Product::TYPE_CROWDFUNDING);
    //         $form->text('title', '商品名称')->rules('required')->creationRules('required|unique:products');
    //         $form->select('category_id', '类目')->options(function ($id) {
    //             $category = Category::find($id);
    //             if ($category) {
    //                 return [$category->id => $category->full_name];
    //             }
    //         })->ajax('/admin/api/categories?is_directory=0');
    //         $form->multipleImage('image', '封面图片')->rules('required|image|max:3')->removable()->sortable()->move('cover');
    //         $form->textarea('description', '产品描述')->rules('required');
    //         $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');
    //     })->tab('众筹属性', function($form) {
    //         $form->text('crowdfunding.target_amount', '众筹目标金额')->rules('required|numeric|min:0.01');
    //         $form->datetime('crowdfunding.end_at', '众筹结束时间')->rules('required|date');
    //     })->tab('商品SKU', function($form) {
    //         $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
    //             $form->text('title', 'SKU 名称')->rules('required');
    //             $form->text('description', 'SKU 描述')->rules('required');
    //             $form->text('price', '单价')->rules('required|numeric|min:0.01');
    //             $form->text('stock', '剩余库存')->rules('required|integer|min:0');
    //         });
    //     });
        
    //     $form->tools(function (Form\Tools $tools) {
    //         $tools->disableDelete();
    //         $tools->disableView();
    //     });

    //     $form->footer(function ($footer) {     
    //         $footer->disableViewCheck();
    //         $footer->disableEditingCheck();
    //         $footer->disableCreatingCheck();
    //     });

    //     $form->saving(function (Form $form) {
    //         $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price');
    //     });
        
    //     return $form;
    // }
}
