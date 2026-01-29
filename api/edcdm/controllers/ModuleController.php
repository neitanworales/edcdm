<?php
require_once './config/Db.class.php';
require_once __DIR__ . '/../helpers.php';

class ModuleController {

    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function list() {
        $stmt = $this->db->getPdo()->query('SELECT id, code, title, description, recommended_classes FROM modules ORDER BY id');
        $modules = $stmt->fetchAll();

        foreach($modules as &$module) {
            $lessonStmt = $this->db->getPdo()->prepare('SELECT id, lesson_number, title, description, duration_minutes FROM module_lessons WHERE module_id = ? ORDER BY lesson_number');
            $lessonStmt->execute([$module['id']]);
            $module['lessons'] = $lessonStmt->fetchAll();
        }

        jsonResponse(['modules'=>$modules, 'message'=>'Modules retrieved successfully']);
    }

    public function get($id) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, code, title, description, recommended_classes FROM modules WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) jsonResponse(['error'=>'Module not found'],404);
        jsonResponse(['module'=>$row, 'message'=>'Module retrieved successfully']);
    }

    public  function create() {
        $data = getJsonInput();
        if (empty($data['title'])) jsonResponse(['error'=>'title required'],400);
        $stmt = $this->db->getPdo()->prepare('INSERT INTO modules (code, title, description, recommended_classes) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['code'] ?? null,
            $data['title'],
            $data['description'] ?? null,
            $data['recommended_classes'] ?? 8
        ]);
        jsonResponse(['id'=>$this->db->getPdo()->lastInsertId()],201);
    }

    public function update($id) {
        $data = getJsonInput();
        $stmt = $this->db->getPdo()->prepare('UPDATE modules SET code=?, title=?, description=?, recommended_classes=? WHERE id=?');
        $stmt->execute([
            $data['code'] ?? null,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['recommended_classes'] ?? null,
            $id
        ]);
        jsonResponse(['updated'=>true]);
    }

    public function lessons($moduleId) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, lesson_number, title, description, duration_minutes FROM module_lessons WHERE module_id = ? ORDER BY lesson_number');
        $stmt->execute([$moduleId]);
        jsonResponse($stmt->fetchAll());
    }
}
