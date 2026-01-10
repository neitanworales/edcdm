
-- Diseño relacional recomendado para la Escuela de Capacitación Discipulado y Ministerial
-- Motor: InnoDB, charset utf8mb4

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE churches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  address VARCHAR(300),
  contact_person VARCHAR(150),
  contact_email VARCHAR(150),
  contact_phone VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(150) NOT NULL,
  email VARCHAR(150),
  phone VARCHAR(50),
  church_id INT, -- referencia a la iglesia
  date_of_birth DATE,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE instructors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(150) NOT NULL,
  email VARCHAR(150),
  phone VARCHAR(50),
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE modules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) UNIQUE,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  recommended_classes INT DEFAULT 8,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Lecciones/Clases tipo dentro de un módulo (p.ej clase 1..8)
CREATE TABLE module_lessons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  module_id INT NOT NULL,
  lesson_number INT NOT NULL,
  title VARCHAR(200),
  description TEXT,
  duration_minutes INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
  UNIQUE KEY ux_module_lesson (module_id, lesson_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cohortes/Grupos: una ejecución de un módulo (permite múltiples ediciones/runs)
CREATE TABLE cohorts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  module_id INT NOT NULL,
  name VARCHAR(200), -- p.ej. "Cohorte Enero 2026"
  start_date DATE,
  end_date DATE,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Modalidad por sesión: presencial u online
CREATE TABLE modalities (
  id TINYINT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(30) UNIQUE,
  label VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO modalities (id, code, label) VALUES (1, 'presencial', 'Presencial'), (2, 'online', 'En línea');

-- Sesión: una fecha/horario de una lección dentro de una cohorte
CREATE TABLE sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cohort_id INT NOT NULL,
  lesson_id INT, -- referencia a module_lessons (opcional)
  instructor_id INT,
  session_datetime DATETIME NOT NULL,
  modality_id TINYINT NOT NULL DEFAULT 1,
  location VARCHAR(255), -- sala física o URL
  duration_minutes INT,
  capacity INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (cohort_id) REFERENCES cohorts(id) ON DELETE CASCADE,
  FOREIGN KEY (lesson_id) REFERENCES module_lessons(id) ON DELETE SET NULL,
  FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE SET NULL,
  FOREIGN KEY (modality_id) REFERENCES modalities(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inscripciones de estudiantes a una cohorte (un alumno puede pertenecer a varias cohorts)
CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  cohort_id INT NOT NULL,
  enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('activo','completado','cancelado') DEFAULT 'activo',
  notes TEXT,
  UNIQUE KEY ux_enrollment_student_cohort (student_id, cohort_id),
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (cohort_id) REFERENCES cohorts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuarios del sistema (para marcar asistencias / roles)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(200),
  role ENUM('admin','instructor','viewer') DEFAULT 'viewer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Asistencias: registro por sesión y estudiante
CREATE TABLE attendances (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  student_id INT NOT NULL,
  enrollment_id INT, -- opcional, referencia a la inscripción en la cohorte
  status ENUM('presente','ausente','justificado','tardanza') NOT NULL DEFAULT 'ausente',
  marked_by_user_id INT, -- quien marcó la asistencia
  marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  observation TEXT, -- observaciones específicas de la asistencia
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE SET NULL,
  FOREIGN KEY (marked_by_user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX ix_attendance_session_student (session_id, student_id),
  INDEX ix_attendance_student (student_id),
  INDEX ix_attendance_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;

-- Vistas y utilidades (ejemplos):
-- Vista: resumen de asistencia por estudiante y cohorte
CREATE OR REPLACE VIEW vw_student_cohort_attendance AS
SELECT e.id AS enrollment_id,
       e.student_id,
       e.cohort_id,
       s.session_datetime,
       a.status,
       a.observation
FROM enrollments e
JOIN sessions s ON s.cohort_id = e.cohort_id
LEFT JOIN attendances a ON a.session_id = s.id AND a.student_id = e.student_id;

