<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

class AttendanceController {
    public static function listBySession($sessionId) {
        $pdo = DB::get();
        $stmt = $pdo->prepare('SELECT a.id, a.session_id, a.student_id, a.status, a.observation, a.marked_at FROM attendances a WHERE a.session_id = ? ORDER BY a.student_id');
        $stmt->execute([$sessionId]);
        jsonResponse($stmt->fetchAll());
    }

    public static function create($sessionId) {
        $d = getJsonInput();
        if (empty($d['student_id']) || empty($d['status'])) jsonResponse(['error'=>'student_id and status required'],400);
        $pdo = DB::get();
        $stmt = $pdo->prepare('INSERT INTO attendances (session_id, student_id, enrollment_id, status, marked_by_user_id, observation) VALUES (?,?,?,?,?,?)');
        $stmt->execute([
            $sessionId,
            $d['student_id'],
            $d['enrollment_id'] ?? null,
            $d['status'],
            $d['marked_by_user_id'] ?? null,
            $d['observation'] ?? null
        ]);
        jsonResponse(['id'=>$pdo->lastInsertId()],201);
    }

    public static function update($id) {
        $d = getJsonInput();
        $pdo = DB::get();
        $stmt = $pdo->prepare('UPDATE attendances SET status=?, observation=?, marked_by_user_id=? WHERE id=?');
        $stmt->execute([
            $d['status'] ?? null,
            $d['observation'] ?? null,
            $d['marked_by_user_id'] ?? null,
            $id
        ]);
        jsonResponse(['updated'=>true]);
    }
}
