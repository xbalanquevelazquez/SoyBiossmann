DROP TABLE IF EXISTS cms_acciones;
CREATE TABLE cms_acciones (
  kid_accion INT(11) NOT NULL auto_increment,
  acronimo_accion VARCHAR(15) NOT NULL UNIQUE,
  nombre_accion VARCHAR(50) NOT NULL UNIQUE,
  desc_accion VARCHAR(150),
  PRIMARY KEY  (kid_accion)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_acciones VALUES (1,'ADMIN','Administrar','Administración total del sistema');
INSERT INTO cms_acciones VALUES (2,'VIEW','Consulta de secciones generales','');
INSERT INTO cms_acciones VALUES (3,'USERS','Usuarios','Permisos para dar de alta, baja y editar usuarios');
INSERT INTO cms_acciones VALUES (4,'PERF','Perfiles','Permisos para modificar los permisos de los perfiles');
INSERT INTO cms_acciones VALUES (5,'CMS','Páginas','');
INSERT INTO cms_acciones VALUES (6,'NOTICIAS','Noticias','');
INSERT INTO cms_acciones VALUES (7,'BANNERS','Banners','');
INSERT INTO cms_acciones VALUES (8,'CALIDAD','Calidad','');