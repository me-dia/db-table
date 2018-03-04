<?php

define("DB_HOST","hostname");
define("DB_USER","username");
define("DB_PASSWORD","password");
define("DB_NAME","dbname");


require_once "wordpress.php";


$all = wordpress::find_all();
$num = wordpress::count_all();

$rec = new wordpress();
$rec->post_content = "new content";
$rec->save();

$rec = wordpress::find_by_id(27);
//or $rec->ID = 20;

$rec->delete();

?>
