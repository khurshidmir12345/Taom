<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\UsersResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\FoodResource;
use App\MoonShine\Resources\CategoryResource;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\CustomPage;
use App\MoonShine\Resources\CafeResource;
use App\MoonShine\Resources\BotUserResource;
use App\MoonShine\Resources\ProductCategoryResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\ProductVoteResource;
use App\MoonShine\Resources\DailyReminderResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                FoodResource::class,
                UserResource::class,
                CategoryResource::class,
                CafeResource::class,
                BotUserResource::class,
                ProductCategoryResource::class,
                ProductResource::class,
                ProductVoteResource::class,
                DailyReminderResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ]);
    }
}
