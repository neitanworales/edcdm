<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

class ModuleController {
    public static function list() {
        $pdo = DB::get();
        $stmt = $pdo->query('SELECT id, code, title, description, recommended_classes FROM modules ORDER BY id');
        jsonResponse($stmt->fetchAll());
    }

    public static function get($id) {
        $pdo = DB::get();
        $stmt = $pdo->prepare('SELECT id, code, title, description, recommended_classes FROM modules WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) jsonResponse(['error'=>'Module not found'],404);
        jsonResponse($row);
    }

    public static function create() {
        $data = getJsonInput();
        if (empty($data['title'])) jsonResponse(['error'=>'title required'],400);
        $pdo = DB::get();
        $stmt = $pdo->prepare('INSERT INTO modules (code, title, description, recommended_classes) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['code'] ?? null,
            $data['title'],
            $data['description'] ?? null,
            $data['recommended_classes'] ?? 8
        ]);
        jsonResponse(['id'=>$pdo->lastInsertId()],201);
    }

    public static function update($id) {
        $data = getJsonInput();
        $pdo = DB::get();
        $stmt = $pdo->prepare('UPDATE modules SET code=?, title=?, description=?, recommended_classes=? WHERE id=?');
        $stmt->execute([
            $data['code'] ?? null,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['recommended_classes'] ?? null,
            $id
        ]);
        jsonResponse(['updated'=>true]);
    }

    public static function lessons($moduleId) {
        $pdo = DB::get();
        $stmt = $pdo->prepare('SELECT id, lesson_number, title, description, duration_minutes FROM module_lessons WHERE module_id = ? ORDER BY lesson_number');
        $stmt->execute([$moduleId]);
        jsonResponse($stmt->fetchAll());
    }
}
