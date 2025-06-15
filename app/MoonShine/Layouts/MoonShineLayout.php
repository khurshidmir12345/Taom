<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\FoodResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\Contracts\ColorManager\ColorManagerContract;

// Resurslar
use App\MoonShine\Resources\CafeResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\ProductCategoryResource;
use App\MoonShine\Resources\BotUserResource;
use MoonShine\MenuManager\MenuDivider;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Menyu bot system', [
                MenuItem::make('Choyxona', CafeResource::class),
                MenuItem::make('Maxsulotlar', ProductResource::class),
                MenuItem::make('Kategoriyalar', ProductCategoryResource::class),
                MenuItem::make('Bot foydalanuvchilari', BotUserResource::class),
                MenuDivider::make(),
            ]),

            MenuGroup::make('Random food system', [
                MenuItem::make('User', UserResource::class),
                MenuItem::make('Category', CategoryResource::class),
                MenuItem::make('Food', FoodResource::class),
                MenuDivider::make(),
            ]),

            ...parent::menu(),
        ];
    }

    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // Ranglar sozlashni hohlasangiz shu yerga yozing, masalan:
        // $colorManager->primary('#3B82F6');
    }
}
