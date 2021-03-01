<?php

use Illuminate\Database\Seeder;

class DataInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminInitSeeder::class,
            DealerInitSeeder::class
        ]);
    }
}
