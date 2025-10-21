<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DocumentController;

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();

try {
    switch (true) {
        case $uri === '/' || $uri === '/login':
            if ($method === 'GET') {
                $authController->showLogin();
            } elseif ($method === 'POST') {
                $authController->login();
            }
            break;

        case $uri === '/logout':
            $authController->logout();
            break;

        case $uri === '/documents':
            $documentController = new DocumentController();
            $documentController->index();
            break;

        case $uri === '/documents/create':
            $documentController = new DocumentController();
            $documentController->create();
            break;

        case $uri === '/documents/edit':
            $documentController = new DocumentController();
            $documentController->edit();
            break;

        case $uri === '/documents/delete':
            $documentController = new DocumentController();
            $documentController->delete();
            break;

        default:
            http_response_code(404);
            echo "Página no encontrada";
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error del servidor: " . htmlspecialchars($e->getMessage());
}