<?php

use App\Models\Dealer\Menu;
use Illuminate\Database\Seeder;

class DealerInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = date('Y-m-d H:i:s');
        $titles = ['id', 'parent_id', 'order', 'title', 'icon', 'uri','extension', 'show', 'created_at'];
        $menus = [
            [1, 0, 1, '主页', 'feather icon-bar-chart-2', '/', '', 1, $createdAt],
            [2, 0, 2, '系统管理', 'feather icon-settings', '', '', 1, $createdAt],
            [3, 2, 3, '用户', '', 'auth/users', '', 1, $createdAt],
        ];
        $data = [];
        foreach ($menus as $menu) {
            $data[] = array_combine($titles, $menu);
        }
        Menu::truncate();
        Menu::insert($data);
    }
}
