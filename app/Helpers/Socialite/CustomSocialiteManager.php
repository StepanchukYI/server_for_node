<?php

namespace App\Helpers\Socialite;

use RummyKhan\Socialite\Contracts\Factory;
use RummyKhan\Socialite\SocialiteManager;
use Illuminate\Support\Manager;
use App\Helpers\Socialite\CustomFacebookProvider;
use Symfony\Component\Console\Helper\Helper;

class CustomSocialiteManager extends SocialiteManager implements Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \RummyKhan\Socialite\Two\AbstractProvider
     */
    protected function createFacebookDriver()
    {
        $config = $this->app['config']['services.facebook'];
        return $this->buildProvider(
            CustomFacebookProvider::class, $config
        );
    }

    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \RummyKhan\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {

        return new $provider(
            $this->app['request'], $config['client_id'],
            $config['client_secret'], $config['redirect']
        );
    }
}