<?php
/**
 * Authentication helper functions
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/UserManager.php';

/**
 * Require authentication - redirect to login if not authenticated
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Logout user
 */
function logout() {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit();
}
?>