<?php

use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Models\Menu;
use Dcat\Admin\Models\Permission;
use Dcat\Admin\Models\Role;
use Illuminate\Database\Seeder;

class AdminInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $createdAt = date('Y-m-d H:i:s');

        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username'   => 'admin',
            'password'   => bcrypt('admin'),
            'name'       => 'Administrator',
            'created_at' => $createdAt,
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name'       => 'Administrator',
            'slug'       => Role::ADMINISTRATOR,
            'created_at' => $createdAt,
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        $titles = ['id', 'name', 'slug', 'http_method', 'http_path', 'parent_id', 'order', 'created_at'];
        $perms = [
            [1, '系统管理', 'auth-management', '', '', 0, 1, $createdAt],
            [2, '管理员', 'users', '', '/auth/users*', 1, 2, $createdAt],
            [3, '角色', 'roles', '', '/auth/roles*', 1, 3, $createdAt],
            [4, '权限', 'permissions', '', '/auth/permissions*', 1, 4, $createdAt],
            [5, '菜单', 'menu', '', '/auth/menu*', 1, 5, $createdAt],
            [6, '扩展', 'extension', '', '/auth/extensions*', 1, 6, $createdAt]
        ];
        $data = [];
        foreach ($perms as $perm) {
            $data[] = array_combine($titles, $perm);
        }
        Permission::insert($data);

//        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        $titles = ['id', 'parent_id', 'order', 'title', 'icon', 'uri','extension', 'show', 'created_at'];
        $menus = [
            [1, 0, 1, '首页', 'feather icon-bar-chart-2', '/', '', 1, $createdAt],
            [2, 0, 2, '系统管理', 'feather icon-settings', '', '', 1, $createdAt],
            [3, 2, 3, '用户', '', 'auth/users', '', 1, $createdAt],
            [4, 2, 4, '角色', '', 'auth/roles', '', 1, $createdAt],
            [5, 2, 5, '权限', '', 'auth/permissions', '', 1, $createdAt],
            [6, 2, 6, '菜单', '', 'auth/menu', '', 1, $createdAt],
            [7, 2, 7, 'Extensions', '', 'auth/extensions', '', 1, $createdAt],
            [8, 0, 8, '经销商', 'fa-android', '\N', '', 1, $createdAt],
            [9, 8, 9, '列表', 'fa-th-list', 'dealers', '', 1, $createdAt],
            [10, 8, 10, '用户', 'fa-user', 'dealer/users', '', 1, $createdAt],
            [11, 8, 11, '角色', 'fa-at', 'dealer/roles', '', 1, $createdAt],
            [12, 8, 12, '权限', 'fa-superpowers', 'dealer/permissions', '', 1, $createdAt],
            [13, 8, 13, '菜单', 'fa-list-alt', 'dealer/menu', '', 1, $createdAt],
        ];
        $data = [];
        foreach ($menus as $menu) {
            $data[] = array_combine($titles, $menu);
        }
        Menu::insert($data);

        (new Menu())->flushCache();
    }
}
