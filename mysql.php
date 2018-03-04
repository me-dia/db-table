<?php
class mysqldb{
    private $connection;
    private $last_query;

    public function db_connect() {
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

    public function insert_id() {
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }

    public function query($sql) {
        $this->last_query = $sql;
        $result = mysqli_query($this->connection,$sql);
        return $result;
    }
    public function fetch_row($result){
        $row=mysqli_fetch_assoc($result);
        return $row;
    }

    public function db_close() {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }


    public function escape_value($value) {
        if (function_exists("mysqli_real_escape_string")) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            $value = mysqli_real_escape_string($this->connection,$value);
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if (!get_magic_quotes_gpc()) {
                $value = addslashes($value);
            }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;
    }
}
?>
