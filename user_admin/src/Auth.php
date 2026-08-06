<?php
declare(strict_types=1);

namespace App;

class Auth
{
    /**
     * Check if user is logged in
     */

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            $config = require __DIR__ . '/../config/app.php';
            header('Location: ' . $config['base_url'] . '/login');
            exit();
        }
    }

    /**
     * Logout user
     */
    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $config = require __DIR__ . '/../config/app.php';
        header('Location: ' . $config['base_url'] . '/login');
        exit();
    }
}