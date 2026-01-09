<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id');

        $items = [
            [
                'item' => [
                    'name' => '腕時計',
                    'price' => 15000,
                    'brand' => 'Rolax',
                    'description' => 'スタイリッシュなデザインのメンズ腕時計',
                    'image_path' => 'items/MensClock.jpg',
                    'condition' => 1,
                    'status' => rand(1, 2)
                ],
                'categories' => [1,5,12],
            ],
            [
                'item' => [
                    'name' => 'HDD',
                    'price' => 5000,
                    'brand' => '西芝',
                    'description' => '高速で信頼性の高いハードディスク',
                    'image_path' => 'items/HDDHardDisk.jpg',
                    'condition' => 2,
                    'status' => rand(1, 2)
                ],
                'categories' => [2],
            ],
            [
                'item' => [
                    'name' => '玉ねぎ3束',
                    'price' => 300,
                    'brand' => 'なし',
                    'description' => '新鮮な玉ねぎ3束のセット',
                    'image_path' => 'items/Onion.jpg',
                    'condition' => 3,
                    'status' => rand(1, 2)
                ],
                'categories' => [10],
            ],
            [
                'item' => [
                    'name' => '革靴',
                    'price' => 4000,
                    'description' => 'クラシックなデザインの革靴',
                    'image_path' => 'items/LeatherShoes.jpg',
                    'condition' => 4,
                    'status' => rand(1, 2)
                ],
                'categories' => [1],
            ],
            [
                'item' => [
                    'name' => 'ノートPC',
                    'price' => 45000,
                    'description' => '高性能なノートパソコン',
                    'image_path' => 'items/Laptop.jpg',
                    'condition' => 1,
                    'status' => rand(1, 2)
                ],
                'categories' => [2],
            ],
            [
                'item' => [
                    'name' => 'マイク',
                    'price' => 8000,
                    'brand' => 'なし',
                    'description' => '高音質のレコーディング用マイク',
                    'image_path' => 'items/MusicMic.jpg',
                    'condition' => 2,
                    'status' => rand(1, 2)
                ],
                'categories' => [2],
            ],
            [
                'item' => [
                    'name' => 'ショルダーバッグ',
                    'price' => 3500,
                    'description' => 'おしゃれなショルダーバッグ',
                    'image_path' => 'items/shoulderBag.jpg',
                    'condition' => 3,
                    'status' => rand(1, 2)
                ],
                'categories' => [1],
            ],
            [
                'item' => [
                    'name' => 'タンブラー',
                    'price' => 500,
                    'brand' => 'なし',
                    'description' => '使いやすいタンブラー',
                    'image_path' => 'items/Tumbler.jpg',
                    'condition' => 4,
                    'status' => rand(1, 2)
                ],
                'categories' => [10],
            ],
            [
                'item' => [
                    'name' => 'コーヒーミル',
                    'price' => 4000,
                    'brand' => 'Starbacks',
                    'description' => '手動のコーヒーミル',
                    'image_path' => 'items/CoffeeGrinder.jpg',
                    'condition' => 1,
                    'status' => rand(1, 2)
                ],
                'categories' => [10],
            ],
            [
                'item' => [
                    'name' => 'メイクセット',
                    'price' => 2500,
                    'description' => '便利なメイクアップセット',
                    'image_path' => 'items/MakeupSet.jpg',
                    'condition' => 2,
                    'status' => rand(1, 2)
                ],
                'categories' => [6],
            ]
        ];

        foreach ($items as $data) {
            $item = Item::create(array_merge(
                $data['item'],
                ['user_id' => $userIds->random()]
            ));

            $item->categories()->attach($data['categories']);
        }
    }
}
