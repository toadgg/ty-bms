<?php

namespace App\Providers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

use App\AdminLte\Events\BuildingMenu;
use App\AdminLte\AdminLte;
use App\AdminLte\Http\ViewComposers\AdminLteComposer;
use App\AdminLte\Http\ViewComposers\CurrentUserComposer;


class AdminLteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdminLte::class, function (Container $app) {
            return new AdminLte(
                $app['config']['adminlte.filters'],
                $app['events'],
                $app
            );
        });
    }

    public function boot(
        Factory $view,
        Dispatcher $events,
        Repository $config
    ) {
        $this->registerViewComposers($view);
        static::registerMenu($events, $config);
    }

    public static function registerMenu(Dispatcher $events, Repository $config)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($config) {
            $menu = $config->get('adminlte.menu');
            call_user_func_array([$event->menu, 'add'], $menu);
        });
    }

    private function registerViewComposers(Factory $view)
    {
        $view->composer('adminlte.page', AdminLteComposer::class);
        $view->composer('adminlte.page', CurrentUserComposer::class);
    }

}
