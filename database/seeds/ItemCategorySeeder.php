<?php

use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_categories')->insert([
            'id' => 1,
            'name' => "Fruit",
            'description' => "Fruits",
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('item_categories')->insert([
            'id' => 2,
            'name' => "Vegetable",
            'description' => "Vegetables",
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
