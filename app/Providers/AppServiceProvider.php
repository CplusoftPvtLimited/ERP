<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\AssemblyGroupNodesRepository;
use App\Repositories\Interfaces\ArticleInterface;
use App\Repositories\Interfaces\AssemblyGroupNodeInterface;
use App\Repositories\Interfaces\LinkageTargetInterface;
use App\Repositories\AmBrandRepository;
use App\Repositories\ArticleCriteriaRepository;
use App\Repositories\ArticleCrossesRepository;
use App\Repositories\ArticleEanRepository;
use App\Repositories\ArticleLinksRepository;
use App\Repositories\Interfaces\AmBrandInterface;
use App\Repositories\Interfaces\ArticleCriteriaInterface;
use App\Repositories\Interfaces\ArticleCrossesInterface;
use App\Repositories\Interfaces\ArticleEanInterface;
use App\Repositories\Interfaces\ArticleLinksInterface;
use App\Repositories\Interfaces\ManufacturerInterface;
use App\Repositories\LinkageTargetRepository;
use App\Repositories\ManufacturerRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ManufacturerInterface::class,ManufacturerRepository::class);
        $this->app->bind(LinkageTargetInterface::class,LinkageTargetRepository::class);
        $this->app->bind(AssemblyGroupNodeInterface::class,AssemblyGroupNodesRepository::class);
        $this->app->bind(ArticleInterface::class,ArticleRepository::class);
        $this->app->bind(AmBrandInterface::class,AmBrandRepository::class);
        $this->app->bind(ArticleCriteriaInterface::class,ArticleCriteriaRepository::class);
        $this->app->bind(ArticleCrossesInterface::class,ArticleCrossesRepository::class);
        $this->app->bind(ArticleEanInterface::class,ArticleEanRepository::class);
        $this->app->bind(ArticleLinksInterface::class,ArticleLinksRepository::class);



        
    }

    public function boot()
    {
        /*if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            URL::forceScheme('https');
        }*/
        //setting language
        if(isset($_COOKIE['language'])) {
            \App::setLocale($_COOKIE['language']);
        } 
        else {
            \App::setLocale('en');
        }
        //setting theme
        if(isset($_COOKIE['theme'])) {
            View::share('theme', $_COOKIE['theme']);
        }
        else {
            View::share('theme', 'light');;
        }
        //get general setting value        
        $general_setting = DB::table('general_settings')->latest()->first();
        $currency = \App\Currency::find($general_setting->currency);
        View::share('general_setting', $general_setting);
        View::share('currency', $currency);
        config(['staff_access' => $general_setting->staff_access, 'date_format' => $general_setting->date_format, 'currency' => $currency->code, 'currency_position' => $general_setting->currency_position]);
        
        $alert_product = DB::table('products')->where('is_active', true)->whereColumn('alert_quantity', '>', 'qty')->count();
        View::share('alert_product', $alert_product);
        Schema::defaultStringLength(191);
    }
}
