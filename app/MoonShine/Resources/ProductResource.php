<?php

namespace App\MoonShine\Resources;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;


class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Products';


    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Image::make('Image')->disk('public')->dir('products')->nullable(),
            Text::make('Name'),
            Number::make('Price')->badge('primary'),
            BelongsTo::make('Category', 'category', 'name')->readonly(),
            BelongsTo::make('Cafe', 'cafe', 'name')->readonly(),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Name'),
            Number::make('Price'),
            Image::make('Image')->disk('public')->dir('products')->nullable(),
            Text::make('Description')->nullable(),
            Number::make('Likes Number')->readonly(),
            Number::make('Dislikes Number')->readonly(),
            BelongsTo::make('Category', 'category', 'name', ProductCategoryResource::class),
            BelongsTo::make('Cafe', 'cafe', 'name', CafeResource::class),
            HasMany::make('Votes', 'votes', 'type', ProductVoteResource::class),
        ];
    }

    public function formFields(): array
    {
        return [
            Text::make('Name')->required(),
            Number::make('Price')->step(0.01)->required(),
            Image::make('Image')->disk('public')->dir('products')->nullable(),
            Text::make('Description')->nullable(),
            BelongsTo::make('Category', 'category', 'name', ProductCategoryResource::class)->required(),
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
        return ['name', 'description'];
    }

    public function filters(): array
    {
        return [
            Text::make('Name'),
            BelongsTo::make('Category', 'category', 'name', resource: ProductCategoryResource::class),
            BelongsTo::make('Cafe', 'cafe', 'name', resource: CafeResource::class),
        ];
    }

    public function actions(): array
    {
        return [];
    }
}
