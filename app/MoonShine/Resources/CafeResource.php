<?php

namespace App\MoonShine\Resources;

use App\Models\Cafe;
use MoonShine\Laravel\Resources\ModelResource;
use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

class CafeResource extends ModelResource
{
    protected string $model = Cafe::class;

    protected string $title = 'Cafes';

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
            Text::make('Bot Token', 'bot_token'),
            Select::make('Is Paid', 'is_paid')
                ->options([
                    '0' => 'No',
                    '1' => 'Yes',
                ])
                ->readonly()
        ];
    }

    // SHOW (detail) sahifa
    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Name'),
            Text::make('Bot Token', 'bot_token'),
            Select::make('Is Paid', 'is_paid')
                ->options([
                    '0' => 'No',
                    '1' => 'Yes',
                ])
                ->readonly()
        ];
    }

    // FORM (create/edit) sahifalari
    public function formFields(): array
    {
        return [
            Text::make('Name')->required(),
            Text::make('Bot Token', 'bot_token')->required(),
            Select::make('Is Paid', 'is_paid')
                ->options([
                    '1' => 'Yes',
                    '0' => 'No',
                ])
                ->default('1'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [

        ];
    }
}
