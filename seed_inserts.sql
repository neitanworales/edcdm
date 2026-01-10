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
