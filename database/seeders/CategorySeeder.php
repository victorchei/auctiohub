<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // [uk => en] with children [uk => en]
        $tree = [
            ['Антикваріат', 'Antiques', [
                ['Монети', 'Coins'],
                ['Старовинні меблі', 'Antique Furniture'],
                ['Порцеляна', 'Porcelain'],
            ]],
            ['Електроніка', 'Electronics', [
                ['Телефони', 'Phones'],
                ['Ноутбуки', 'Laptops'],
                ['Фототехніка', 'Camera Equipment'],
            ]],
            ['Мистецтво', 'Art', [
                ['Картини', 'Paintings'],
                ['Скульптура', 'Sculpture'],
            ]],
            ['Книги та автографи', 'Books & Autographs', []],
            ['Прикраси', 'Jewellery', [
                ['Золото', 'Gold'],
                ['Срібло', 'Silver'],
            ]],
            ['Інструменти', 'Tools', []],
            ['Колекційні', 'Collectibles', [
                ['Марки', 'Stamps'],
                ['Винні етикетки', 'Wine Labels'],
            ]],
            ['Спорттовари', 'Sporting Goods', []],
        ];

        foreach ($tree as [$parentName, $parentNameEn, $children]) {
            $parent = Category::create([
                'name'        => $parentName,
                'name_en'     => $parentNameEn,
                'slug'        => Str::slug($parentName),
                'parent_id'   => null,
                'description' => "Розділ «{$parentName}» — лоти даної категорії.",
            ]);

            foreach ($children as [$childName, $childNameEn]) {
                Category::create([
                    'name'      => $childName,
                    'name_en'   => $childNameEn,
                    'slug'      => Str::slug($parentName.' '.$childName),
                    'parent_id' => $parent->id,
                    'description' => "{$childName} ({$parentName}).",
                ]);
            }
        }
    }
}
