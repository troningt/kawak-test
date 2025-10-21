USE doc_management;

-- Insertar datos en PRO_PROCESO
INSERT INTO PRO_PROCESO (PRO_NOMBRE, PRO_PREFIJO) VALUES
('Ingeniería', 'ING'),
('Recursos Humanos', 'RH'),
('Ventas', 'VEN'),
('Producción', 'PRO'),
('Calidad', 'CAL');

-- Insertar datos en TIP_TIPO_DOC
INSERT INTO TIP_TIPO_DOC (TIP_NOMBRE, TIP_PREFIJO) VALUES
('Instructivo', 'INS'),
('Manual', 'MAN'),
('Procedimiento', 'PROC'),
('Política', 'POL'),
('Formato', 'FOR');