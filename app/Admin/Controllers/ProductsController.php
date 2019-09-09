<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->column('id', 'Id')->sortable();
        $grid->column('title', '商品名称');
        // $grid->column('description', __('Description'));
        // $grid->column('image', __('Image'));
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

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('image', __('Image'));
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

        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->image('image', __('Image'));
        $form->switch('on_sale', __('On sale'))->default(1);
        $form->decimal('rating', __('Rating'))->default(5.00);
        $form->number('sold_count', __('Sold count'));
        $form->number('review_count', __('Review count'));
        $form->decimal('price', __('Price'));

        return $form;
    }
}
