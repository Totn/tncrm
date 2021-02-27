<?php

namespace App\Admin\Repositories\Dealer;

use App\Models\Dealer\Role as Model;
use Dcat\Admin\Http\Repositories\Role as DcatRole;

/**
 * 数据仓库：Admin应用里管理Dealer的角色
 */
class Role extends DcatRole
{
    protected function initModel($modelOrRelations = [])
    {
        $this->eloquentClass = Model::class;

        parent::initModel($modelOrRelations);
    }
}
