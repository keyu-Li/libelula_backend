<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brands;
class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'name' => 'product'],
            ['id' => 2,'name' => 'order'],
            ['id' => 3,'name' => 'service'],
            ['id' => 4,'name' => 'course'],
        ];
        foreach ($items as $item) {
            Brands::create($item);
        }
    }
}
