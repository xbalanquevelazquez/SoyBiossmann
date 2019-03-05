DROP TABLE IF EXISTS cms_estructura;
CREATE TABLE cms_estructura (
  kid_pagina int(11) NOT NULL auto_increment,
  nivel int(11) NOT NULL default '0',
  padre int(11) NOT NULL default '1',
  nombre varchar(250) NOT NULL,
  alias varchar(40) default NULL UNIQUE,
  publicado int(11) NOT NULL default '1',
  orden int(11) NOT NULL default '1',
  plantilla int(11) NOT NULL default '1',
  visible int(11) NOT NULL default '1',
  descripcion VARCHAR(255) NULL,
  keywords VARCHAR(255) NULL,
  selector VARCHAR(50) default '',
  PRIMARY KEY  (`kid_pagina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO cms_estructura VALUES (1, 0, 0, 'Soy Biossmann', 'index', 1, 1, 1, 1, 'Sitio web Soy Biossmann.', 'inicio, biossmann, home','');
INSERT INTO cms_estructura VALUES (2, 1, 1, 'Biossmann', 'biossmann', 1, 1, 2, 1, 'Qué es Biossmann.', 'servicios, informacion, mision, vision, sap, gobierno, objetivos, que, quien, biossmann, organigrama, historia, ubicaciones, logo, diseno','');
INSERT INTO cms_estructura VALUES (3, 1, 1, 'Comunidad', 'comunidad', 1, 2, 2, 1, 'Tú eres Biossmann.', 'biossmann, aplicaciones, sistemas, reportes, informacion, bolsa, trabajo, evaluacion, desempeno, capacitacion, tramites','');
INSERT INTO cms_estructura VALUES (4, 1, 1, 'Calidad', 'calidad',     1, 3, 2, 1, 'Documentación del Sistema de Gestión de Calidad Biossmann.', 'biossmann, calidad, politica, procedimiento, formato, pdf, proceso, sgc','');
INSERT INTO cms_estructura VALUES (5, 1, 1, 'Marketing', 'marketing',     1, 4, 2, 1, 'Información de mercadotecnia e imagen corporativa.', 'biossmann, logo, plantilla, presentacion, powerpoint, publicidad, tarjeta, folleto, comercial, producto, servicio','');
INSERT INTO cms_estructura VALUES (6, 1, 1, 'Portales y Tableros', 'portales-y-tableros',     1, 5, 2, 1, 'Aplicaciones Biossmann.', 'biossmann, app, aplicacion, ft, folio, tracker, hh, hand, held, handheld, sistema, registro','');
INSERT INTO cms_estructura VALUES (7, 1, 1, 'Preguntas Frecuentes', 'preguntas-frecuentes', 1, 6, 2, 1, 'Encuentra respuestas a las preguntas más comunes.', 'preguntas, respuestas, frecuentes, biossmann, dudas, informacion','');

DROP TABLE IF EXISTS cms_contenido;
CREATE TABLE cms_contenido (
  kid_contenido int(11) NOT NULL auto_increment,
  fid_estructura int(11) NOT NULL,
  contenido longtext,
  fecha_alta datetime NOT NULL,
  fecha_modificacion datetime default NULL,
  nombre_responsable varchar(70) NOT NULL default 'Administrador del Soy Biossmann',
  imagen_seccion VARCHAR(200),
  PRIMARY KEY  (`kid_contenido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*ALTER TABLE cms_contenido ADD imagen_seccion VARCHAR(200) AFTER nombre_responsable;*/

INSERT INTO cms_contenido VALUES (1, 1, 'Soy Biossmann', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (2, 2, 'Biossmann', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (3, 3, 'Comunidad', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (4, 4, 'Calidad', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (5, 5, 'Marketing', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (6, 6, 'Portales y Tableros', NOW(), NULL, 'Administrador de Soy Biossmann');
INSERT INTO cms_contenido VALUES (7, 7, 'Preguntas Frecuentes', NOW(), NULL, 'Administrador de Soy Biossmann');

DROP TABLE IF EXISTS cms_plantilla;
CREATE TABLE cms_plantilla (
  kid_plantilla int(11) NOT NULL auto_increment,
  nombre varchar(45) NOT NULL,
  descripcion varchar(200) default NULL,
  filepath varchar(150) default NULL,
  estatus int(11) NOT NULL default '1', /*--antes "habilitada"*/
  PRIMARY KEY  (`kid_plantilla`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_plantilla VALUES (1, 'home', 'Plantilla home', 'home', 1);
INSERT INTO cms_plantilla VALUES (2, 'subseccion', 'Página normal', 'subseccion', 1);
INSERT INTO cms_plantilla VALUES (3, 'mapadesitio', 'Mostrar el mapa del sitio', 'mapadesitio', 1);
INSERT INTO cms_plantilla VALUES (4, 'sinsubsecciones', 'P&aacute;gina, no mostrar columna de subsecciones', 'sinsubsecciones', 1);
INSERT INTO cms_plantilla VALUES (5, 'landingpage', 'Mostrar subsecciones como lista', 'landingpage', 1);

DROP TABLE IF EXISTS cms_usuarios;
CREATE TABLE cms_usuarios (
  kid_usr INT(11) NOT NULL auto_increment, /*-- antes "usr_id"*/
  usr_login varchar(50) NOT NULL UNIQUE,
  usr_psw BLOB NOT NULL,
  usr_nombre varchar(255) NOT NULL,
  usr_apaterno varchar(255) NOT NULL,
  usr_amaterno varchar(255) NOT NULL,
  usr_correo VARCHAR(255),
  usr_activo INT(11) NOT NULL default '1',
  fid_perfil INT(11) NOT NULL default '1',
  PRIMARY KEY  (`kid_usr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_usuarios VALUES (1,'admin',AES_ENCRYPT('123','$0y.Bios5m4n'),'Administrador de Soy Biossmann','','','xvelazquez@biossmann.com',1,1);

DROP TABLE IF EXISTS cms_perfil;
CREATE TABLE cms_perfil (
  kid_perfil INT(11) NOT NULL auto_increment,
  nombre_perfil VARCHAR(50) NOT NULL UNIQUE,
  seccion_inicial VARCHAR(50) NOT NULL,
  PRIMARY KEY  (kid_perfil)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_perfil VALUES (1,'Administrador principal','CMS');

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

DROP TABLE IF EXISTS cms_permisos;
CREATE TABLE cms_permisos (
  fid_perfil INT(11) NOT NULL,
  fid_accion INT(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_permisos VALUES (1,1);

DROP TABLE IF EXISTS cms_permisos_pagina;
CREATE TABLE cms_permisos_pagina (
  fid_pagina INT(11) NOT NULL,
  fid_accion INT(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO cms_permisos_pagina VALUES (1,1);

DROP TABLE IF EXISTS cms_seccion;
CREATE TABLE cms_seccion(
	kid_seccion INT AUTO_INCREMENT,
	nombre VARCHAR(255) NOT NULL,
	visible INT NOT NULL DEFAULT 1,
	acronimo VARCHAR(20) NOT NULL,
	image VARCHAR(50) NULL,
  permisos VARCHAR(300) NOT NULL DEFAULT 'ADMIN',
	PRIMARY KEY(kid_seccion)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO cms_seccion VALUES
(1,'Usuarios',1,'usr','imgUsr','ADMIN,USERS'),
(2,'Perfil',1,'perfil','imgPerfil','ADMIN,PERF'),
(3,'Estructura',1,'est','imgTree','ADMIN,CMS'),
(4,'Noticias',1,'noticias','imgNoticia','ADMIN,NOTICIAS'),
(5,'Listas',1,'pluginlinks','imgLink','ADMIN,CMS'),
(6,'Banners',1,'pluginbanners','imgBanner','ADMIN,BANNERS'),
(7,'Calidad',1,'calidad','imgCalidad','ADMIN,CALIDAD');

DROP TABLE IF EXISTS cms_banners;
CREATE TABLE cms_banners(
  kid_banner INT(11) NOT NULL AUTO_INCREMENT,
  img VARCHAR(255) NOT NULL DEFAULT '',
  grupo INT(11) NOT NULL DEFAULT '1',
  link TEXT,
  titulo VARCHAR(255),
  alt VARCHAR(255) DEFAULT NULL,
  posicion INT(11) NOT NULL DEFAULT '1',
  visible INT(11) NOT NULL DEFAULT '1',
  color INT(11) NOT NULL DEFAULT '1',
  PRIMARY KEY(kid_banner)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS cms_grupo_banners;
CREATE TABLE cms_grupo_banners (
  kid_grupo int(11) NOT NULL auto_increment,
  identificador varchar(255) NOT NULL default '',
  titulo varchar(255) default NULL,
  selector varchar(50) default NULL,
  visible int(11) NOT NULL default '1',
  PRIMARY KEY  (`kid_grupo`),
  UNIQUE KEY identificador (`identificador`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO cms_grupo_banners VALUES 
(1,'banners-inicio','Banners de la p&aacute;gina de inicio','banner',1);

DROP TABLE IF EXISTS cms_grupo_links;
CREATE TABLE cms_grupo_links (
  kid_grupo int(11) NOT NULL auto_increment,
  identificador varchar(255) NOT NULL default '',
  titulo varchar(255) default NULL,
  selector varchar(50) default NULL,
  visible int(11) NOT NULL default '1',
  PRIMARY KEY  (`kid_grupo`),
  UNIQUE KEY identificador (`identificador`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO cms_grupo_links VALUES 
(1,'temas-de-interes','Temas de inter&eacute;s','listadoBotones',1);

DROP TABLE IF EXISTS cms_lista_links;
CREATE TABLE cms_lista_links (
  kid_link int(11) NOT NULL auto_increment,
  titulo text NOT NULL,
  grupo int(11) NOT NULL default '1',
  link varchar(255) default NULL,
  icono varchar(50) default NULL,
  selector varchar(50) default NULL,
  posicion int(11) NOT NULL default '1',
  visible int(11) NOT NULL default '1',
  PRIMARY KEY  (`kid_link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS cms_noticias;
CREATE TABLE cms_noticias(
  kid_noticia INT AUTO_INCREMENT,
  titulo TEXT NOT NULL,
  resumen TEXT NULL,
  texto TEXT NULL,
  fecha DATE NOT NULL,
  link VARCHAR(255) NULL,
  imagen VARCHAR(255) NULL,
  visible INT NOT NULL DEFAULT 1,
  PRIMARY KEY(kid_noticia)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS cms_page_rank;
CREATE TABLE cms_page_rank(
  kid_rank INT AUTO_INCREMENT,
  fid_pagina INT NOT NULL,
  fid_usr INT NOT NULL,
  unico VARCHAR(15) NOT NULL,
  calificacion TINYINT,
  fecha DATETIME NOT NULL,
  UNIQUE (unico),
  PRIMARY KEY(kid_rank)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS cms_page_comments;
CREATE TABLE cms_page_comments(
  kid_comment INT AUTO_INCREMENT,
  fid_pagina INT NOT NULL,
  fid_usr INT NOT NULL,
  comentario TEXT,
  fecha DATETIME NOT NULL,
  anonimo TINYINT,
  fid_parent_comment INT,
  activo INT NOT NULL DEFAULT 1,
  PRIMARY KEY(kid_comment)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS cms_encuesta;
CREATE TABLE cms_encuesta(
  kid_votacion INT AUTO_INCREMENT,
  fid_encuesta INT NOT NULL,
  opcion INT NOT NULL,
  comentario VARCHAR(300),
  fecha_votacion DATETIME NOT NULL DEFAULT NOW(),
  activo INT NOT NULL DEFAULT 1,
  PRIMARY KEY(kid_votacion)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP VIEW IF EXISTS cms_view_base_estructura;
CREATE VIEW cms_view_base_estructura AS
SELECT *, 
padre AS iniPadre,
(SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre) as lvl1,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre)) as lvl2,
/*(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = padre)) as lvl2,*/
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre))) as lvl3,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre)))) as lvl4,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre))))) as lvl5,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre)))))) as lvl6,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre))))))) as lvl7,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre)))))))) as lvl8,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre))))))))) as lvl9,
(SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = (SELECT padre FROM cms_estructura WHERE kid_pagina = estructura_origen.padre)))))))))) as lvl10
FROM cms_estructura as estructura_origen WHERE estructura_origen.nivel <=10 ORDER BY estructura_origen.kid_pagina;


DROP VIEW IF EXISTS cms_view_estructura;
CREATE VIEW cms_view_estructura AS
SELECT *,
 IF(nivel > 0,
 CONCAT(
 IF(nivel > 1,
 CONCAT(
 IF(nivel > 2,
 CONCAT(
 IF(nivel > 3,
 CONCAT(
 IF(nivel > 4,
 CONCAT(
 IF(nivel > 5,
 CONCAT(
 IF(nivel > 6,
 CONCAT(
 IF(nivel > 7,
 CONCAT(
 IF(nivel > 8,
 CONCAT(
 IF(nivel > 9,
 CONCAT(
 IF(nivel > 10,NULL,''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl8) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl8),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl7) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl7),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl6) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl6),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl5) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl5),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl4) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl4),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl3) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl3),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl2) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl2),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = lvl1) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = lvl1),'.'),''),
 IF((SELECT orden FROM cms_estructura WHERE kid_pagina = iniPadre) < 10,'0',''),(SELECT orden FROM cms_estructura WHERE kid_pagina = iniPadre),'.'),''),
 IF(orden < 10,'0',''),orden)
 ,'0') as clasificacion, 
 (SELECT nombre FROM cms_estructura AS cms_local WHERE cms_local.kid_pagina=cms_base.padre) AS nombrePadre,
 (SELECT nombre FROM cms_estructura AS cms_local WHERE cms_local.kid_pagina=cms_base.lvl1) AS nombreAbuelo,
 (SELECT COUNT(cms_local.kid_pagina) FROM cms_estructura as cms_local WHERE cms_local.nivel=cms_base.nivel AND cms_local.padre=cms_base.padre AND cms_local.orden>cms_base.orden) AS conteo
FROM cms_view_base_estructura AS cms_base WHERE cms_base.nivel <=10 ORDER BY clasificacion;