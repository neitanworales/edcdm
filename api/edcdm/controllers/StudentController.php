<?php
require_once './config/Db.class.php';
require_once __DIR__ . '/../helpers.php';

class StudentController {

    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function list() {
        $stmt = $this->db->getPdo()->query('SELECT id, first_name, last_name, email, phone, church_id FROM students ORDER BY id');
        jsonResponse($stmt->fetchAll());
    }

    public function get($id) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, first_name, last_name, email, phone, church_id, date_of_birth, notes FROM students WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) jsonResponse(['error'=>'Student not found'],404);
        jsonResponse($row);
    }

    public function create() {
        $d = getJsonInput();
        if (empty($d['first_name']) || empty($d['last_name'])) jsonResponse(['error'=>'first_name and last_name required'],400);
        $stmt = $this->db->getPdo()->prepare('INSERT INTO students (first_name, last_name, email, phone, church_id, date_of_birth, notes) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([
            $d['first_name'], $d['last_name'], $d['email'] ?? null, $d['phone'] ?? null, $d['church_id'] ?? null, $d['date_of_birth'] ?? null, $d['notes'] ?? null
        ]);
        jsonResponse([
                'message' => 'Student created successfully',
                'id'=>$this->db->getPdo()->lastInsertId()],201);
    }

    public function update($id) {
        $d = getJsonInput();
        $stmt = $this->db->getPdo()->prepare('UPDATE students SET first_name=?, last_name=?, email=?, phone=?, church_id=?, date_of_birth=?, notes=? WHERE id=?');
        $stmt->execute([
            $d['first_name'] ?? null, $d['last_name'] ?? null, $d['email'] ?? null, $d['phone'] ?? null, $d['church_id'] ?? null, $d['date_of_birth'] ?? null, $d['notes'] ?? null, $id
        ]);
        jsonResponse(['updated'=>true]);
    }

    public function delete($id) {
        $stmt = $this->db->getPdo()->prepare('DELETE FROM students WHERE id=?');
        $stmt->execute([$id]);
        jsonResponse(['deleted'=>true]);
    }
}
