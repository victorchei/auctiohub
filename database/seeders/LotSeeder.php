<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Lot;
use App\Models\LotImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LotSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'user')->get();
        $leafCategories = Category::whereNotNull('parent_id')->get();
        if ($leafCategories->isEmpty()) {
            $leafCategories = Category::all();
        }

        // [uk, en]
        $titles = [
            ['Монета СРСР 1961 року',                   'USSR Coin 1961'],
            ['iPhone 14 Pro Max 256 GB',                'iPhone 14 Pro Max 256 GB'],
            ['Картина олією "Захід сонця"',             'Oil Painting "Sunset"'],
            ['Книга з підписом автора',                 'Author-Signed Book'],
            ['Перстень із гранатом',                    'Garnet Ring'],
            ['Дрель-шуруповерт Makita',                 'Makita Cordless Drill'],
            ['Карта виноградників 1908 р.',             'Vineyard Map 1908'],
            ['Тенісна ракетка Wilson Pro Staff',        'Wilson Pro Staff Tennis Racket'],
            ['Старовинна скриня',                       'Antique Chest'],
            ['Колекція марок СРСР 1970-х',              'USSR Stamps Collection 1970s'],
            ['Зеркальна камера Canon EOS R6',           'Canon EOS R6 Mirrorless Camera'],
            ['Намисто з перлами',                       'Pearl Necklace'],
            ['Стіл XIX століття',                       '19th Century Table'],
            ['Підставка для книг',                      'Bookstand'],
            ['Світлини Парижа 1960 р.',                 'Paris Photos 1960'],
            ['Лижі гоночні Atomic',                     'Atomic Racing Skis'],
            ['Скрипка ручної роботи',                   'Handcrafted Violin'],
            ['Підручник з фізики 1955',                 'Physics Textbook 1955'],
            ['Браслет золотий',                         'Gold Bracelet'],
            ['Електрогітара Fender Stratocaster',       'Fender Stratocaster Electric Guitar'],
            ['Кубок чемпіонату',                        'Championship Trophy'],
            ['Атлас світу 1937 р.',                     'World Atlas 1937'],
            ['Фарфорова чашка з рожами',                'Porcelain Cup with Roses'],
            ['Шахи з кістки',                           'Bone Chess Set'],
            ['Бінокль морський',                        'Marine Binoculars'],
            ['Малюнок в стилі модерн',                  'Art Nouveau Drawing'],
            ['Книга кулінарних рецептів',               'Cookbook'],
            ['Сережки з топазом',                       'Topaz Earrings'],
            ['Швейна машинка Singer',                   'Singer Sewing Machine'],
            ['Гарпун для риболовлі',                    'Fishing Harpoon'],
            ['Поштова марка 1925 року',                 'Postage Stamp 1925'],
            ['Колекційна модель авто',                  'Collectible Car Model'],
            ['Старовинні гральні карти',                'Antique Playing Cards'],
            ['Пейзаж акварель',                         'Watercolour Landscape'],
            ['Хайкінгові черевики Lowa',                'Lowa Hiking Boots'],
            ['Інструменти столярні в наборі',           'Carpentry Tool Set'],
            ['Японська лялька',                         'Japanese Doll'],
            ['Фотоальбом сімейний',                     'Family Photo Album'],
            ['Кольє з аквамарином',                     'Aquamarine Necklace'],
            ["Колекційний фломастер Caran d'Ache",      "Caran d'Ache Collector's Marker"],
            ['Тарілка з гербом',                        'Coat-of-Arms Plate'],
            ['Скейтборд професійний',                   'Professional Skateboard'],
            ['Стара мапа Києва',                        'Old Map of Kyiv'],
            ['Фонограф Edison',                         'Edison Phonograph'],
            ['Зошит з конспектами математика',          'Mathematician\'s Notebook'],
            ['Гольф-клуб набір',                        'Golf Club Set'],
            ['Картка футболіста 1986',                  'Football Player Card 1986'],
            ['Парасоля з ручкою з дерева',              'Umbrella with Wooden Handle'],
            ['Аудіокасета унікальна',                   'Rare Audio Cassette'],
            ['Перо для каліграфії',                     'Calligraphy Pen'],
            ['Сувенірна табличка',                      'Souvenir Plaque'],
        ];

        $totalLots = min(count($titles), 50);
        $now = now();

        for ($i = 0; $i < $totalLots; $i++) {
            [$title, $titleEn] = $titles[$i];
            $seller = $sellers->random();
            $category = $leafCategories->random();
            $startingPrice = fake()->randomFloat(2, 50, 5000);
            $startsAt = fake()->dateTimeBetween('-10 days', '-1 day');

            $variant = $i % 4;
            $endsAt = match ($variant) {
                0 => fake()->dateTimeBetween('+1 hour', '+3 days'),
                1 => fake()->dateTimeBetween('+3 days', '+10 days'),
                2 => fake()->dateTimeBetween('-5 days', '-1 day'),
                default => fake()->dateTimeBetween('+5 minutes', '+30 minutes'),
            };
            $status = $endsAt < $now ? 'ended' : 'active';

            $lot = Lot::create([
                'seller_id' => $seller->id,
                'category_id' => $category->id,
                'title'    => $title,
                'title_en' => $titleEn,
                'slug'     => Str::slug($title).'-'.($i + 1),
                'description' => fake()->paragraphs(3, true),
                'starting_price' => $startingPrice,
                'current_price' => $startingPrice,
                'bid_increment' => fake()->randomElement([5, 10, 25, 50, 100]),
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => $status,
                'cover_image_path' => null,
            ]);

            for ($j = 1; $j <= fake()->numberBetween(1, 4); $j++) {
                LotImage::create([
                    'lot_id' => $lot->id,
                    'path' => "lots/placeholder-".fake()->numberBetween(1, 10).".svg",
                    'sort_order' => $j,
                ]);
            }

            $bidsCount = fake()->numberBetween(2, 8);
            $currentPrice = $startingPrice;
            $bidders = $sellers->where('id', '!=', $seller->id)->random(min($bidsCount, $sellers->count() - 1));
            $bidderArray = $bidders->values();
            $time = clone $startsAt;
            $lastBidderId = null;

            for ($k = 0; $k < $bidsCount && $k < $bidderArray->count(); $k++) {
                $currentPrice += fake()->randomFloat(2, 5, 100);
                $time = (clone $time)->modify('+'.fake()->numberBetween(10, 360).' minutes');
                if ($time > $endsAt) {
                    break;
                }
                $bidder = $bidderArray[$k];
                Bid::create([
                    'lot_id' => $lot->id,
                    'user_id' => $bidder->id,
                    'amount' => $currentPrice,
                    'placed_at' => $time,
                ]);
                $lastBidderId = $bidder->id;
            }

            $lot->update([
                'current_price' => $currentPrice,
                'winner_id' => $status === 'ended' ? $lastBidderId : null,
            ]);

            for ($c = 0; $c < fake()->numberBetween(0, 3); $c++) {
                Comment::create([
                    'lot_id' => $lot->id,
                    'user_id' => $sellers->random()->id,
                    'parent_id' => null,
                    'body' => fake()->sentence(fake()->numberBetween(5, 15)),
                ]);
            }
        }
    }
}
