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
        jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listByChurch($churchId) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, first_name, last_name, email, phone, church_id FROM students WHERE church_id = ? ORDER BY id');
        $stmt->execute([$churchId]);
        jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listByModuleChurchModality($moduleId, $churchId, $modalityId) {
        $que = 'SELECT DISTINCT student_id, first_name, last_name FROM view_attendance 
        WHERE module_id= ? 
        AND modality_id= ?
        AND church_id = ? ';
        $stmt = $this->db->getPdo()->prepare($que);
        $stmt->execute([$moduleId, $modalityId, $churchId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($students as &$student) {
            $attendanceStmt = $this->db->getPdo()->prepare('SELECT session_id, status FROM view_attendance 
            WHERE student_id = ? 
            AND module_id= ? 
            AND church_id= ? 
            AND modality_id= ? ');
            $attendanceStmt->execute([$student['student_id'], $moduleId, $churchId, $modalityId]);
            $student['attendances'] = $attendanceStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        jsonResponse([ 'students' => $students, 
            'module_id' => (int)$moduleId,
            'church_id' => (int)$churchId,
            'modality_id' => (int)$modalityId,
            'message' => 'Students retrieved successfully']);
    }

    public function get($id) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, first_name, last_name, email, phone, church_id, date_of_birth, notes FROM students WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getUnassignedStudent($churchId) {
        $query ='SELECT DISTINCT enrollment_id, student_id \'id\', first_name, last_name, phone, church_id 
        FROM view_enrollments_attendances 
        WHERE enrollment_id IS NULL';

        if(!empty($churchId)) {
            $query .= ' AND church_id=?';
            $stmt = $this->db->getPdo()->prepare($query);
            $stmt->execute([$churchId]);    
        } else {
            $stmt = $this->db->getPdo()->prepare($query);
            $stmt->execute([]);
        }
        $studnets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['church_id' => $churchId, 'students' => $studnets, 'message'=>'Unassigned students retrieved successfully'], 200);
    }
}
