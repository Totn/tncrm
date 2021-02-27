<?php

namespace App\Admin\Repositories\Dealer;

use App\Models\Dealer\Permission as Model;
use Dcat\Admin\Http\Repositories\Permission as DcatPermission;

/**
 * 数据仓库：Admin应用里管理Dealer的权限
 */
class Permission extends DcatPermission
{
    public function initModel($modelOrRelations = [])
    {
        $this->eloquentClass = Model::class;

        parent::initModel($modelOrRelations);
    }
}
