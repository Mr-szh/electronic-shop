<?php

namespace App\Admin\Controllers;

// use Encore\Admin\Controllers\AdminController;
use App\Models\CouponCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponCodesController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('优惠券列表')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new CouponCode);

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', 'ID')->sortable();
        $grid->column('name', '名称');
        $grid->column('code', '优惠码');
        // $grid->column('type', '类型')->display(function($value) {
        //     return CouponCode::$typeMap[$value];
        // });
        // $grid->column('value', '折扣')->display(function($value) {
        //     return $this->type === CouponCode::TYPE_FIXED ? '￥'.$value : $value.'%';
        // });
        // $grid->column('total', '总量');
        $grid->description('描述');
        $grid->column('used', '用量')->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });
        // $grid->column('min_amount', '最低金额');
        // $grid->column('not_before', __('Not before'));
        // $grid->column('not_after', __('Not after'));
        $grid->column('enabled', '是否启用')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->column('created_at', '创建时间');
        // $grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->like('name', '优惠券名称')->placeholder('请输入优惠券名称');
            $filter->in('enabled', '是否启用')->multipleSelect(['1' => '是', '0' => '否']);
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
        $show = new Show(CouponCode::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('type', __('Type'));
        $show->field('value', __('Value'));
        $show->field('total', __('Total'));
        $show->field('used', __('Used'));
        $show->field('min_amount', __('Min amount'));
        $show->field('not_before', __('Not before'));
        $show->field('not_after', __('Not after'));
        $show->field('enabled', __('Enabled'));
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
        $form = new Form(new CouponCode);

        $form->text('name', '名称')->rules('required');
        // 管理员可不填，将由系统自动生成
        $form->text('code', '优惠码')->rules(function ($form) {
            if ($id = $form->model()->id) {
                return 'nullable|unique:coupon_codes,code,' . $id . ',id';
            } else {
                return 'nullable|unique:coupon_codes';
            }
        });
        $form->radio('type', '类型')->options(CouponCode::$typeMap)->rules('required')->default(CouponCode::TYPE_FIXED);
        $form->decimal('value', '折扣')->rules(function ($form) {
            if ($form->type === CouponCode::TYPE_PERCENT) {
                return 'numeric|between:1,99';
            } else {
                return 'numeric|min:0.01';
            }
        })->default('1');
        $form->text('total', '总量')->rules('required|numeric|min:1');
        // $form->number('used', __('Used'));
        $form->decimal('min_amount', '最低金额')->rules('required|numeric|min:0');
        $form->datetime('not_before', '开始时间')->default(date('Y-m-d H:i:s'));
        $form->datetime('not_after', '结束时间')->default(date('Y-m-d H:i:s'))
            ->rules('after_or_equal:not_before', ['after_or_equal' => '结束时间必须大于或等于开始时间']);
        $form->radio('enabled', '启用')->options(['1' => '是', '0' => '否']);

        $form->submitted(function (Form $form) {
            $data = request()->all();

            if (request()->route()->getActionMethod() == 'store') {
                $validator = \Validator::make($data, [
                    'name' => 'required',
                    'type' => 'required',
                    'value' => 'required|numeric',
                    'total' => 'required|numeric|min:1',
                    'min_amount' => 'required|numeric|min:0',
                    'not_after' => 'after_or_equal:not_before',
                ], [
                    'value.required' => '折扣不能为空',
                    'value.numeric' => '折扣必须为数值类型',
                    'total.min' => '总量不能小于1',
                    'not_after.after_or_equal' => '结束时间必须大于或等于开始时间',
                ]);
                if (!$validator->passes()) {
                    return back()->withErrors($validator)->withInput();
                }
            }

            return true;
        });

        $form->saving(function (Form $form) {
            if (!$form->value) {
                throw new \Exception('折扣不能为空！');
            }

            if (!$form->code) {
                $form->code = CouponCode::findAvailableCode();
            }

            return $form;
        });

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
            ->header('新增优惠券')
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑优惠券')
            ->body($this->form()->edit($id));
    }
}
