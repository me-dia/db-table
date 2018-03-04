<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//echo (integer)ini_get('display_errors');

define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","root");
define("DB_NAME","wordpress");


require_once "wordpress.php";

/*$m = new mysqldb();
$m->db_connect();
$res = $m->query("select * from wp_posts");
$m->db_close();
print_r($res);
*/
class teszt{

    public static $classname2 = "teszttttt";
}

//print_r(wordpress::find_all());exit();
//print_r(wordpress::count_all());
//print_r(wordpress::find_by_id(1));
//$rec = wordpress::find_by_id(31);
//$rec = new wordpress();
//$rec->post_content = "juhheeeee";
//$rec->save();
$rec = wordpress::find_by_id(27);
//$rec->ID = 20;

$rec->delete();
exit("xxxxx");
?>
