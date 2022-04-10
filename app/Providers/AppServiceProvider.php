<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;  //to fix migration error (Specified key was too long; max key length is 767 bytes
use Laravel\Passport\Passport; // Passport Api

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
        //
		Schema::defaultStringLength(191); //to fix migration error (Specified key was too long; max key length is 767 bytes
		Passport::routes(); // Passport Api
 
    }
}
