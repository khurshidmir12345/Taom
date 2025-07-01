<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use MoonShine\UI\Fields\ID;
use App\Models\DailyReminder;

use MoonShine\UI\Fields\Text;
use MoonShine\UI\Components\Boolean;
use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<DailyReminder>
 */
class DailyReminderResource extends ModelResource
{
    protected string $model = DailyReminder::class;

    protected string $title = 'DailyReminders';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Day'),
            Textarea::make('Message'),
            Text::make('Time'),
            Switcher::make('Is Active'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
             ID::make()->sortable(),
            Text::make('Day'),
            Textarea::make('Message'),
            Text::make('Time'),
            Switcher::make('Is Active'),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
              ID::make()->sortable(),
            Text::make('Day'),
            Textarea::make('Message'),
            Text::make('Time'),
            Switcher::make('Is Active'),
        ];
    }

    /**
     * @param DailyReminder $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
