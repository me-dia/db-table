# db-table
library to manage all db operations 
<br/>
operations: like select update insert delete
<br/>
after we have defined the db connection parameters<br/>
define("DB_HOST","localhost");<br/>
define("DB_USER","root");<br/>
define("DB_PASSWORD","root");<br/>
define("DB_NAME","wordpress");<br/>

and we defined the table in a class<br/> 
<PRE>
require_once "db_table.php";
class wordpress extends db_table{
    protected static $classname = "wordpress";
    protected static $fields = ["post_content","post_title","post_type"];
    protected static $table_name = "wp_posts";
    protected static $id = "ID";
}

we could use the static methods:

//SELECT//
wordpress::find_all();

//INSERT//
$rec = new wordpress();
$rec->post_content = "some content";
$rec->save();

//UPDATE//
$rec = wordpress::find_by_id(27);
$rec->post_content = "some content";
$rec->save();

//DELETE//
$rec = wordpress::find_by_id(27);
//or $rec->ID = 20;
$rec->delete();

</PRE>