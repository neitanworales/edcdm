<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

class StudentController {
    public static function list() {
        $pdo = DB::get();
        $stmt = $pdo->query('SELECT id, first_name, last_name, email, phone, church_id FROM students ORDER BY id');
        jsonResponse($stmt->fetchAll());
    }

    public static function get($id) {
        $pdo = DB::get();
        $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, phone, church_id, date_of_birth, notes FROM students WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) jsonResponse(['error'=>'Student not found'],404);
        jsonResponse($row);
    }

    public static function create() {
        $d = getJsonInput();
        if (empty($d['first_name']) || empty($d['last_name'])) jsonResponse(['error'=>'first_name and last_name required'],400);
        $pdo = DB::get();
        $stmt = $pdo->prepare('INSERT INTO students (first_name, last_name, email, phone, church_id, date_of_birth, notes) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([
            $d['first_name'], $d['last_name'], $d['email'] ?? null, $d['phone'] ?? null, $d['church_id'] ?? null, $d['date_of_birth'] ?? null, $d['notes'] ?? null
        ]);
        jsonResponse(['id'=>$pdo->lastInsertId()],201);
    }

    public static function update($id) {
        $d = getJsonInput();
        $pdo = DB::get();
        $stmt = $pdo->prepare('UPDATE students SET first_name=?, last_name=?, email=?, phone=?, church_id=?, date_of_birth=?, notes=? WHERE id=?');
        $stmt->execute([
            $d['first_name'] ?? null, $d['last_name'] ?? null, $d['email'] ?? null, $d['phone'] ?? null, $d['church_id'] ?? null, $d['date_of_birth'] ?? null, $d['notes'] ?? null, $id
        ]);
        jsonResponse(['updated'=>true]);
    }

    public static function delete($id) {
        $pdo = DB::get();
        $stmt = $pdo->prepare('DELETE FROM students WHERE id=?');
        $stmt->execute([$id]);
        jsonResponse(['deleted'=>true]);
    }
}
