<?php

use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $fruits = array("Apple","Apricot","Avocado","Banana","Bilberry","Blackberry","Blackcurrant","Blueberry","Boysenberry","Currant","Cherry","Cherimoya","Chico fruit","Cloudberry","Coconut","Cranberry","Cucumber","Custard apple","Damson","Date","Dragonfruit","Durian","Elderberry","Feijoa","Fig","Goji berry","Gooseberry","Grape","Raisin","Grapefruit","Guava","Honeyberry","Huckleberry","Jabuticaba","Jackfruit","Jambul","Jujube","Juniper berry","Kiwano","Kiwifruit","Kumquat","Lemon","Lime","Loquat","Longan","Lychee","Mango","Mangosteen","Marionberry","Melon","Cantaloupe","Honeydew","Watermelon","Miracle fruit","Mulberry","Nectarine","Nance","Olive","Orange","Blood orange","Clementine","Mandarine","Tangerine","Papaya","Passionfruit","Peach","Pear","Persimmon","Physalis","Plantain","Plum","Prune","Pineapple","Plumcot","Pomegranate","Pomelo","Purple mangosteen","Quince","Raspberry","Salmonberry","Rambutan","Redcurrant","Salal berry","Salak","Satsuma","Soursop","Star fruit","Solanum quitoense","Strawberry","Tamarillo","Tamarind","Ugli fruit","Yuzu");
        $vegies = array("Asparagus","Avocado","BBQ vegies","Bean","Beetroot","Beetroot and potato salad","Bok choy, pak choy or Chinese chard","Broccoli","Brussels sprouts","Butternut pumpkin in orange ","Cabbage","Capsicum","Carrot","Carrot and parsnip muffins","Cauliflower","Cauliflower and broccoli gratin","Celeriac","Celery","Chickpea and couscous salad","Chinese broccoli or gai lan","Chinese cabbage or wong bok","Citrus coleslaw","Corn or sweet corn","Crunchy Waldorf salad","Cucumber","Eggplant, aubergine or brinjal","Eggplant dip","Fennel","Greek salad","Guacamole","Honeyed greens","Italian pasta salad","Lettuce","Mexican corn and tomato salad","Mushroom","Mustard and honey corn on the cob","Onion","Parsnip","Peas","Potato","Potato caesar salad","Pumpkin","Radish","Ratatouille","Salsa","Sesame brussels sprouts","Spinach","Squash","Swede","Sweet potato or kumera","Tabbouleh","Thai beef salad","Thai noodle salad","Tomato","Tomato and garlic bruschetta","Tuna and avocado salad","Turnip","Vegetable stock","Vegie curry","Vegie lentil soup","Walnut lentil salad","Warm roasted vegetable salad","Warm squid salad","Zucchini or courgette");

        foreach ($fruits as $fruit){
            \Illuminate\Support\Facades\DB::table('item_types')->insert([
                'name' => $fruit,
                'description' => $fruit,
                'item_category_id' => 1,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        foreach ($vegies as $vege){
            \Illuminate\Support\Facades\DB::table('item_types')->insert([
                'name' => $vege,
                'description' => $vege,
                'item_category_id' => 2,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

    }
}
