<?php

namespace App\Helpers\Socialite;

use RummyKhan\Socialite\Facades\Socialite;

class CustomSocialite extends Socialite
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'RummyKhan\Socialite\Contracts\Factory';
    }
}