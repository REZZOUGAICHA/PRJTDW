
<?php


class SessionHelper {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
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
        session_unset();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

?>
