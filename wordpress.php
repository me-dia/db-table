<?php
require_once "db_table.php";
class wordpress extends db_table{

    protected static $classname = "wordpress";
    protected static $fields = ["post_content","post_title","post_type"];
    protected static $table_name = "wp_posts";
    protected static $id = "ID";
}

?>
