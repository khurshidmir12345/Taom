<?php

namespace App\MoonShine\Resources;

use App\Models\ProductVote;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;

class ProductVoteResource extends ModelResource
{
    protected string $model = ProductVote::class;

    protected string $title = 'Product Votes';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Bot User', 'botUser', 'user_name')->readonly(),
            BelongsTo::make('Product', 'product', 'name')->readonly(),
            Select::make('Type')->options([
                'like' => 'Like',
                'dislike' => 'Dislike',
            ])->readonly(),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('Bot User', 'botUser', 'user_name', BotUserResource::class),
            BelongsTo::make('Product', 'product', 'name', ProductResource::class),
            Select::make('Type')->options([
                'like' => 'Like',
                'dislike' => 'Dislike',
            ])
        ];
    }

    public function formFields(): array
    {
        return [
            BelongsTo::make('Bot User', 'botUser', 'user_name', BotUserResource::class)->required(),
            BelongsTo::make('Product', 'product', 'name', ProductResource::class)->required(),
            Select::make('Type')->options([
                'like' => 'Like',
                'dislike' => 'Dislike',
            ])->required(),
        ];
    }


    public function rules(mixed $item): array
    {
        return [
        ];
    }

    public function search(): array
    {
        return ['type'];
    }

    public function filters(): array
    {
        return [
            Select::make('Type')
                ->options([
                    'like' => 'Like',
                    'dislike' => 'Dislike'
                ]),
            BelongsTo::make('Bot User', 'botUser', 'user_name', resource: BotUserResource::class),
            BelongsTo::make('Product', 'product', 'name', resource: ProductResource::class),
        ];
    }

    public function actions(): array
    {
        return [];
    }
}
