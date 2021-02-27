<?php

namespace App\Admin\Repositories\Dealer;

use App\Models\Dealer\Menu as Model;
use Dcat\Admin\Http\Repositories\Menu as DcatMenu;

/**
 * 数据仓库：Admin应用里管理Dealer的菜单
 */
class Menu extends DcatMenu
{
    protected function initModel($modelOrRelations)
    {
        $this->eloquentClass = Model::class;
        
        parent::initModel($modelOrRelations);
    }
}
