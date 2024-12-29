<?php
class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy() {
        session_destroy();
    }

    public static function checkRole($requiredRole) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
            header('Location: /access-denied');
            exit();
        }
    }
}
?>
