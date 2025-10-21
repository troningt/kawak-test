<?php

namespace App\Controllers;

class AuthController
{
    private const VALID_USERNAME = 'admin';
    private const VALID_PASSWORD = 'admin123';

    public function showLogin(): void
    {
        if ($this->isAuthenticated()) {
            header('Location: /documents');
            exit;
        }

        require __DIR__ . '/../Views/login.php';
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === self::VALID_USERNAME && $password === self::VALID_PASSWORD) {
            session_start();
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            header('Location: /documents');
            exit;
        }

        $_SESSION['error'] = 'Usuario o contraseña incorrectos';
        header('Location: /login');
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

    public static function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }

    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
}