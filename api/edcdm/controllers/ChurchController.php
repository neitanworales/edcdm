<?php
require_once './config/Db.class.php';
require_once __DIR__ . '/../helpers.php';

class ChurchController {
    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function list() {
        $stmt = $this->db->getPdo()->query('SELECT id, name, address, contact_person, contact_email, contact_phone, created_at FROM churches ORDER BY id');
        jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get($id) {
        $stmt = $this->db->getPdo()->prepare('SELECT id, name, address, contact_person, contact_email, contact_phone, created_at, updated_at FROM churches WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) jsonResponse(['error'=>'Church not found'],404);
        jsonResponse($row);
    }

    public function create() {
        $d = getJsonInput();
        if (empty($d['name'])) jsonResponse(['error'=>'name required'],400);
        $stmt = $this->db->getPdo()->prepare('INSERT INTO churches (name, address, contact_person, contact_email, contact_phone) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $d['name'], $d['address'] ?? null, $d['contact_person'] ?? null, $d['contact_email'] ?? null, $d['contact_phone'] ?? null
        ]);
        $id = $this->db->getPdo()->lastInsertId();
        jsonResponse(['id'=>$id],201);
    }

    public function update($id) {
        $d = getJsonInput();
        $stmt = $this->db->getPdo()->prepare('UPDATE churches SET name=?, address=?, contact_person=?, contact_email=?, contact_phone=? WHERE id=?');
        $stmt->execute([
            $d['name'] ?? null, $d['address'] ?? null, $d['contact_person'] ?? null, $d['contact_email'] ?? null, $d['contact_phone'] ?? null, $id
        ]);
        jsonResponse(['updated'=>true]);
    }

    public function delete($id) {
        $stmt = $this->db->getPdo()->prepare('DELETE FROM churches WHERE id=?');
        $stmt->execute([$id]);
        jsonResponse(['deleted'=>true]);
    }
}
