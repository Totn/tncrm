<?php

namespace App\Models\Dealer;

use Dcat\Admin\Models\Permission as DcatPermission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 模型：Dealer的权限
 */
class Permission extends DcatPermission
{
    public function setTable($table)
    {
        $this->table = 'dealer_permissions';
    }

    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'dealer_role_permissions', 'permission_id', 'role_id');
    }
}
