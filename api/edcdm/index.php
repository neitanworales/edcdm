<?php
// Simple router entrypoint: backend/index.php

// Mostrar errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/controllers/ModuleController.php';
require_once __DIR__ . '/controllers/StudentController.php';
require_once __DIR__ . '/controllers/AttendanceController.php';
require_once __DIR__ . '/controllers/UserController.php';

// CORS (dev)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $path), '/');
$segments = explode('/', $base);

// Routes start directly (no /api prefix needed)
// if (empty($segments[0]) || $segments[0] !== 'api') {
//     jsonResponse(['error'=>'Invalid endpoint'], 404);
// }

// No need to shift segments
// array_shift($segments);

// Routing
try {
    // /api/modules
    if ($segments[0] === 'modules') {
        $moduleController = new ModuleController();

        if (count($segments) === 1) {
            if ($method === 'GET') $moduleController->list();
            if ($method === 'POST') $moduleController->create();
        }
        if (count($segments) >= 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if (count($segments) === 2) {
                if ($method === 'GET') $moduleController->get($id);
                if ($method === 'PUT') $moduleController->update($id);
            }
            // /api/modules/{id}/lessons
            if (isset($segments[2]) && $segments[2] === 'lessons' && $method === 'GET') {
                $moduleController->lessons($id);
            }
        }
    }

    // /api/students
    if ($segments[0] === 'students') {
        if (count($segments) === 1) {
            if ($method === 'GET') StudentController::list();
            if ($method === 'POST') StudentController::create();
        }
        if (count($segments) === 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if ($method === 'GET') StudentController::get($id);
            if ($method === 'PUT') StudentController::update($id);
            if ($method === 'DELETE') StudentController::delete($id);
        }
    }

    // /api/sessions/{id}/attendances
    if ($segments[0] === 'sessions' && isset($segments[1]) && is_numeric($segments[1])) {
        $sessionId = (int)$segments[1];
        if (isset($segments[2]) && $segments[2] === 'attendances') {
            if ($method === 'GET') AttendanceController::listBySession($sessionId);
            if ($method === 'POST') AttendanceController::create($sessionId);
        }
    }

    // /api/attendances/{id}
    if ($segments[0] === 'attendances' && isset($segments[1]) && is_numeric($segments[1])) {
        $attId = (int)$segments[1];
        if ($method === 'PUT') AttendanceController::update($attId);
    }

    // /api/users
    if ($segments[0] === 'users') {
        // /api/users/register
        if (count($segments) === 2 && $segments[1] === 'register' && $method === 'POST') {
            UserController::register();
        }
        // /api/users/login
        if (count($segments) === 2 && $segments[1] === 'login' && $method === 'POST') {
            UserController::login();
        }
        // /api/users
        if (count($segments) === 1) {
            if ($method === 'GET') UserController::list();
        }
        // /api/users/{id}
        if (count($segments) === 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if ($method === 'GET') UserController::get($id);
            if ($method === 'PUT') UserController::update($id);
            if ($method === 'DELETE') UserController::delete($id);
        }
    }

    // If no route matched
    jsonResponse(['error'=>'Route not found'], 404);
} catch (Exception $e) {
    jsonResponse(['error'=>'Server error','message'=>$e->getMessage()],500);
}
