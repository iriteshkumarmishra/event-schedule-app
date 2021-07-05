<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ViewServiceProvider extends ServiceProvider
{

    protected  $siteSettings = [];

    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer(['layouts.app', 'auth.login', 'auth.register'], function ($view) {
            $this->siteSettings = $this->loadSiteSettings();
            $view->with(['siteSettings' => $this->siteSettings]);
        });
    }

    protected function loadSiteSettings() {
        // You can change the logo and and other details here, or store those values in a table and read those. For the simplicity sake I have created an array with key and values.
        $response = [
            'logo'              =>  '/images/logo.svg',
            'mini_logo'         =>  '/images/mini-logo.svg',
            'top_bar_email'     =>  'events@app.com',
            'top_bar_phone'     =>  '+19876545671',
            'site_title'        =>  'Events Scheduling',
            'footer_text'       =>  'All Rights Reserved, 2021',
        ];

        return $response;
    }
}
