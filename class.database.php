<?php

class MySQLDatabase {

    private $connection;
    public $last_query;
    private $magic_quotes_active;
    private $real_escape_string_exists;

    public function __construct() {
        $this->open_connection();
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists = function_exists("mysqli_real_escape_string");
    }

    public function open_connection() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
        if (!$this->connection) {
            die("Database connection failed: " . mysqli_error($this->connection));
        } else {
            $db_select = mysqli_select_db($this->connection, DB_NAME);
            $karakter = "SET CHARACTER SET utf8";
            mysqli_query($this->connection,$karakter);
            if (!$db_select) {
                die("Database selection failed: " . mysql_error());
            }
        }
    }

    public function close_connection() {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql) {
        $this->last_query = $sql;
        $result = mysqli_query($this->connection,$sql);
        $this->confirm_query($result);
        return $result;
    }

    public function escape_value($value) {
        if ($this->real_escape_string_exists) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if ($this->magic_quotes_active) {
                $value = stripslashes($value);
            }
            $value = mysqli_real_escape_string($this->connection,$value);
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if (!$this->magic_quotes_active) {
                $value = addslashes($value);
            }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;
    }

    // "database-neutral" methods
    public function fetch_array($result_set) {
        return mysqli_fetch_array($result_set,MYSQLI_ASSOC);
    }

    public function fetch_object($result_set) {
        return mysqli_fetch_object($result_set);
    }

    public function num_rows($result_set) {
        return mysqli_num_rows($result_set);
    }

    public function insert_id() {
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }

    public function insert_id_by_table($id, $table_name) {
        // get the last id inserted over the current db connection
        $sql = "SELECT " . $id . " FROM " . $table_name . " ORDER by " . $id . " DESC LIMIT 1";
        $query = $this->query($sql);
        return $this->fetch_object($query);
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->connection);
    }

    private function confirm_query($result) {
        if (!$result) {
            $output = "Database query failed: " . mysqli_error($this->connection) . "<br /><br />";
            $output .= "Last SQL query: " . $this->last_query;
            die($output);
        }
    }

}
global $database;
$database = new MySQLDatabase();
$db = & $database;
?>