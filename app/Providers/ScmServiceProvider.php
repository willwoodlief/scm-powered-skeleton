<?php

namespace App\Providers;

use App\Plugins\Plugin;
use App\Plugins\PluginRef;
use Illuminate\Support\ServiceProvider;
use TorMorten\Eventy\Facades\Eventy;

class ScmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }


    public static function getEmployeeDefaultThumbnail() : ?string {
        return Eventy::filter(Plugin::FILTER_EMPLOYEE_ICON,'');
    }

    public static function getUserDefaultThumbnail() : ?string {
        return Eventy::filter(Plugin::FILTER_USER_ICON,'');
    }

    public static function getContactorDefaultImage() : ?string {
        return Eventy::filter(Plugin::FILTER_CONTRACTOR_IMAGE,'');
    }

    public static function getLogo() : ?string {
        return Eventy::filter(Plugin::FILTER_LOGO_IMAGE,'');
    }

    public static function getLoginBackground() : ?string {
        return Eventy::filter(Plugin::FILTER_LOGIN_BACKGROUND_IMAGE,'');
    }
    public static function getFavicon() : ?string {
        return Eventy::filter(Plugin::FILTER_FAVICON,'');
    }
     public static function getSiteName() : ?string {
        return Eventy::filter(Plugin::FILTER_SITE_NAME,__('Salvage Contractors'));
    }



}
