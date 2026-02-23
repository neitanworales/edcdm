<?php
// Mark request start for timing
$GLOBALS['REQUEST_START'] = microtime(true);
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
require_once __DIR__ . '/controllers/ChurchController.php';

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
            // /api/modules/{id}/sessions
            if (isset($segments[2]) && $segments[2] === 'sessions' && $method === 'GET') {
                $church_id = isset($_GET['church_id']) ? (int)$_GET['church_id'] : -1;
                $modeality = isset($_GET['modeality']) ? (int)$_GET['modeality'] : -1;
                $moduleController->getSessions($id, $church_id, $modeality);
            }
            // /api/modules/{id}/students
            if (isset($segments[2]) && $segments[2] === 'students' && $method === 'GET') {
                $church_id = isset($_GET['church_id']) ? (int)$_GET['church_id'] : null;
                $modeality = isset($_GET['modeality']) ? (int)$_GET['modeality'] : null;
                $studentsController = new StudentController();
                $studentsController->listByModuleChurchModality($id, $church_id, $modeality);
            }
        }
    }

    // /api/churches
    if ($segments[0] === 'churches') {
        $churchController = new ChurchController();

        if (count($segments) === 1) {
            if ($method === 'GET') $churchController->list();
            if ($method === 'POST') $churchController->create();
        }
        if (count($segments) === 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if ($method === 'GET') $churchController->get($id);
            if ($method === 'PUT') $churchController->update($id);
            if ($method === 'DELETE') $churchController->delete($id);
        }
    }

    // /api/students
    if ($segments[0] === 'students') {
        $studentController = new StudentController();
        if (count($segments) === 1) {
            if ($method === 'GET') $studentController->list();
            if ($method === 'POST') $studentController->create();
        }
        if (count($segments) === 2 && is_numeric($segments[1])) {
            $id = (int)$segments[1];
            if ($method === 'GET') $studentController->get($id);
            if ($method === 'PUT') $studentController->update($id);
            if ($method === 'DELETE') $studentController->delete($id);
        }
        if (count($segments) === 3 && $segments[1] === 'church' && is_numeric($segments[2])) {
            $churchId = (int)$segments[2];
            if ($method === 'GET') $studentController->listByChurch($churchId);
        }
        // /api/students/unassigned/{churchId}
        if (count($segments) === 3 && $segments[1] === 'unassigned' && is_numeric($segments[2])) {
            $churchId = (int)$segments[2];
            if ($method === 'GET') $studentController->getUnassignedStudent($churchId);
        }
    }

    // /api/sessions/{id}/attendances
    if ($segments[0] === 'sessions' && isset($segments[1]) && is_numeric($segments[1])) {
        $sessionId = (int)$segments[1];
        $attendanceController = new AttendanceController();
        if (isset($segments[2]) && $segments[2] === 'attendances') {
            if ($method === 'GET') $attendanceController->listBySession($sessionId);
            if ($method === 'POST') $attendanceController->create($sessionId);
        }
    }

    // /api/attendances/{id}
    if ($segments[0] === 'attendances' && isset($segments[1]) && is_numeric($segments[1])) {
        $attId = (int)$segments[1];
        $attendanceController = new AttendanceController();
        if ($method === 'PUT' && (!isset($segments[2]) || $segments[2] !== 'status')) {
            $attendanceController->update($attId);
        } 
        //api/attendances/{id}/status/{newStatus}
        if ($method === 'PUT' && isset($segments[2]) && $segments[2] === 'status') {
            $newStatus = $segments[3];
            $attendanceController->changeStatus($attId, $newStatus);
        }
        
    }

    //api/attendances/generate
    if( $segments[0] === 'attendances' && $segments[1] === 'generate'){
        if($method === 'POST'){
            $d = getJsonInput();
            if (empty($d['student_id']) || empty($d['cohort_id'])) jsonResponse(['error'=>'student_id and cohort_id required'],400);
            $attendanceController = new AttendanceController();
            $attendanceController->generateAttendances($d['student_id'], $d['cohort_id']);
        }
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
        // /api/users/check-email
        if (count($segments) === 2 && $segments[1] === 'check-email' && $method === 'POST') {
            UserController::checkEmail();
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
