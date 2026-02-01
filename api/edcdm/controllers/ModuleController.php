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
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($modules as &$module) {
            $lessonStmt = $this->db->getPdo()->prepare('SELECT id, lesson_number, title, description, duration_minutes FROM module_lessons WHERE module_id = ? ORDER BY lesson_number');
            $lessonStmt->execute([$module['id']]);
            $module['lessons'] = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);
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
        jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getSessions($moduleId, $chruchId, $modalityId) {
        $chrches = $this->getChurchesFromViewSessions($chruchId);
        foreach($chrches as &$church) {
            $chruchId_ = (int)$church['church_id'];
            $sql = "SELECT DISTINCT modality_id, label FROM view_sessions WHERE module_id = ? AND church_id = ?";
            $params = [ $moduleId, $chruchId_ ];
            if (!empty($modalityId)) {
                $sql .= " AND modality_id = ?";
                $params[] = $modalityId;
            }
            $sql .= " ORDER BY session_id";

            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute($params);
            $modalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($modalities as &$modality) {
                $modalityId_ = (int)$modality['modality_id'];
                $moduleSql = "SELECT DISTINCT module_id,code, module_title FROM view_sessions 
                WHERE module_id = ? 
                AND church_id = ? 
                AND modality_id = ? 
                ORDER BY session_id";
                $moduleParams = [ $moduleId, $chruchId_, $modalityId_ ];
                $moduleStmt = $this->db->getPdo()->prepare($moduleSql);
                $moduleStmt->execute($moduleParams);
                $modality['modules'] = $moduleStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach($modality['modules'] as &$module) {
                    $sessionSql = "SELECT session_id, session_datetime, lesson_id, lesson_number, lesson_title FROM view_sessions WHERE module_id = ? AND church_id = ? AND modality_id = ? ORDER BY session_id";
                    $sessionParams = [ $module['module_id'], $chruchId_, $modalityId_ ];
                    $sessionStmt = $this->db->getPdo()->prepare($sessionSql);
                    $sessionStmt->execute($sessionParams);
                    $module['sessions'] = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            $church['modalities'] = $modalities;
        }
        
        jsonResponse(['sessions' => ['module_id' => (int)$moduleId,
            'church_id' => !empty($chruchId) ? (int)$chruchId : 0,
            'modality_id' => !empty($modalityId) ? (int)$modalityId : 0,
            'churches' => $chrches, 
            'message' => 'Sessions retrieved successfully']]);
    }

    public function getChurchesFromViewSessions($chruchId){
        $sql = "SELECT DISTINCT church_id, church FROM view_sessions WHERE church_id IS NOT NULL";
        $params = [];
        if (!empty($chruchId)) {
            $sql .= " AND church_id = ?";
            $params[] = $chruchId;
        }
        $sql .= " ORDER BY church_id";

        if ($params) {
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $this->db->getPdo()->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
