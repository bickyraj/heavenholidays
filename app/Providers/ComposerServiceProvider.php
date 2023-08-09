<?php

namespace App\Providers;

use App\Menu;
use App\MenuItem;
use App\Trip;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer(
            '*', 'App\Http\View\Composers\MenuComposer'
        );

        View::composer('front.trips.show', function($view) {
            $menu = Menu::where('slug', '=', 'essential-trip-information')->first();
            $essential_trip_informations = [];
            if ($menu) {
                $essential_trip_informations = MenuItem::where('menu_id', '=', $menu->id)->get();
            }

            $monthly_trips = Trip::where('block_3', 1)->latest()->get();
            $view->with([
                'essential_trip_informations' => $essential_trip_informations,
                'monthly_trips' => $monthly_trips
            ]);
        });
    }
}
