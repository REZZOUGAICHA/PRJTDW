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

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function unset($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        session_destroy();
        session_unset();
        header('Location: ' . BASE_URL . '/accueil');
        exit;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdminLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    public static function getUserData() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'user_id' => self::get('user_id'),
            'first_name' => self::get('first_name'),
            'last_name' => self::get('last_name'),
            'email' => self::get('email'),
            'user_type' => self::get('user_type')
        ];
    }
}