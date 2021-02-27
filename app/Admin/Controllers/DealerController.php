<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Dealer;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class DealerController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Dealer(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('short_name');
            $grid->column('address');
            $grid->column('manager');
            $grid->column('phone');
            $grid->column('email');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Dealer(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('short_name');
            $show->field('address');
            $show->field('manager');
            $show->field('phone');
            $show->field('email');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Dealer(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('short_name');
            $form->text('address');
            $form->text('manager');
            $form->text('phone');
            $form->text('email');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
