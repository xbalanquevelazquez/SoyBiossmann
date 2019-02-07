<?php 
$conn = mysql_connect(BD_HOST,DB_USER, DB_PSW) or die(mysql_error());
mysql_select_db(DB_NAME);