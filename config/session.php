<?php
    // Force session to start if not already started
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure cookies
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        session_start();
    }

    // Always ensure a CSRF token exists
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>