Escuela de Capacitación Discipulado y Ministerial — Sistema de Administración de Asistencias

Descripción
-----------
Sistema para gestionar la asistencia de estudiantes, instructores y sesiones de formación de la "Escuela de Capacitación Discipulado y Ministerial". Provee herramientas para registrar asistencias, gestionar cursos y sesiones, generar reportes y exportar datos para control administrativo.

Stack del proyecto
------------------
- Frontend: Angular (TypeScript) — aplicación SPA gestionando interfaces de administrador, instructor y reportes.
- Backend: PHP (se recomienda Laravel para acelerar desarrollo y manejar autenticación/migraciones, aunque puede usarse Slim o código PHP personalizado).
- Base de datos: MySQL.

Objetivos
---------
- Registrar y consultar asistencias por sesión, curso y alumno.
- Permitir a administradores e instructores marcar y revisar asistencias.
- Generar reportes de asistencia y exportarlos (CSV/PDF).
- Mantener un historial por alumno y por curso.

Características principales
---------------------------
- Gestión de alumnos (crear/editar/eliminar/buscar).
- Gestión de cursos y sesiones (fechas, duración, instructor responsable).
- Registro de asistencia por sesión (presente, ausente, justificado).
- Reportes por alumno, curso y rango de fechas.
- Exportación de datos (CSV/PDF) y filtros avanzados.
- Roles y permisos: Administrador, Instructor, Usuario (lectura limitada).

Modelo de datos (propuesta)
--------------------------
- `Student` (Alumno): id, nombre, apellidos, correo, teléfono, fecha_nacimiento, notas.
- `Instructor`: id, nombre, apellidos, correo, teléfono.
- `Course` (Curso): id, titulo, descripcion, duracion, categoria.
- `Session` (Sesión): id, course_id, instructor_id, fecha_hora, ubicacion, duracion.
- `Attendance` (Asistencia): id, session_id, student_id, estado (presente/ausente/justificado), marcado_por, timestamp, observaciones.
- `Enrollment` (Inscripción): id, student_id, course_id, fecha_inscripcion, estado.

Instalación y arranque (guía rápida)
-----------------------------------
Requisitos mínimos: Node.js (16+), Angular CLI, PHP 8+, Composer, MySQL 5.7+/8.

1) Frontend (Angular)

	- Instalar Angular CLI si no está instalado:

		npm install -g @angular/cli

	- Crear o instalar dependencias del proyecto Angular:

		npm install

	- Ejecutar servidor de desarrollo:

		ng serve

2) Backend (PHP - ejemplo con Laravel)

	- Instalar dependencias:

		composer install

	- Configurar archivo `.env` con la conexión a MySQL y claves.

	- Ejecutar migraciones y levantar servidor:

		php artisan migrate
		php artisan serve

3) Base de datos (MySQL)

	- Crear la base de datos y configurar el usuario en MySQL.
	- Ajustar `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` en `.env`.

Ejemplos de uso rápido
----------------------
Marcar asistencia (curl):

	curl -X POST -H "Content-Type: application/json" -d '{"student_id":123,"estado":"presente"}' https://mi-backend/api/sessions/456/attendances

Obtener reporte CSV:

	curl -o reporte.csv "https://mi-backend/api/reports/attendances?from=2026-01-01&to=2026-01-31&format=csv"

Especificación de API y esquema de BD
-------------------------------------
Se incluye un `API.md` con endpoints propuestos y `schema.sql` con el esquema inicial de la base de datos.

Consideraciones de privacidad
----------------------------
- Guardar y procesar datos personales cumpliendo la normativa aplicable (consentimiento, acceso restringido, retención mínima necesaria).
- Restringir exportaciones y accesos sólo a roles autorizados.

Contribuir
----------
- Abrir issues para errores o mejoras.
- Crear pull requests con cambios bien documentados y pruebas cuando apliquen.

Próximos pasos recomendados
--------------------------
- Definir si usaremos Laravel (recomendado) u otra alternativa en PHP.
- Crear migraciones y el esquema inicial en `schema.sql`.
- Implementar endpoints básicos de CRUD para `Student`, `Course`, `Session` y `Attendance`.
- Añadir autenticación y control de accesos por roles.

Contacto
-------
Para preguntas o coordinación del proyecto contactar a los responsables de la Escuela.

