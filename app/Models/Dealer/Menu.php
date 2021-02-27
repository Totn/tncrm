<?php

namespace App\Models\Dealer;

use Dcat\Admin\Models\Menu as DcatMenu;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 模型：Dealer的菜单
 */
class Menu extends DcatMenu
{

    public function setTable($table)
    {
        $this->table = 'dealer_menu';
    }

    /**
     * Determine if enable menu bind role.
     *
     * @return bool
     */
    public static function withRole()
    {
        return (bool) config('dealer.permission.enable');
    }

    /**
     * Determine if enable menu bind permission.
     *
     * @return bool
     */
    public static function withPermission()
    {
        return config('dealer.menu.bind_permission') && config('dealer.permission.enable');
    }
    /**
     * A Menu belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'dealer_role_menu', 'menu_id', 'role_id')->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'dealer_permission_menu', 'menu_id', 'permission_id')->withTimestamps();
    }
}
