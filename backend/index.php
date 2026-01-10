<?php
// Simple router entrypoint: backend/index.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/controllers/ModuleController.php';
require_once __DIR__ . '/controllers/StudentController.php';
require_once __DIR__ . '/controllers/AttendanceController.php';

// CORS (dev)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $path), '/');
$segments = explode('/', $base);

// Expect routes under /api/...
if (empty($segments[0]) || $segments[0] !== 'api') {
    jsonResponse(['error'=>'Invalid endpoint'], 404);
}

// Shift 'api'
array_shift($segments);

// Routing
try {
    // /api/modules
    if ($segments[0] === 'modules') {
        if (count($segments) === 1) {
            if ($method === 'GET') ModuleController::list();
            if ($method === 'POST') ModuleController::create();
        }
        if (count($segments) >= 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if (count($segments) === 2) {
                if ($method === 'GET') ModuleController::get($id);
                if ($method === 'PUT') ModuleController::update($id);
            }
            // /api/modules/{id}/lessons
            if (isset($segments[2]) && $segments[2] === 'lessons' && $method === 'GET') {
                ModuleController::lessons($id);
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

    // If no route matched
    jsonResponse(['error'=>'Route not found'], 404);
} catch (Exception $e) {
    jsonResponse(['error'=>'Server error','message'=>$e->getMessage()],500);
}
