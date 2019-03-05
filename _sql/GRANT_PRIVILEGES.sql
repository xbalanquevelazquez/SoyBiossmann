192.168.150.194


SHOW GRANTS for dashboard@localhost;

| GRANT USAGE ON *.* TO 'dashboard'@'localhost' IDENTIFIED BY PASSWORD '*A8A43A492A9BD402ADADEA3888AB757DC8ED52DA' |
| GRANT ALL PRIVILEGES ON `dashboard`.* TO 'dashboard'@'localhost' |


USAR CON USER ROOT, en SERVER DESTINO
server:: 192.168.150.14
usr:root
psw:b10ssf3sc0b4r.-

GRANT ALL ON dashboard.* TO 'dashboard'@'192.168.150.194';

GRANT ALL PRIVILEGES ON dashboard.* TO 'dashboard'@'192.168.150.194' IDENTIFIED BY 'b10ssd4shb04rd.-';
