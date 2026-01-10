Backend PHP minimal
====================

Estructura:
- `config.php` - editar conexión a MySQL
- `db.php` - conexión PDO singleton
- `helpers.php` - utilidades JSON
- `controllers/` - controladores para módulos, estudiantes, asistencias
- `index.php` - entrypoint, router simple

Rutas principales (ejemplos):
- GET /api/modules
- GET /api/modules/{id}
- GET /api/modules/{id}/lessons
- POST /api/modules
- GET /api/students
- POST /api/students
- GET /api/students/{id}
- PUT /api/students/{id}
- GET /api/sessions/{id}/attendances
- POST /api/sessions/{id}/attendances
- PUT /api/attendances/{id}

Instrucciones rápidas:
1) Copiar `backend/config.php` y ajustar credenciales.
2) Colocar `backend` como document root o configurar virtual host apuntando a `backend`.
3) Asegurarse de que la base de datos y tablas (ver `schema.sql`) existan.
4) Probar con curl o Postman.

Notas:
- Este es un skeleton minimal para desarrollo local. Para producción usar framework (Laravel) y añadir autenticación, validación y tests.
