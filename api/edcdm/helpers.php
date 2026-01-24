<?php
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    // Emit basic server timing header
    if (isset($GLOBALS['REQUEST_START'])) {
        $durMs = (microtime(true) - $GLOBALS['REQUEST_START']) * 1000;
        header('Server-Timing: total;dur=' . number_format($durMs, 2));
        header('X-Server-Duration: ' . number_format($durMs, 2) . 'ms');
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonInput() {
    $raw = file_get_contents('php://input');
    return $raw ? json_decode($raw, true) : [];
}
