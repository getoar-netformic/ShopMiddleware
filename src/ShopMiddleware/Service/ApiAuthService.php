<?php

namespace App\ShopMiddleware\Service;

use App\ShopMiddleware\ShopMiddlewareConfig;
use Vin\ShopwareSdk\Client\AdminAuthenticator;
use Vin\ShopwareSdk\Client\GrantType\GrantType;
use Vin\ShopwareSdk\Client\GrantType\RefreshTokenGrantType;
use Vin\ShopwareSdk\Data\AccessToken;

class ApiAuthService
{
    public static ?AccessToken $ACCESS_TOKEN = null;

    public function authenticate(): AccessToken
    {
        if (!self::$ACCESS_TOKEN) {
            return $this->withClientCredentials();
        }

        if (self::$ACCESS_TOKEN && self::$ACCESS_TOKEN->isExpired()) {
            return $this->refreshToken();
        }

        return self::$ACCESS_TOKEN;
    }

    public function withClientCredentials(): AccessToken
    {
        $config = ShopMiddlewareConfig::CLIENT_ACCESS_GRANT;

        $grantType = GrantType::createFromConfig($config);
        $adminClient = new AdminAuthenticator($grantType, ShopMiddlewareConfig::SHOP_URL);
        self::$ACCESS_TOKEN = $adminClient->fetchAccessToken();


        return self::$ACCESS_TOKEN;
    }

    public function withUserCredentials(): AccessToken
    {
        $config = ShopMiddlewareConfig::PASSWORD_ACCESS_GRANT;

        $grantType = GrantType::createFromConfig($config);
        $adminClient = new AdminAuthenticator($grantType, ShopMiddlewareConfig::SHOP_URL);
        self::$ACCESS_TOKEN = $adminClient->fetchAccessToken();

        return self::$ACCESS_TOKEN;
    }

    public function refreshToken(): AccessToken
    {
        $grantType = new RefreshTokenGrantType(self::$ACCESS_TOKEN);
        $adminClient = new AdminAuthenticator($grantType, ShopMiddlewareConfig::SHOP_URL);
        self::$ACCESS_TOKEN = $adminClient->fetchAccessToken();

        return self::$ACCESS_TOKEN;
    }
}