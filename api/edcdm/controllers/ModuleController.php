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
        $sqlChurches = "SELECT DISTINCT church_id, church FROM view_sessions ";
        $paramsChurches = $this->getParamsSessions($moduleId, $chruchId, $modalityId);
        $whereChurches = $this->getWhereSessions($moduleId, $chruchId, $modalityId);
        $sqlChurches .= $whereChurches . " ORDER BY church_id";
        
        $stmt = $this->db->getPdo()->prepare($sqlChurches);
        $stmt->execute($paramsChurches);
        $chrches = $stmt->fetchAll(PDO::FETCH_ASSOC);    

        foreach($chrches as &$church) {
            $chruchId_ = (int)$church['church_id'];
            $sql = "SELECT DISTINCT modality_id, label FROM view_sessions ";
            $params = $this->getParamsSessions($moduleId, $chruchId_, $modalityId);
            $where = $this->getWhereSessions($moduleId, $chruchId_, $modalityId);
            $sql .= $where . " ORDER BY session_id";

            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute($params);
            $modalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($modalities as &$modality) {
                $modalityId_ = (int)$modality['modality_id'];
                $modalitySql = "SELECT DISTINCT module_id,code, module_title, cohort_id FROM view_sessions ";
                $modalityParams = $this->getParamsSessions($moduleId, $chruchId_, $modalityId_);
                $modalityWhere = $this->getWhereSessions($moduleId, $chruchId_, $modalityId_);
                $modalitySql .= $modalityWhere . " ORDER BY module_id";

                $modalityStmt = $this->db->getPdo()->prepare($modalitySql);
                $modalityStmt->execute($modalityParams);
                $modality['modules'] = $modalityStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach($modality['modules'] as &$module) {
                    $sessionSql = "SELECT session_id, session_datetime, lesson_id, lesson_number, lesson_title FROM view_sessions ";
                    $sessionWhere = $this->getWhereSessions($module['module_id'], $chruchId_, $modalityId_);
                    $sessionSql .= $sessionWhere . " ORDER BY lesson_number";
                    $sessionParams = $this->getParamsSessions($module['module_id'], $chruchId_, $modalityId_);
                    $sessionStmt = $this->db->getPdo()->prepare($sessionSql);
                    $sessionStmt->execute($sessionParams);
                    $sessiones = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);
                    $module['sessions'] = $sessiones;
                }

                foreach($modality['modules'] as &$module) {
                    $studentsSQL = "SELECT distinct student_id, first_name, last_name, phone FROM view_enrollments_attendances 
                    WHERE module_id = ?
                    AND cohort_id = ?
                    AND modality_id = ?
                    AND session_id IS NOT NULL 
                    AND attendance_id IS NOT NULL
                    ORDER BY first_name, last_name";
                    
                    $studentsStmt = $this->db->getPdo()->prepare($studentsSQL);
                    $studentsStmt->execute([$module['module_id'], $module['cohort_id'], $modalityId_]);
                    $students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);
                    $module['students'] = $students; 

                    foreach($module['students'] as &$student) {
                        $attendancesSQL = "SELECT * FROM view_enrollments_attendances 
                        WHERE module_id = ?
                        AND cohort_id = ?
                        AND modality_id = ?
                        AND student_id = ?
                        AND session_id IS NOT NULL 
                        AND attendance_id IS NOT NULL 
                        ORDER BY first_name,last_name, lesson_id";
                        $attendancesStmt = $this->db->getPdo()->prepare($attendancesSQL);
                        $attendancesStmt->execute([$module['module_id'], $module['cohort_id'], $modalityId_, $student['student_id']]);
                        $student['attendances'] = $attendancesStmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                       
                }
            }
            $church['modalities'] = $modalities;
        }
        
        jsonResponse(['response' => ['module_id' => (int)$moduleId,
            'church_id' => !empty($chruchId) ? (int)$chruchId : 0,
            'modality_id' => !empty($modalityId) ? (int)$modalityId : 0,
            'churches' => $chrches, 
            'message' => 'Sessions retrieved successfully']]);
    }

    public function getWhereSessions($moduleId, $chruchId, $modalityId){
        if($moduleId == -1 && $chruchId == -1 && $modalityId == -1){
            return "";
        } else {
            if($chruchId != -1 && $modalityId == -1 && $moduleId == -1){
                return "WHERE church_id = ?";
            } else if ($chruchId != -1 && $modalityId != -1 && $moduleId == -1){
                return "WHERE church_id = ? AND modality_id = ?";
            } else if($chruchId != -1 && $modalityId == -1 && $moduleId != -1){
                return "WHERE church_id = ? AND module_id = ?";
            } else if($chruchId != -1 && $modalityId != -1 && $moduleId != -1){
                return "WHERE church_id = ? AND module_id = ? AND modality_id = ?";
            } else {
                if($modalityId != -1 && $chruchId == -1 && $moduleId == -1){
                    return "WHERE modality_id = ?";
                } else if($modalityId != -1 && $chruchId != -1 && $moduleId == -1){
                    return "WHERE church_id = ? AND modality_id = ?";
                } else if($modalityId != -1 && $chruchId == -1 && $moduleId != -1){
                    return "WHERE module_id = ? AND modality_id = ?";
                } else {
                    if($moduleId != -1 && $chruchId == -1 && $modalityId == -1){
                        return "WHERE module_id = ?";
                    } else if ($moduleId != -1 && $chruchId != -1 && $modalityId == -1){
                        return "WHERE module_id = ? AND church_id = ?";
                    } else if ($moduleId != -1 && $modalityId != -1 && $chruchId == -1 ){
                        return "WHERE module_id = ? AND modality_id = ?";
                    } else if ($moduleId != -1 && $modalityId != -1 && $chruchId != -1){
                        return "WHERE module_id = ? AND church_id = ? AND modality_id = ?";
                    }
                }
            }
        }
    }

    public function getParamsSessions($moduleId, $chruchId, $modalityId){
        if($moduleId == -1 && $chruchId == -1 && $modalityId == -1){
            return [];
        } else {
            if($chruchId != -1 && $modalityId == -1 && $moduleId == -1){
                return [$chruchId];
            } else if ($chruchId != -1 && $modalityId != -1 && $moduleId == -1){
                return [$chruchId, $modalityId];
            } else if($chruchId != -1 && $modalityId == -1 && $moduleId != -1){
                return [$chruchId, $moduleId];
            } else if($chruchId != -1 && $modalityId != -1 && $moduleId != -1){
                return [$chruchId, $moduleId, $modalityId];
            } else {
                if($modalityId != -1 && $chruchId == -1 && $moduleId == -1){
                    return [$modalityId];
                } else if($modalityId != -1 && $chruchId != -1 && $moduleId == -1){
                    return [$chruchId, $modalityId];
                } else if($modalityId != -1 && $chruchId == -1 && $moduleId != -1){
                    return [$moduleId, $modalityId];
                } else {
                    if($moduleId != -1 && $chruchId == -1 && $modalityId == -1){
                        return [$moduleId];
                    } else if ($moduleId != -1 && $chruchId != -1 && $modalityId == -1){
                        return [$moduleId, $chruchId];
                    } else if ($moduleId != -1 && $modalityId != -1 && $chruchId == -1 ){
                        return [$moduleId, $modalityId] ;
                    } else if ($moduleId != -1 && $modalityId != -1 && $chruchId != -1){
                        return [$moduleId, $chruchId, $modalityId];
                    }
                }
            }
        }
    }
}
