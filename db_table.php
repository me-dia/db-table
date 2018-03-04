<?php

require_once 'class.database.php';

class db_table{


    public static function find_all() {
        return self::find_by_sql("SELECT * FROM " . static::$table_name);
    }

    public static function find_by_id($id = 0) {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE " . static::$id ."=" .$id." LIMIT 1";

        $result_array = self::find_by_sql($sql);


        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql = "") {
        global $database;

        $result = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result)) {
            $object_array[] = self::instantiate($row);
        }

        return $object_array;
    }

    private static function instantiate($record){
      // Could check that $record exists and is an array
        $object = new static::$classname;

        foreach ($record as $attribute => $value) {
            if (in_array( $attribute , static::$fields)) {
                $object->$attribute = $value;
            }
        }

        if(isset($record[static::$id]))
            $object->{static::$id} = $record[static::$id];

        return $object;
    }

    public static function count_all() {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . static::$table_name;
        $result = $database->query($sql);
        $row = $database->fetch_array($result);
        return array_shift($row);
    }

    public function save() {

        // A new record won't have an id yet.

        $id = static::$id;
        return isset($this->$id) ? $this->update() : $this->create();
    }
    protected function attributes() {
        // return an array of attribute names and their values
        $attributes = array();
        $fields = static::$fields;
        foreach ($fields as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->{$field};
            }
        }
        return $attributes;
    }

    private function sanitize_attributes($attributes){
        global $database;
        $clean_attributes = array();
        // sanitize the values before submitting
        // Note: does not alter the actual value of each attribute
        foreach ($attributes as $key => $value) {
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }

    public function create() {
        global $database;
        // Don't forget your SQL syntax and good habits:
        // - INSERT INTO table (key, key) VALUES ('value', 'value')
        // - single-quotes around all values
        // - escape all values to prevent SQL injection


        $attributes = $this->attributes();
        //print_r($attributes);
        //if(!isset($this->__table_name__))
        //    $this->__table_name__ =  static::$table_name;

        $table_name =  static::$table_name;
        $id =  static::$id;
        $attributes = $this->sanitize_attributes($attributes);
        $sql = "INSERT INTO " . $table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        if ($database->query($sql)) {
            $this->$id= $database->insert_id();
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        global $database;

        // Don't forget your SQL syntax and good habits:
        // - UPDATE table SET key='value', key='value' WHERE condition
        // - single-quotes around all values
        // - escape all values to prevent SQL injection
        $table_name =  static::$table_name;
        $id =  static::$id;



        $attributes = $this->attributes();
        $attributes = $this->sanitize_attributes($attributes);
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE " . $table_name . " SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE $id=" . $database->escape_value($this->$id);

        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete() {


        global $database;
        // Don't forget your SQL syntax and good habits:
        // - DELETE FROM table WHERE condition LIMIT 1
        // - escape all values to prevent SQL injection
        // - use LIMIT 1


        $sql = "DELETE FROM " . static::$table_name;
        $sql .= " WHERE ".static::$id."=" . $database->escape_value($this->{static::$id});
        $sql .= " LIMIT 1";
        echo $sql;
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;

    }
}

