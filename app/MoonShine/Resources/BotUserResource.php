<?php

namespace App\MoonShine\Resources;

use App\Models\BotUser;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

class BotUserResource extends ModelResource
{
    protected string $model = BotUser::class;

    protected string  $title = 'Bot Users';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Chat ID', 'chat_id'),
            Text::make('Username', 'user_name'),
            Text::make('First Name', 'first_name'),
            BelongsTo::make('Cafe', 'cafe', 'name'),
        ];
    }

    // DETAIL (show page)
    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Chat ID', 'chat_id'),
            Text::make('Username', 'user_name'),
            Text::make('First Name', 'first_name'),
            Text::make('Last Name', 'last_name'),
            Number::make('Last Message ID', 'last_message_id'),
            BelongsTo::make('Cafe', 'cafe', 'name'),
            Text::make('Step', 'step'),
            HasMany::make('Votes', 'votes', 'type', ProductVoteResource::class),
        ];
    }

    // FORM (create/edit)
    public function formFields(): array
    {
        return [
            Text::make('Chat ID', 'chat_id')->required(),
            Text::make('Username', 'user_name')->nullable(),
            Text::make('First Name', 'first_name')->nullable(),
            Text::make('Last Name', 'last_name')->nullable(),
            Number::make('Last Message ID', 'last_message_id')->nullable(),
            BelongsTo::make('Cafe', 'cafe', 'name')->required(),
            Text::make('Step', 'step')->default('start'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
        ];
    }

    public function search(): array
    {
        return ['chat_id', 'user_name', 'first_name', 'last_name'];
    }

    public function filters(): array
    {
        return [
            Text::make('Chat ID'),
            Text::make('Username'),
            BelongsTo::make('Cafe', 'cafe', 'name', CafeResource::class),
        ];
    }

    public function actions(): array
    {
        return [];
    }
}
