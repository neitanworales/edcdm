-- seed_inserts.sql
-- Inserts para módulos y lecciones (module_lessons)
-- Ejecutar en MySQL (usar la base de datos del proyecto).

START TRANSACTION;

-- Modalidades (id único por code)
INSERT INTO modalities (code, label)
VALUES
  ('presencial', 'Presencial'),
  ('online', 'En línea')
ON DUPLICATE KEY UPDATE label = VALUES(label);

-- Módulos
INSERT INTO modules (code, title, description, recommended_classes)
VALUES
  ('IDENT', 'Identidad', 'Formación de la identidad en Cristo; cómo lo que eres transforma lo que haces.', 8),
  ('PROP', 'Propósito', 'Descubrir y vivir el propósito personal y ministerial en el plan de Dios.', 8),
  ('CAR',  'Carácter', 'Formación práctica del carácter cristiano: hábitos y actitudes.', 8),
  ('VIS',  'Visión', 'Aprender a ver como Dios y sostener una visión que impulse la misión.', 8),
  ('REC',  'Recursos', 'Mayordomía y uso de recursos espirituales, personales y materiales.', 8),
  ('DEST', 'Destino', 'Entender el destino cristiano y las etapas que llevan a su cumplimiento.', 8)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), recommended_classes = VALUES(recommended_classes);

-- Obtener ids (variables) para insertar lecciones
SET @m_ident = (SELECT id FROM modules WHERE code='IDENT');
SET @m_prop  = (SELECT id FROM modules WHERE code='PROP');
SET @m_car   = (SELECT id FROM modules WHERE code='CAR');
SET @m_vis   = (SELECT id FROM modules WHERE code='VIS');
SET @m_rec   = (SELECT id FROM modules WHERE code='REC');
SET @m_dest  = (SELECT id FROM modules WHERE code='DEST');

-- Lecciones para IDENTIDAD
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_ident, 1, 'Un nuevo nacimiento', 'La naturaleza del nuevo nacimiento como fundamento espiritual.', 90),
  (@m_ident, 2, 'Un nuevo nombre', 'La identidad renovada y su impacto en la vida práctica.', 90),
  (@m_ident, 3, 'Identidad por revelación', 'Conocer a Dios para que la identidad sea revelada y recibida.', 90),
  (@m_ident, 4, 'Pertenencia', 'La pertenencia a la familia de Dios y su efecto formativo.', 90),
  (@m_ident, 5, 'Identificación', 'Procesos para reconocer y afirmar la identidad en Cristo.', 90),
  (@m_ident, 6, 'Mi naturaleza', 'Comprender la semejanza con Dios como base del carácter cristiano.', 90),
  (@m_ident, 7, 'Intercambio', 'Principio del intercambio (dar/recibir) en la formación espiritual.', 90),
  (@m_ident, 8, 'Conceptos correctos', 'Corregir ideas equivocadas sobre identidad y propósito.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

-- Lecciones para PROPÓSITO
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_prop, 1, 'Mi propósito', 'Para lo que fui creado; visión personal y llamado.', 90),
  (@m_prop, 2, 'Propósito y misión', 'Relación entre propósito individual y misión comunitaria.', 90),
  (@m_prop, 3, 'Propósito y mensaje', 'Comunicar el propósito en palabra y testimonio.', 90),
  (@m_prop, 4, 'Propósito y función', 'Operar según el llamado y las funciones dadas.', 90),
  (@m_prop, 5, 'Fructificar y multiplicar', 'Principios para producir fruto sostenible.', 90),
  (@m_prop, 6, 'Imagen y reflejo', 'Ser reflejo de Dios en la comunidad y la misión.', 90),
  (@m_prop, 7, 'Imagen y semejanza', 'Vivir la semejanza a Dios en el día a día.', 90),
  (@m_prop, 8, 'Vocación práctica', 'Aplicación del propósito en la vida y el servicio.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

-- Lecciones para CARÁCTER
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_car, 1, 'Respuestas correctas', 'Formación de respuestas sanas ante la vida.', 90),
  (@m_car, 2, 'Voluntad', 'Desarrollo del poder de decisión y compromiso.', 90),
  (@m_car, 3, 'Dominio propio', 'Gobernar las pasiones y hábitos.', 90),
  (@m_car, 4, 'Prudencia', 'Sabiduría práctica para la vida y el ministerio.', 90),
  (@m_car, 5, 'Mansedumbre', 'Humildad y trato con otros.', 90),
  (@m_car, 6, 'Emociones', 'Salud emocional y regulación afectiva.', 90),
  (@m_car, 7, 'Obediencia', 'Vivir conforme a la autoridad y la Palabra.', 90),
  (@m_car, 8, 'Servicio', 'Actitud práctica de entrega y ayuda al prójimo.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

-- Lecciones para VISIÓN
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_vis, 1, 'El poder de la visión', 'Ver las cosas como Dios las ve; dirección espiritual.', 90),
  (@m_vis, 2, 'Visión y posición', 'Ubicar la propia posición en la visión de Dios.', 90),
  (@m_vis, 3, 'El proceso de la visión', 'Cómo se desarrolla y madura la visión.', 90),
  (@m_vis, 4, 'El plan eterno de Dios', 'Integrar la visión personal en el plan de Dios.', 90),
  (@m_vis, 5, 'Visión generacional', 'Pensar en impacto a largo plazo y legado.', 90),
  (@m_vis, 6, 'Visión y pasión', 'Mantener el impulso y la pasión por la visión.', 90),
  (@m_vis, 7, 'La visión y mi potencial', 'Equipamiento para avanzar en la visión.', 90),
  (@m_vis, 8, 'Revelación progresiva', 'La visión como revelación que crece con el tiempo.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

-- Lecciones para RECURSOS
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_rec, 1, 'Recursos', 'Identificar lo que Dios ha dado para la misión.', 90),
  (@m_rec, 2, 'Mayordomía', 'Administración fiel de talentos y bienes.', 90),
  (@m_rec, 3, 'Semilla', 'Principio de siembra y cosecha espiritual.', 90),
  (@m_rec, 4, 'Trabajo', 'Vocación como plataforma de servicio y sustento.', 90),
  (@m_rec, 5, 'Unción', 'Acción del Espíritu que habilita para el servicio.', 90),
  (@m_rec, 6, 'Legado y herencia', 'Construir en perspectiva de legado espiritual.', 90),
  (@m_rec, 7, 'Recursos y tiempo', 'Uso sabio del tiempo y prioridades.', 90),
  (@m_rec, 8, 'Dones y recursos', 'Descubrir y activar dones para la obra.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

-- Lecciones para DESTINO
INSERT INTO module_lessons (module_id, lesson_number, title, description, duration_minutes)
VALUES
  (@m_dest, 1, 'Destino', 'Visión del destino personal y su cumplimiento.', 90),
  (@m_dest, 2, 'Destino y propósito', 'Relación entre destino y razón de ser.', 90),
  (@m_dest, 3, 'Destino y valor', 'Reconocer el valor propio en el plan de Dios.', 90),
  (@m_dest, 4, 'Destino y tiempo', 'Tiempos y estaciones para alcanzar el destino.', 90),
  (@m_dest, 5, 'Destino y etapas', 'Etapas prácticas del proceso hacia el destino.', 90),
  (@m_dest, 6, 'Origen y destino', 'Conectar origen (fundamento) con destino (meta).', 90),
  (@m_dest, 7, 'El camino', 'Etapas y rutas prácticas para avanzar hacia el destino.', 90),
  (@m_dest, 8, 'Sentido de destino', 'Vivir con propósito y orientación final.', 90)
ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), duration_minutes=VALUES(duration_minutes);

COMMIT;

-- =============================
-- Estudiantes aleatorios (20)
-- Asegúrate de tener iglesias con ids 1..4 creadas.
-- =============================
START TRANSACTION;

INSERT INTO students (first_name, last_name, email, phone, church_id, date_of_birth, notes) VALUES
  ('Juan', 'Perez', 'juan.perez@example.com', '555-0101', 1, '1992-05-14', 'Estudiante activo'),
  ('Maria', 'Lopez', 'maria.lopez@example.com', '555-0102', 2, '1995-11-03', 'Interesada en Propósito'),
  ('Carlos', 'Gomez', 'carlos.gomez@example.com', '555-0103', 3, '1988-02-21', 'Participa en grupos'),
  ('Ana', 'Martinez', 'ana.martinez@example.com', '555-0104', 4, '1999-07-30', 'Nueva inscripción'),
  ('Luis', 'Hernandez', 'luis.hernandez@example.com', '555-0105', 1, '1985-12-12', 'Requiere seguimiento'),
  ('Laura', 'Diaz', 'laura.diaz@example.com', '555-0106', 2, '1993-03-08', 'Prefiere modalidad online'),
  ('Miguel', 'Santos', 'miguel.santos@example.com', '555-0107', 3, '2000-10-17', 'Asistencia regular'),
  ('Sofia', 'Ramirez', 'sofia.ramirez@example.com', '555-0108', 4, '1991-06-25', 'Interesada en Carácter'),
  ('Diego', 'Vargas', 'diego.vargas@example.com', '555-0109', 1, '1987-01-05', 'Buen desempeño'),
  ('Camila', 'Castro', 'camila.castro@example.com', '555-0110', 2, '1998-09-19', 'Recién incorporada'),
  ('Andres', 'Rojas', 'andres.rojas@example.com', '555-0111', 3, '1990-04-28', 'Solicita material adicional'),
  ('Valeria', 'Navarro', 'valeria.navarro@example.com', '555-0112', 4, '1996-08-02', 'Participa en visión'),
  ('Jorge', 'Flores', 'jorge.flores@example.com', '555-0113', 1, '1989-03-15', 'Disponible fines de semana'),
  ('Paula', 'Torres', 'paula.torres@example.com', '555-0114', 2, '2001-12-01', 'Excelente compromiso'),
  ('Ricardo', 'Mendoza', 'ricardo.mendoza@example.com', '555-0115', 3, '1986-07-11', 'Asiste presencial'),
  ('Carolina', 'Silva', 'carolina.silva@example.com', '555-0116', 4, '1994-05-07', 'Solicita mentoría'),
  ('Felipe', 'Ortega', 'felipe.ortega@example.com', '555-0117', 1, '1997-02-10', 'Interesado en recursos'),
  ('Natalia', 'Paredes', 'natalia.paredes@example.com', '555-0118', 2, '1993-10-22', 'Seguimiento por email'),
  ('Hector', 'Campos', 'hector.campos@example.com', '555-0119', 3, '1984-09-09', 'Horario nocturno'),
  ('Daniela', 'Fuentes', 'daniela.fuentes@example.com', '555-0120', 4, '1999-01-27', 'Preferencia por mañana');

COMMIT;

-- =============================
-- Estudiantes aleatorios (50 más)
-- =============================
START TRANSACTION;

INSERT INTO students (first_name, last_name, email, phone, church_id, date_of_birth, notes) VALUES
  ('Alejandro', 'Suarez', 'alejandro.suarez@example.com', '555-0201', 1, '1990-01-12', 'Alumno constante'),
  ('Beatriz', 'Velasco', 'beatriz.velasco@example.com', '555-0202', 2, '1993-03-07', 'Prefiere online'),
  ('Cristian', 'Nunez', 'cristian.nunez@example.com', '555-0203', 3, '1988-11-23', 'Interesado en identidad'),
  ('Diana', 'Aguilar', 'diana.aguilar@example.com', '555-0204', 4, '1997-06-15', 'Buena participación'),
  ('Eduardo', 'Ponce', 'eduardo.ponce@example.com', '555-0205', 1, '1985-09-29', 'Seguimiento por mentor'),
  ('Fernanda', 'Araya', 'fernanda.araya@example.com', '555-0206', 2, '1999-12-03', 'Nueva en el grupo'),
  ('Guillermo', 'Cortez', 'guillermo.cortez@example.com', '555-0207', 3, '1987-08-18', 'Participa en debates'),
  ('Helena', 'Salas', 'helena.salas@example.com', '555-0208', 4, '1992-02-26', 'Interesada en visión'),
  ('Ignacio', 'Bravo', 'ignacio.bravo@example.com', '555-0209', 1, '1994-05-04', 'Asistencia destacada'),
  ('Julieta', 'Mora', 'julieta.mora@example.com', '555-0210', 2, '1996-07-21', 'Consulta material extra'),
  ('Kevin', 'Rios', 'kevin.rios@example.com', '555-0211', 3, '1991-10-10', 'Compromiso alto'),
  ('Liliana', 'Serrano', 'liliana.serrano@example.com', '555-0212', 4, '1989-04-02', 'Prefiere presencial'),
  ('Manuel', 'Ibanez', 'manuel.ibanez@example.com', '555-0213', 1, '1986-03-13', 'Horario nocturno'),
  ('Nerea', 'Carrasco', 'nerea.carrasco@example.com', '555-0214', 2, '1998-01-28', 'Interesada en propósito'),
  ('Oscar', 'Tapia', 'oscar.tapia@example.com', '555-0215', 3, '1984-12-06', 'Participa activamente'),
  ('Pamela', 'Zamora', 'pamela.zamora@example.com', '555-0216', 4, '1995-09-11', 'Solicitud de mentoría'),
  ('Quique', 'Delgado', 'quique.delgado@example.com', '555-0217', 1, '1993-04-19', 'Buen dominio de temas'),
  ('Rocio', 'Arce', 'rocio.arce@example.com', '555-0218', 2, '1990-06-08', 'Interesada en carácter'),
  ('Sergio', 'Palacios', 'sergio.palacios@example.com', '555-0219', 3, '1997-11-14', 'Participación estable'),
  ('Tamara', 'Reyes', 'tamara.reyes@example.com', '555-0220', 4, '1992-07-03', 'Requiere seguimiento'),
  ('Ulises', 'Arias', 'ulises.arias@example.com', '555-0221', 1, '1988-02-09', 'Consulta recursos'),
  ('Veronica', 'Figueroa', 'veronica.figueroa@example.com', '555-0222', 2, '1999-05-27', 'Excelente disposición'),
  ('Walter', 'Camacho', 'walter.camacho@example.com', '555-0223', 3, '1987-01-31', 'Asiste con regularidad'),
  ('Ximena', 'Bustos', 'ximena.bustos@example.com', '555-0224', 4, '1996-08-20', 'Interesada en legado'),
  ('Yamil', 'Peña', 'yamil.pena@example.com', '555-0225', 1, '1990-10-05', 'Participa en grupos'),
  ('Zoila', 'Quispe', 'zoila.quispe@example.com', '555-0226', 2, '1994-09-16', 'Se integra este mes'),
  ('Abel', 'Sosa', 'abel.sosa@example.com', '555-0227', 3, '1993-12-24', 'Necesita material básico'),
  ('Bianca', 'Vega', 'bianca.vega@example.com', '555-0228', 4, '1989-07-12', 'Buen avance'),
  ('Cesar', 'Leiva', 'cesar.leiva@example.com', '555-0229', 1, '1998-03-02', 'Horario flexible'),
  ('Daniela', 'Paredes', 'daniela.paredes2@example.com', '555-0230', 2, '1995-04-26', 'Interesada en recursos'),
  ('Esteban', 'Villalba', 'esteban.villalba@example.com', '555-0231', 3, '1986-06-22', 'Asiste puntual'),
  ('Florencia', 'Acosta', 'florencia.acosta@example.com', '555-0232', 4, '1997-02-14', 'Alta motivación'),
  ('Gabriel', 'Huerta', 'gabriel.huerta@example.com', '555-0233', 1, '1992-11-09', 'Consulta dudas técnicas'),
  ('Hortensia', 'Mendez', 'hortensia.mendez@example.com', '555-0234', 2, '1988-05-06', 'Participa ocasionalmente'),
  ('Ismael', 'Navia', 'ismael.navia@example.com', '555-0235', 3, '1991-09-27', 'Compromiso medio'),
  ('Jimena', 'Alvarado', 'jimena.alvarado@example.com', '555-0236', 4, '1999-01-18', 'Solicita guía'),
  ('Karen', 'Lagos', 'karen.lagos@example.com', '555-0237', 1, '1996-12-30', 'Buena actitud'),
  ('Lucia', 'Bermudez', 'lucia.bermudez@example.com', '555-0238', 2, '1987-07-08', 'Interesada en mayordomía'),
  ('Matias', 'Sevilla', 'matias.sevilla@example.com', '555-0239', 3, '1994-01-25', 'Asistencia estable'),
  ('Noelia', 'Prado', 'noelia.prado@example.com', '555-0240', 4, '1993-10-12', 'Solicita material'),
  ('Omar', 'Garrido', 'omar.garrido@example.com', '555-0241', 1, '1985-04-01', 'Participa activamente'),
  ('Paola', 'Bustamante', 'paola.bustamante@example.com', '555-0242', 2, '1998-06-19', 'Interesada en visión'),
  ('Renzo', 'Quiroga', 'renzo.quiroga@example.com', '555-0243', 3, '1990-02-07', 'Alta disponibilidad'),
  ('Silvia', 'Lemus', 'silvia.lemus@example.com', '555-0244', 4, '1997-09-04', 'Constante asistencia'),
  ('Tomas', 'Renteria', 'tomas.renteria@example.com', '555-0245', 1, '1992-03-29', 'Puntual'),
  ('Ursula', 'Esquivel', 'ursula.esquivel@example.com', '555-0246', 2, '1989-08-28', 'Requiere apoyo'),
  ('Valentina', 'Correa', 'valentina.correa@example.com', '555-0247', 3, '1996-07-01', 'Buen desempeño'),
  ('Wilson', 'Ibarra', 'wilson.ibarra@example.com', '555-0248', 4, '1988-10-15', 'Participa en talleres'),
  ('Xiomara', 'Alonso', 'xiomara.alonso@example.com', '555-0249', 1, '1995-05-20', 'Preferencia por mañana'),
  ('Yolanda', 'Galvez', 'yolanda.galvez@example.com', '555-0250', 2, '1991-12-27', 'Asistencia regular');

COMMIT;
