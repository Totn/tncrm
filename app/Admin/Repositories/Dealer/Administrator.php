<?php

namespace App\Admin\Repositories\Dealer;

use App\Models\Dealer\Administrator as Model;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Repositories\Administrator as DcatAdministrator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * 数据仓库：Admin应用里管理Dealer的用户
 */
class Administrator extends DcatAdministrator
{
    protected function initModel($modelOrRelations = [])
    {
        $this->eloquentClass = Model::class;

        parent::initModel($modelOrRelations);
    }

    public function get(Grid\Model $model)
    {
        $results = parent::get($model);

        $isPaginator = $results instanceof AbstractPaginator;

        $items = $isPaginator ? $results->getCollection() : $results;
        $items = is_array($items) ? collect($items) : $items;

        if ($items->isEmpty()) {
            return $results;
        }

        $roleModel = config('dealer.database.roles_model');

        $roleKeyName = (new $roleModel())->getKeyName();

        $roleIds = $items
            ->pluck('roles')
            ->flatten(1)
            ->pluck($roleKeyName)
            ->toArray();

        $permissions = $roleModel::getPermissionId($roleIds);

        if (! $permissions->isEmpty()) {
            $items = $items->map(function ($v) use ($roleKeyName, $permissions) {
                $v['permissions'] = [];

                foreach ($v['roles']->pluck($roleKeyName) as $roleId) {
                    $v['permissions'] = array_merge($v['permissions'], $permissions->get($roleId, []));
                }

                $v['permissions'] = array_unique($v['permissions']);

                return $v;
            });
        }

        if ($isPaginator) {
            $results->setCollection($items);

            return $results;
        }

        return $items;
    }
}
