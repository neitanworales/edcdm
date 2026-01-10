API - Endpoints propuestos

Nota: ajustar rutas y autenticación según el framework PHP elegido (Laravel recomendado).

Autenticación
- POST /api/auth/login { email, password }
- POST /api/auth/register { name, email, password } (solo admins)
- POST /api/auth/logout

Students (Alumnos)
- GET /api/students
- GET /api/students/{id}
- POST /api/students { nombre, apellidos, correo, telefono, fecha_nacimiento }
- PUT /api/students/{id}
- DELETE /api/students/{id}

Instructors
- GET /api/instructors
- GET /api/instructors/{id}
- POST /api/instructors
- PUT /api/instructors/{id}
- DELETE /api/instructors/{id}

Courses
- GET /api/courses
- GET /api/courses/{id}
- POST /api/courses
- PUT /api/courses/{id}
- DELETE /api/courses/{id}

Sessions
- GET /api/sessions
- GET /api/sessions/{id}
- POST /api/sessions { course_id, instructor_id, fecha_hora, ubicacion, duracion }
- PUT /api/sessions/{id}
- DELETE /api/sessions/{id}

Attendances
- GET /api/sessions/{session_id}/attendances
- POST /api/sessions/{session_id}/attendances { student_id, estado, observaciones }
- PUT /api/attendances/{id} { estado, observaciones }

Enrollments
- POST /api/courses/{course_id}/enrollments { student_id }
- GET /api/students/{student_id}/enrollments

Reportes
- GET /api/reports/attendances?from=YYYY-MM-DD&to=YYYY-MM-DD&course_id=&student_id=&format=csv|pdf

Consideraciones
- Proteger rutas con middleware de autenticación.
- Añadir control de permisos por roles (admin, instructor, lector).
- Validar entradas y normalizar formatos de fecha/hora (ISO 8601).
