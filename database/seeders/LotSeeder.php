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

        $titles = [
            'Монета СРСР 1961 року', 'iPhone 14 Pro Max 256 GB', 'Картина олією "Захід сонця"',
            'Книга з підписом автора', 'Перстень із гранатом', 'Дрель-шуруповерт Makita',
            'Карта виноградників 1908 р.', 'Тенісна ракетка Wilson Pro Staff', 'Старовинна скриня',
            'Колекція марок СРСР 1970-х', 'Зеркальна камера Canon EOS R6', 'Намисто з перлами',
            'Стіл XIX століття', 'Підставка для книг', 'Світлини Парижа 1960 р.',
            'Лижі гоночні Atomic', 'Скрипка ручної роботи', 'Підручник з фізики 1955',
            'Браслет золотий', 'Електрогітара Fender Stratocaster', 'Кубок чемпіонату',
            'Атлас світу 1937 р.', 'Фарфорова чашка з рожами', 'Шахи з кістки',
            'Бінокль морський', 'Малюнок в стилі модерн', 'Книга кулінарних рецептів',
            'Сережки з топазом', 'Швейна машинка Singer', 'Гарпун для риболовлі',
            'Поштова марка 1925 року', 'Колекційна модель авто', 'Старовинні гральні карти',
            'Пейзаж акварель', 'Хайкінгові черевики Lowa', 'Інструменти столярні в наборі',
            'Японська лялька', 'Фотоальбом сімейний', 'Кольє з аквамарином',
            'Колекційний фломастер Caran d\'Ache', 'Тарілка з гербом', 'Скейтборд професійний',
            'Стара мапа Києва', 'Фонограф Edison', 'Зошит з конспектами математика',
            'Гольф-клуб набір', 'Картка футболіста 1986', 'Парасоля з ручкою з дерева',
            'Аудіокасета унікальна', 'Перо для каліграфії', 'Сувенірна табличка',
        ];

        $totalLots = min(count($titles), 50);
        $now = now();

        for ($i = 0; $i < $totalLots; $i++) {
            $title = $titles[$i];
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
                'title' => $title,
                'slug' => Str::slug($title).'-'.($i + 1),
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
                    'path' => "lots/placeholder-".fake()->numberBetween(1, 10).".jpg",
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
