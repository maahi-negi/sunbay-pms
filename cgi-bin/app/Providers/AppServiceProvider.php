<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $setting = Settings::where('status', 1)->get();
        $all_configs = [];
        foreach ($setting as $key => $value) {
            $all_configs[$value->name] = $value->value;
        }
        view()->share(compact('all_configs'));
    }
}
