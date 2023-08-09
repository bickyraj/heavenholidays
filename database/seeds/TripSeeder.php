<?php

use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Trip::insert([
            ['name' => 'Everest Base Camp Trek', 'slug' => 'everest-base-camp-trek', 'duration' => 14, 'cost' => 3000],
            ['name' => 'Annapurna Circuit Trek', 'slug' => 'annapurna-circuit-trek', 'duration' => 21, 'cost' => 3000],
            ['name' => 'Langtang Trek', 'slug' => 'langtang-trek', 'duration' => 10, 'cost' => 2000],
            ['name' => 'Manaslu Circuit Trek', 'slug' => 'manaslu-trek', 'duration' => 19, 'cost' => 3000],
            ['name' => 'Upper Mustang Trek', 'slug' => 'upper-mustang-trek', 'duration' => 20, 'cost' => 5000],
            ['name' => 'Gokyo Lakes Trek', 'slug' => 'gokyo-lakes-trek', 'duration' => 15, 'cost' => 3000],
            ['name' => 'Rara Lake Trek', 'slug' => 'rara-lake-trek', 'duration' => 14, 'cost' => 2000],
            ['name' => 'Dhaulagiri Circuit Trek', 'slug' => 'dhaulagiri-circuit-trek', 'duration' => 22, 'cost' => 4000],
            ['name' => 'Kanchanjunga Base Camp Trek', 'slug' => 'kanchanjunga-base-camp-trek', 'duration' => 25, 'cost' => 4000],
        ]);
    }
}
