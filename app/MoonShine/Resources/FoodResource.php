<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Food;
use App\MoonShine\Pages\Food\FoodIndexPage;
use App\MoonShine\Pages\Food\FoodFormPage;
use App\MoonShine\Pages\Food\FoodDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
 * @extends ModelResource<Food, FoodIndexPage, FoodFormPage, FoodDetailPage>
 */
class FoodResource extends ModelResource
{
    protected string $model = Food::class;

    protected string $title = 'Food';
    
    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            FoodIndexPage::class,
            FoodFormPage::class,
            FoodDetailPage::class,
        ];
    }

    /**
     * @param Food $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
