<?php

namespace App\Helpers\Socialite;

use RummyKhan\Socialite\SocialiteServiceProvider;

class CustomSocialiteServiceProvider extends SocialiteServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('RummyKhan\Socialite\Contracts\Factory', function ($app) {
            return new CustomSocialiteManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['RummyKhan\Socialite\Contracts\Factory'];
    }
}