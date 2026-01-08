<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            [
                'name' => '腕時計',
                'category_id' => 4,
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'MensClock.jpg',
                'condition' => 1
            ],
            [
                'name' => 'HDD',
                'category_id' => 2,
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'HDDHardDisk.jpg',
                'condition' => 2
            ],
            [
                'name' => '玉ねぎ3束',
                'category_id' => 10,
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'Onion.jpg',
                'condition' => 3
            ],
            [
                'name' => '革靴',
                'category_id' => 1,
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'LeatherShoes.jpg',
                'condition' => 4
            ],
            [
                'name' => 'ノートPC',
                'category_id' => 2,
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image_path' => 'Laptop.jpg',
                'condition' => 1
            ],
            [
                'name' => 'マイク',
                'category_id' => 2,
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'MusicMic.jpg',
                'condition' => 2
            ],
            [
                'name' => 'ショルダーバッグ',
                'category_id' => 1,
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'shoulderBag.jpg',
                'condition' => 3
            ],
            [
                'name' => 'タンブラー',
                'category_id' => 10,
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_path' => 'Tumbler.jpg',
                'condition' => 4
            ],
            [
                'name' => 'コーヒーミル',
                'category_id' => 10,
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'CoffeeGrinder.jpg',
                'condition' => 1
            ],
            [
                'name' => 'メイクセット',
                'category_id' => 6,
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image_path' => 'MakeupSet.jpg',
                'condition' => 2
            ]
        ];

        foreach ($params as $param) {
            Item::create($param);
        }
    }
}
