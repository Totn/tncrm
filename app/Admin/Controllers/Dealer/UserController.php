<?php

namespace App\Admin\Controllers\Dealer;

use App\Admin\Repositories\Dealer\Administrator;
use App\Models\Dealer;
use App\Models\Dealer\Administrator as DealerAdministrator;
use App\Models\Dealer\Permission;
use App\Models\Dealer\Role;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\UserController as DcatUserController;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tree;

class UserController extends DcatUserController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Administrator::with(['roles', 'dealer']), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('dealer.name');
            $grid->column('username');
            $grid->column('name');

            if (config('dealer.permission.enable')) {
                $grid->column('roles')->pluck('name')->label('primary', 3);

                $permissionModel = config('dealer.database.permissions_model');
                $roleModel = config('dealer.database.roles_model');
                $nodes = (new $permissionModel())->allNodes();
                $grid->column('permissions')
                    ->if(function () {
                        return ! $this->roles->isEmpty();
                    })
                    ->showTreeInDialog(function (Grid\Displayers\DialogTree $tree) use (&$nodes, $roleModel) {
                        $tree->nodes($nodes);

                        foreach (array_column($this->roles->toArray(), 'slug') as $slug) {
                            if ($roleModel::isAdministrator($slug)) {
                                $tree->checkAll();
                            }
                        }
                    })
                    ->else()
                    ->display('');
            }

            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['id', 'name', 'username']);

            $grid->showQuickEditButton();
            $grid->enableDialogCreate();
            $grid->showColumnSelector();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // if ($actions->getKey() == DealerAdministrator::DEFAULT_ID) {
                if (collect($this->roles)->pluck('slug')->contains(Role::ADMINISTRATOR)) {
                    $actions->disableDelete();
                }
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
        return Show::make($id, Administrator::with(['roles', 'dealer']), function (Show $show) {
            $show->field('id');
            $show->field('dealer.name');
            $show->field('username');
            $show->field('name');

            $show->field('avatar', __('admin.avatar'))->image();

            if (config('dealer.permission.enable')) {
                $show->field('roles')->as(function ($roles) {
                    if (! $roles) {
                        return;
                    }

                    return collect($roles)->pluck('name');
                })->label();

                $show->field('permissions')->unescape()->as(function () {
                    $roles = $this->roles->toArray();

                    $permissionModel = new Permission();
                    $nodes = $permissionModel->allNodes();

                    $tree = Tree::make($nodes);

                    $isAdministrator = false;
                    foreach (array_column($roles, 'slug') as $slug) {
                        if (Role::isAdministrator($slug)) {
                            $tree->checkAll();
                            $isAdministrator = true;
                        }
                    }

                    if (! $isAdministrator) {
                        $keyName = $permissionModel->getKeyName();
                        $tree->check(
                            Role::getPermissionId(array_column($roles, $keyName))->flatten()
                        );
                    }

                    return $tree->render();
                });
            }

            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Form::make(Administrator::with(['roles', 'dealer']), function (Form $form) {
            $userTable = config('dealer.database.users_table');

            $connection = config('dealer.database.connection');

            $id = $form->getKey();

            $form->display('id', 'ID');
            
            $form->select('dealer_id')->options(Dealer::all()->pluck('name', 'id')->toArray());

            $form->text('username', trans('admin.username'))
                ->required()
                ->creationRules(['required', "unique:{$connection}.{$userTable}"])
                ->updateRules(['required', "unique:{$connection}.{$userTable},username,$id"]);
            $form->text('name', trans('admin.name'))->required();
            $form->image('avatar', trans('admin.avatar'))->autoUpload();

            if ($id) {
                $form->password('password', trans('admin.password'))
                    ->minLength(5)
                    ->maxLength(20)
                    ->customFormat(function () {
                        return '';
                    });
            } else {
                $form->password('password', trans('admin.password'))
                    ->required()
                    ->minLength(5)
                    ->maxLength(20);
            }

            $form->password('password_confirmation', trans('admin.password_confirmation'))->same('password');

            $form->ignore(['password_confirmation']);

            if (config('dealer.permission.enable')) {
                $form->multipleSelect('roles', trans('admin.roles'))
                    ->options(function () {
                        $roleModel = config('dealer.database.roles_model');

                        return $roleModel::all()->pluck('name', 'id');
                    })
                    ->customFormat(function ($v) {
                        return array_column($v, 'id');
                    });
            }

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            if ($id == DealerAdministrator::DEFAULT_ID) {
                $form->disableDeleteButton();
            }
        })->saving(function (Form $form) {
            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

            if (! $form->password) {
                $form->deleteInput('password');
            }
        });
    }
}
