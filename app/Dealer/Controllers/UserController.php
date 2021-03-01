<?php

namespace App\Dealer\Controllers;

use App\Dealer\Repositories\Administrator;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class UserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Administrator(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('dealer_id');
            $grid->column('username');
            $grid->column('password');
            $grid->column('name');
            $grid->column('avatar');
            $grid->column('remember_token');
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
        return Show::make($id, new Administrator(), function (Show $show) {
            $show->field('id');
            $show->field('dealer_id');
            $show->field('username');
            $show->field('password');
            $show->field('name');
            $show->field('avatar');
            $show->field('remember_token');
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
        return Form::make(new Administrator(), function (Form $form) {
            $form->display('id');
            $form->text('dealer_id');
            $form->text('username');
            $form->text('password');
            $form->text('name');
            $form->text('avatar');
            $form->text('remember_token');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
