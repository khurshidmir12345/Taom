<?php

namespace Database\Factories;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foods = [
            [
                'name_uz' => 'Osh',
                'description' => 'An\'anaviy o\'zbek taomi, guruch va sabzavotlar bilan tayyorlanadi.',
                'image' => 'foods/osh.jpg'
            ],
            [
                'name_uz' => 'Manti',
                'description' => 'Xamir ichiga qiyma solib tayyorlanadigan taom.',
                'image' => 'foods/manti.jpg'
            ],
            [
                'name_uz' => 'Lag\'mon',
                'description' => 'Ugra va sabzavotlar bilan tayyorlanadigan taom.',
                'image' => 'foods/lagmon.jpg'
            ],
            [
                'name_uz' => 'Somsa',
                'description' => 'Xamir ichiga qiyma yoki kartoshka solib tayyorlanadi.',
                'image' => 'foods/somsa.jpg'
            ],
            [
                'name_uz' => 'Chuchvara',
                'description' => 'Xamir ichiga qiyma solib tayyorlanadigan taom.',
                'image' => 'foods/chuchvara.jpg'
            ],
            [
                'name_uz' => 'Mastava',
                'description' => 'Guruch va sabzavotlar bilan tayyorlanadigan suyuq taom.',
                'image' => 'foods/mastava.jpg'
            ],
            [
                'name_uz' => 'Dimlama',
                'description' => 'Go\'sht va sabzavotlar bilan tayyorlanadigan taom.',
                'image' => 'foods/dimlama.jpg'
            ],
            [
                'name_uz' => 'Qozon Kabob',
                'description' => 'Qozonda tayyorlanadigan kabob turi.',
                'image' => 'foods/qozon_kabob.jpg'
            ],
            [
                'name_uz' => 'Norin',
                'description' => 'Xom go\'sht va ugradan tayyorlanadigan taom.',
                'image' => 'foods/norin.jpg'
            ],
            [
                'name_uz' => 'Moshxo\'rda',
                'description' => 'Mosh va guruchdan tayyorlanadigan suyuq taom.',
                'image' => 'foods/moshxorda.jpg'
            ],
            [
                'name_uz' => 'Shashlik',
                'description' => 'Go\'shtdan tayyorlanadigan taom.',
                'image' => 'foods/shashlik.jpg'
            ],
            [
                'name_uz' => 'Qovurma',
                'description' => 'Go\'sht va sabzavotlar bilan tayyorlanadigan taom.',
                'image' => 'foods/qovurma.jpg'
            ],
            [
                'name_uz' => 'Mastava',
                'description' => 'Guruch va sabzavotlar bilan tayyorlanadigan suyuq taom.',
                'image' => 'foods/mastava.jpg'
            ],
            [
                'name_uz' => 'Sho\'rva',
                'description' => 'Go\'sht va sabzavotlar bilan tayyorlanadigan suyuq taom.',
                'image' => 'foods/shorva.jpg'
            ],
            [
                'name_uz' => 'Qutab',
                'description' => 'Xamir ichiga qiyma yoki sabzavot solib tayyorlanadi.',
                'image' => 'foods/qutab.jpg'
            ],
            [
                'name_uz' => 'Baliq',
                'description' => 'Baliqdan tayyorlanadigan taom.',
                'image' => 'foods/baliq.jpg'
            ],
            [
                'name_uz' => 'Tovuq',
                'description' => 'Tovuq go\'shtidan tayyorlanadigan taom.',
                'image' => 'foods/tovuq.jpg'
            ],
            [
                'name_uz' => 'Mastava',
                'description' => 'Guruch va sabzavotlar bilan tayyorlanadigan suyuq taom.',
                'image' => 'foods/mastava.jpg'
            ],
            [
                'name_uz' => 'Qozon Kabob',
                'description' => 'Qozonda tayyorlanadigan kabob turi.',
                'image' => 'foods/qozon_kabob.jpg'
            ],
            [
                'name_uz' => 'Norin',
                'description' => 'Xom go\'sht va ugradan tayyorlanadigan taom.',
                'image' => 'foods/norin.jpg'
            ]
        ];

        $food = $foods[array_rand($foods)];

        return [
            'name_uz' => $food['name_uz'],
            'description' => $food['description'],
            'image' => $food['image'],
            'category_id' => rand(1, 3), // Assuming you have 3 categories
        ];
    }
}
