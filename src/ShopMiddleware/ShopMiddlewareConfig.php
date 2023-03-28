<?php

namespace App\ShopMiddleware;

interface ShopMiddlewareConfig
{
    public const SHOP_URL = 'http://192.168.178.172:80'; //Local-IP
    public const ACCESS_GRANT_TYPE_CLIENT = 'client_credentials';
    public const ACCESS_GRANT_TYPE_PASSWORD = 'password';
    public const ACCESS_CLIENT_ID = 'SWIAQWDPSJVPAGXNELM5T1JZBG';
    public const ACCESS_CLIENT_SECRET = 'aTdsSVVNZDNiTFVDYXdtcDJZNmFHTnFzQzhxbk9TbW01UkE0QWo';
    public const ACCESS_USERNAME = 'admin';
    public const ACCESS_PASSWORD = 'shopware';

    public const CLIENT_ACCESS_GRANT = [
        'client_id' => self::ACCESS_CLIENT_ID,
        'client_secret' => self::ACCESS_CLIENT_SECRET,
        'grant_type' => self::ACCESS_GRANT_TYPE_CLIENT,
    ];

    public const PASSWORD_ACCESS_GRANT = [
        'username' => self::ACCESS_USERNAME,
        'password' => self::ACCESS_PASSWORD,
        'grant_type' => self::ACCESS_GRANT_TYPE_PASSWORD,
    ];
}