<?php

namespace App\MoonShine\Resources;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class ProductCategoryResource extends ModelResource
{
    protected string $model = ProductCategory::class;

    protected string $title = 'Product Categories';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            BelongsTo::make('Cafe', 'cafe', 'name')->readonly(),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Name'),
            BelongsTo::make('Cafe', 'cafe', 'name')->readonly(),
            HasMany::make('Products', 'products', 'name', ProductResource::class),
        ];
    }

    public function formFields(): array
    {
        return [
            Text::make('Name')->required(),
            BelongsTo::make('Cafe', 'cafe', 'name', CafeResource::class)->required(),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
        ];
    }

    public function search(): array
    {
        return ['name'];
    }

    public function filters(): array
    {
        return [
            Text::make('Name'),
            BelongsTo::make('Cafe', 'cafe', 'name', resource: CafeResource::class),
        ];
    }

    public function actions(): array
    {
        return [];
    }
}
