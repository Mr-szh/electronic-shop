<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('')
            ->description('')
            ->row(function (Row $row) {

                $row->column(4, 'xxx');
            
                $row->column(8, function (Column $column) {
                    $column->row('111');
                    $column->row('222');
                    $column->row(function(Row $row) {
                        $row->column(6, '444');
                        $row->column(6, '555');
                    });
                });
            });
    }
}
