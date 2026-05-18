<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Антикваріат' => ['Монети', 'Старовинні меблі', 'Порцеляна'],
            'Електроніка' => ['Телефони', 'Ноутбуки', 'Фототехніка'],
            'Мистецтво' => ['Картини', 'Скульптура'],
            'Книги та автографи' => [],
            'Прикраси' => ['Золото', 'Срібло'],
            'Інструменти' => [],
            'Колекційні' => ['Марки', 'Винні етикетки'],
            'Спорттовари' => [],
        ];

        foreach ($tree as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'parent_id' => null,
                'description' => "Розділ «{$parentName}» — лоти даної категорії.",
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($parentName.' '.$childName),
                    'parent_id' => $parent->id,
                    'description' => "{$childName} ({$parentName}).",
                ]);
            }
        }
    }
}
