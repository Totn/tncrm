<?php

namespace App\Admin\Controllers\Dealer;

use App\Admin\Repositories\Dealer\Menu;
use App\Models\Dealer\Menu as DealerMenu;
use App\Models\Dealer\Permission;
use App\Models\Dealer\Role;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Actions\Menu\Show;
use Dcat\Admin\Http\Controllers\MenuController as DcatMenuController;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Box;
use Dcat\Admin\Widgets\Form as WidgetForm;

class MenuController extends DcatMenuController
{

    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description(trans('admin.list'))
            ->body(function (Row $row) {
                $row->column(7, $this->treeView()->render());

                $row->column(5, function (Column $column) {
                    $form = new WidgetForm();
                    $form->action(admin_url('dealer/menu'));

                    $form->select('parent_id', trans('admin.parent_id'))->options(DealerMenu::selectOptions());
                    $form->text('title', trans('admin.title'))->required();
                    $form->icon('icon', trans('admin.icon'))->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));

                    if (DealerMenu::withRole()) {
                        $form->multipleSelect('roles', trans('admin.roles'))->options(Role::all()->pluck('name', 'id'));
                    }
                    if (DealerMenu::withPermission()) {
                        $form->tree('permissions', trans('admin.permission'))
                            ->expand(false)
                            ->nodes((new Permission())->allNodes());
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $form->width(9, 2);

                    $column->append(Box::make(trans('admin.new'), $form));
                });
            });
    }

    /**
     * @return \Dcat\Admin\Tree
     */
    protected function treeView()
    {

        return new Tree(new DealerMenu(), function (Tree $tree) {
            $tree->disableCreateButton();
            $tree->disableQuickCreateButton();
            $tree->disableEditButton();
            $tree->maxDepth(3);

            $tree->actions(function (Tree\Actions $actions) {
                if ($actions->getRow()->extension) {
                    $actions->disableDelete();
                }

                $actions->prepend(new Show());
            });

            $tree->branch(function ($branch) {
                $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

                if (! isset($branch['children'])) {
                    if (url()->isValidUrl($branch['uri'])) {
                        $uri = $branch['uri'];
                    } else {
                        $uri = admin_prefix_path($branch['uri'], config('dealer.route.prefix'));
                    }

                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }

                return $payload;
            });
        });
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $relations = DealerMenu::withPermission() ? ['permissions', 'roles'] : 'roles';

        return Form::make(Menu::with($relations), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });

            $form->display('id', 'ID');

            $form->select('parent_id', trans('admin.parent_id'))->options(function () {
                return DealerMenu::selectOptions();
            })->saving(function ($v) {
                return (int) $v;
            });
            $form->text('title', trans('admin.title'))->required();
            $form->icon('icon', trans('admin.icon'))->help($this->iconHelp());
            $form->text('uri', trans('admin.uri'));
            $form->switch('show', trans('admin.show'));

            if (DealerMenu::withRole()) {
                $form->multipleSelect('roles', trans('admin.roles'))
                    ->options(function () {
                        $roleModel = config('dealer.database.roles_model');

                        return $roleModel::all()->pluck('name', 'id');
                    })
                    ->customFormat(function ($v) {
                        return array_column($v, 'id');
                    });
            }
            if (DealerMenu::withPermission()) {
                $form->tree('permissions', trans('admin.permission'))
                    ->nodes(function () {
                        $permissionModel = config('dealer.database.permissions_model');

                        return (new $permissionModel())->allNodes();
                    })
                    ->customFormat(function ($v) {
                        if (! $v) {
                            return [];
                        }

                        return array_column($v, 'id');
                    });
            }

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        })->saved(function (Form $form, $result) {
            $response = $form->response()->location('dealer/menu');

            if ($result) {
                return $response->success(__('admin.save_succeeded'));
            }

            return $response->info(__('admin.nothing_updated'));
        });
    }
}
