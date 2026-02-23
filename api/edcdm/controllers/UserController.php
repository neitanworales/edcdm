<?php

class UserController {
    
    // POST /users/register - Registrar nuevo usuario
    public static function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validaciones
        if (empty($input['username']) || empty($input['password'])) {
            jsonResponse(['error' => 'Username and password are required'], 400);
        }
        
        // Hash de la contraseña
        $passwordHash = password_hash($input['password'], PASSWORD_BCRYPT);
        
        try {
            $db = Db::getInstance();
            $id = $db->executeWithId("
                INSERT INTO users (username, password_hash, full_name, role) 
                VALUES (?, ?, ?, ?)
            ", [
                $input['username'],
                $passwordHash,
                $input['full_name'] ?? null,
                $input['role'] ?? 'viewer'
            ]);
            
            jsonResponse([
                'message' => 'User created successfully',
                'id' => (int)$id
            ], 201);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                jsonResponse(['error' => 'Username already exists'], 409);
            }
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
    
    // POST /users/login - Autenticar usuario
    public static function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['username']) || empty($input['password'])) {
            jsonResponse(['error' => 'Username and password are required'], 400);
        }
        
        try {
            $db = Db::getInstance();
            $stmt = $db->execute("
                SELECT id, username, password_hash, full_name, role 
                FROM users 
                WHERE username = ?
            ", [$input['username']]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($input['password'], $user['password_hash'])) {
                jsonResponse(['error' => 'Invalid credentials'], 401);
            }
            
            // Crear token simple (en producción usar JWT)
            $token = bin2hex(random_bytes(32));
            
            // Aquí podrías guardar el token en una tabla de sesiones
            // Por ahora, solo devolvemos los datos del usuario
            
            $session = ['user' => [
                    'id' => (int)$user['id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role']
                ],
                'token' => $token
            ];

            jsonResponse([
                'message' => 'Login successful',
                'session' => $session
            ]);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
    
    // GET /users - Listar todos los usuarios (solo admin)
    public static function list() {
        try {
            $db = Db::getInstance();
            $stmt = $db->execute("
                SELECT id, username, full_name, role, created_at 
                FROM users 
                ORDER BY created_at DESC
            ");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse([
                'message' => 'Users retrieved successfully',
                'users' => $users
            ]);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
    
    // GET /users/{id} - Obtener un usuario específico
    public static function get($id) {
        try {
            $db = Db::getInstance();
            $stmt = $db->execute("
                SELECT id, username, full_name, role, created_at, updated_at 
                FROM users 
                WHERE id = ?
            ", [$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                jsonResponse(['error' => 'User not found'], 404);
            }
            
            jsonResponse($user);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
    
    // PUT /users/{id} - Actualizar usuario
    public static function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $db = Db::getInstance();
            
            // Construir query dinámicamente según campos presentes
            $fields = [];
            $params = [];
            
            if (isset($input['username'])) {
                $fields[] = "username = ?";
                $params[] = $input['username'];
            }
            
            if (isset($input['password'])) {
                $fields[] = "password_hash = ?";
                $params[] = password_hash($input['password'], PASSWORD_BCRYPT);
            }
            
            if (isset($input['full_name'])) {
                $fields[] = "full_name = ?";
                $params[] = $input['full_name'];
            }
            
            if (isset($input['role'])) {
                $fields[] = "role = ?";
                $params[] = $input['role'];
            }
            
            if (empty($fields)) {
                jsonResponse(['error' => 'No fields to update'], 400);
            }
            
            $params[] = $id;
            $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $db->execute($sql, $params);
            
            if ($stmt->rowCount() === 0) {
                jsonResponse(['error' => 'User not found or no changes made'], 404);
            }
            
            jsonResponse(['message' => 'User updated successfully']);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                jsonResponse(['error' => 'Username already exists'], 409);
            }
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
    
    // DELETE /users/{id} - Eliminar usuario
    public static function delete($id) {
        try {
            $db = Db::getInstance();
            $stmt = $db->execute("DELETE FROM users WHERE id = ?", [$id]);
            
            if ($stmt->rowCount() === 0) {
                jsonResponse(['error' => 'User not found'], 404);
            }
            
            jsonResponse(['message' => 'User deleted successfully']);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }

    public static function checkEmail() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['email'])) {
            jsonResponse(['error' => 'Email is required'], 400);
        }

        try {
            $db = Db::getInstance();
            $stmt = $db->execute("SELECT id FROM users WHERE username = ?", [$input['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            jsonResponse(['exists' => $user ? true : false, 'message' => $user ? 'Email already exists' : 'Email is available'],200);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Database error', 'message' => $e->getMessage()], 500);
        }
    }
}
