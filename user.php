<?php
// require DB to any class that needs it before we start

require_once (LIB_PATH.DS."database.php");
class User extends DatabaseObject{

    protected static $table_name = "users";
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;


    // static methods when we don't need an object to work with the method, work with them from the class level
    // nonstatic method will need an object to start with, like create(), update(), delete()
    public function full_name(){
        if(isset($this->first_name) && isset($this->last_name)){
            return $this->first_name . " " . $this->last_name;
        }else{
            return "";
        }
    }

    public static function authenticate($username="", $password=""){
        global $database;
        $username = $database->escape_value($username);
        $password = $database->escape_value($password);

        $sql = "SELECT * FROM users";
        $sql .= " WHERE username = '{$username}'";
        $sql .= " AND password = '{$password}'";
        $sql .= " LIMIT 1";

        $result_array = self::find_by_sql($sql);
        return !empty($result_array)? array_shift($result_array) : false;
    }

    // Common Database Methods

    public static function find_all(){
        return self::find_by_sql("select * from " . self::$table_name);
    }

    public static function find_by_id($id=0){
        global $database;
        $result_array = self::find_by_sql("select * from ". self::$table_name ." where id ={$id} limit 1");
        // if $result_array empty, then return false, else get the item out of $result_array and return it
        return !empty($result_array)? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql=""){
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while($row = $database->fetch_array($result_set)){
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    private static function instantiate($record){
        // it is good to check $record exists and is an array

        // this is a simple, long form approach to assign values
        $object = new self;
//        $object->id         = $record['id'];
//        $object->username   = $record['username'];
//        $object->password   = $record['password'];
//        $object->first_name = $record['first_name'];
//        $object->last_name  = $record['last_name'];


        // more dynamic, short form approach to assign values
        foreach($record as $attribute => $value){
            /* maybe the record in the DB has some extra fields that doesn't exists in the class
             so we do extra check */
            if($object->has_attribute($attribute)){
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute){
        $object_vars = get_object_vars($this);
        return array_key_exists($attribute, $object_vars);
    }


    protected function attributes(){
        // return an array of attribute keys and thier values
        return get_object_vars($this);
    }
    // we could have pormote these three methods into the DatabaseObject class


    /*save() prevent mistakes, *** cuz create() will create a record, but if we said create again
    it will create the record again, create() does't has a machinesm to check if the record already
    created or not,we add sth on create() to check first then create, but it is easier to do it like this
    (opinion of auther)this will make the object smart*/

    public function save(){
        // A new record won't have an id yet

        return isset($this->id)? $this->update() : $this->save();
    }

    // if we gonna use save, create and update should be protected
    public function create(){
        global $database;
        // don't forget sql syntax and good habits:
        // INSERT INTO table (key, key) VALUES ('value', 'value')
        // single-quotes aroound all values
        // escape all values to prevent SQL injection

        $sql = "INSERT INTO ". self::$table_name ." (username, password, first_name, last_name)";
        $sql .= " VALUES (";
        $sql .= "'" . $database->escape_value($this->username). "',";
        $sql .= " '" . $database->escape_value($this->password) . "',";
        $sql .= " '" . $database->escape_value($this->first_name) . "',";
        $sql .= " '" . $database->escape_value($this->last_name) ."')";
        if($database->query($sql)){
            // we just inserted a record into DB, but we don't hava the id for this,
            // so we get the id using insert_id() and we have everything about this object
            $this->id = $database->insert_id();
            return true;
        }else{
            return false;
        }
    }

    protected function update(){
        global $database;

        $sql = "UPDATE ". self::$table_name ." SET";
        $sql .= " username='" . $database->escape_value($this->username). "',";
        $sql .= " password='" . $database->escape_value($this->password). "',";
        $sql .= " first_name='" . $database->escape_value($this->first_name). "',";
        $sql .= " last_name='" . $database->escape_value($this->last_name). "'";
        $sql .= " WHERE id=" . $database->escape_value($this->id);
        $database->query($sql);
        return($database->affected_rows() == 1)? true : false;

    }

    public function delete(){
        global $database;
        $sql = "DELETE FROM " . self::$table_name;
        $sql .= " WHERE id = " . $database->escape_value($this->id);
        $sql .= " LIMIT 1";
        $database->query($sql);
        return($database->affected_rows() == 1)? true : false;

    }


}

?>