<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
// use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class OrdersController extends AdminController
{
    use HasResourceActions;

    protected $title = '订单列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        // 只展示已支付的订单，并且默认按支付时间倒序排序
        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at', 'desc');

        // $grid->column('id', __('Id'));
        $grid->column('no', '订单流水号');
        // $grid->column('user_id', __('User id'));
        $grid->column('user.name', '买家');
        // $grid->column('address', __('Address'));
        $grid->column('total_amount', '总金额')->sortable();
        $grid->column('paid_at', '支付时间')->sortable();
        $grid->column('ship_status', '物流')->display(function($value) {
            return Order::$shipStatusMap[$value];
        });
        $grid->column('refund_status', '退款状态')->display(function($value) {
            return Order::$refundStatusMap[$value];
        });
        $grid->column('created_at', '创建时间');

        // $grid->column('remark', __('Remark'));
        // $grid->column('payment_method', __('Payment method'));
        // $grid->column('payment_no', __('Payment no'));
        // $grid->column('refund_no', __('Refund no'));
        // $grid->column('closed', __('Closed'));
        // $grid->column('reviewed', __('Reviewed'));
        // $grid->column('ship_data', __('Ship data'));
        // $grid->column('extra', __('Extra'));
        // $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/2, function ($filter) {
                $filter->like('no', '订单流水号')->placeholder('请输入订单流水号');
                $filter->like('user.name', '买家')->placeholder('请输入买家名称');
                $filter->group('total_amount', '总金额', function ($group) {
                    $group->gt('大于');
                    $group->lt('小于');
                    $group->nlt('不小于');
                    $group->ngt('不大于');
                    $group->equal('等于');
                })->integer()->placeholder('请输入总金额');
            });

            $filter->column(1/2, function ($filter) {
                $filter->between('paid_at', '支付时间')->date();
                $filter->in('ship_status', '物流')->multipleSelect(['pending' => '未发货', 'delivered' => '已发货', 'received' => '已收货']);
                $filter->in('refund_status', '退款状态')->multipleSelect(['pending' => '未退款', 'applied' => '已申请退款', 'processing' => '退款中', 'success' => '退款成功', 'failed' => '退款失败']);
            });

            $filter->scope('new', '最近创建/修改')->whereDate('created_at', date('Y-m-d'));
        });

        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('no', __('No'));
        $show->field('user_id', __('User id'));
        $show->field('address', __('Address'));
        $show->field('total_amount', __('Total amount'));
        $show->field('remark', __('Remark'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
        $show->field('refund_status', __('Refund status'));
        $show->field('refund_no', __('Refund no'));
        $show->field('closed', __('Closed'));
        $show->field('reviewed', __('Reviewed'));
        $show->field('ship_status', __('Ship status'));
        $show->field('ship_data', __('Ship data'));
        $show->field('extra', __('Extra'));
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
        $form = new Form(new Order);

        $form->text('no', __('No'));
        $form->number('user_id', __('User id'));
        $form->textarea('address', __('Address'));
        $form->decimal('total_amount', __('Total amount'));
        $form->textarea('remark', __('Remark'));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'));
        $form->text('payment_no', __('Payment no'));
        $form->text('refund_status', __('Refund status'))->default('pending');
        $form->text('refund_no', __('Refund no'));
        $form->switch('closed', __('Closed'));
        $form->switch('reviewed', __('Reviewed'));
        $form->text('ship_status', __('Ship status'))->default('pending');
        $form->textarea('ship_data', __('Ship data'));
        $form->textarea('extra', __('Extra'));

        return $form;
    }
}
