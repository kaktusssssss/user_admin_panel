<?php

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
            header('Location: /login');
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
        header('Location: /login');
        exit();
    }
}