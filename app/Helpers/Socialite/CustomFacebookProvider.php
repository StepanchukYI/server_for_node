<?php

namespace App\Helpers\Socialite;

use GuzzleHttp\ClientInterface;
use RummyKhan\Socialite\Two\FacebookProvider;
use RummyKhan\Socialite\Two\ProviderInterface;

class CustomFacebookProvider extends FacebookProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    protected function parseAccessToken($body)
    {
        return json_decode($body, true)['access_token'];
    }
}