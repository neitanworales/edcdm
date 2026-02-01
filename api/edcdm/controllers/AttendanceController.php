<?php
require_once './config/Db.class.php';
require_once __DIR__ . '/../helpers.php';

class AttendanceController {

    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function listBySession($sessionId) {
        $stmt = $this->db->getPdo()->prepare('SELECT a.id, a.session_id, a.student_id, a.status, a.observation, a.marked_at FROM attendances a WHERE a.session_id = ? ORDER BY a.student_id');
        $stmt->execute([$sessionId]);
        jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create($sessionId) {
        $d = getJsonInput();
        if (empty($d['student_id']) || empty($d['status'])) jsonResponse(['error'=>'student_id and status required'],400);
        $stmt = $this->db->getPdo()->prepare('INSERT INTO attendances (session_id, student_id, enrollment_id, status, marked_by_user_id, observation) VALUES (?,?,?,?,?,?)');
        $stmt->execute([
            $sessionId,
            $d['student_id'],
            $d['enrollment_id'] ?? null,
            $d['status'],
            $d['marked_by_user_id'] ?? null,
            $d['observation'] ?? null
        ]);
        jsonResponse(['id'=>$this->db->getPdo()->lastInsertId()],201);
    }

    public function update($id) {
        $d = getJsonInput();
        $stmt = $this->db->getPdo()->prepare('UPDATE attendances SET status=?, observation=?, marked_by_user_id=? WHERE id=?');
        $stmt->execute([
            $d['status'] ?? null,
            $d['observation'] ?? null,
            $d['marked_by_user_id'] ?? null,
            $id
        ]);
        jsonResponse(['updated'=>true]);
    }
}
