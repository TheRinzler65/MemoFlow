<?php

namespace App\Helpers;

class Session
{
    // Durées de vie de session (en secondes).
    // TEST : 2 min / 10 min — PROD : remplacer par 7 * 86400 et 30 * 86400
    public const TTL = 120;
    public const TTL_REMEMBER = 600;

    public static function isExpired(): bool
    {
        if (!isset($_SESSION['last_activity'])) {
            return false;
        }

        $ttl = !empty($_SESSION['remember']) ? self::TTL_REMEMBER : self::TTL;

        return (time() - $_SESSION['last_activity']) > $ttl;
    }

    public static function remember(): bool
    {
        return !empty($_SESSION['remember']);
    }

    public static function touch(): void
    {
        $_SESSION['last_activity'] = time();
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}
