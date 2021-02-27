<?php

namespace App\Models\Dealer;

use Dcat\Admin\Models\Role as DcatRole;

/**
 * 模型：Dealer的角色
 */
class Role extends DcatRole
{
	const ADMINISTRATOR = 'dearadmin';

    public function setTable($table)
    {
        $this->table  = 'dealer_roles';
    }
}
