<?php
// require DB to any class that needs it before we start
require_once (LIB_PATH.DS."database.php");

class Comment extends DatabaseObject{

    protected static $table_name = "comments";
    protected static $db_fields = array('id', 'photograph_id', 'created',
        'author', 'body');
    public $id;
    public $photograph_id;
    public $created;
    public $author;
    public $body;

    // can't use (new) cuz it is a key word
    public static function make($photo_id, $author="Anonymous", $body=""){
        if(empty(!$photo_id) && !empty($author) && !empty($body)) {
            $comment = new Comment();
            $comment->photograph_id = (int)$photo_id;
            $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
            $comment->author = $author;
            $comment->body = $body;
            return $comment;
        }else{
            return false;
        }
    }

    public static function find_comments_on($photo_id){
        global $database;
        $sql = "SELECT * FROM " . self::$table_name;
        $sql .= " WHERE photograph_id =" . $database->escape_value($photo_id);
        $sql .= " ORDER BY created ASC";
        return self::find_by_sql($sql);
    }

    // Common Database Methods, if we had DatabaseObject class we wouldn't need to copy and paste each time
    // cuz any class extends DatabaseObject will use all these common methods

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

    public static function count_all(){
        global $database;
        $sql= "SELECT COUNT(*) FROM " .self::$table_name;
        /* find_by_sql isn't gonna work for us cuz it does instantiate & return object
        we don't want that, we just want the count, so we're gonna run the query using $database*/
        $result_set = $database->query($sql);
        /* the query will return a record even though it was a single value, so we need to fetch
        // the first row from the $result_ser*/
        $row = $database->fetch_array($result_set);
        /* even though the record has a single value, but we need to pull it out since it
        // is the first value in the record*/
        return array_shift($row);

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
        /*get_object_vars() has two issues: 1- returns all the attributs that it has access to
        (called from inside a class, then returs private and protected)
        2- some attributes has no database fields, two reasons it is not good for our use
        to solve these two issues we will create get_attributes();*/
    }

    protected function get_attributes(){
        global $database;
        $attributes = array();
        foreach(self::$db_fields as $field){
            if(property_exists($this, $field)){
                $attributes[$field] = $this->$field; // $this->$field; field here is dynamic, don't let that confuse you
            }
        }
        return $attributes;
        /*this will work for our purpose, but if we have a big DB with hundred fields how we can do that?
        we can use SQL statement (SHOW FIELDS FROM users) then we buld our associative array ...*/
    }

    protected function sanitized_attributes(){
        global $database;
        //$attributes = get_object_vars($this);
        $cleaned_attributes = array();
        foreach($this->get_attributes() as $key => $value){
            $cleaned_attributes[$key] = $database->escape_value($value);
        }
        return $cleaned_attributes;
    }
    // we could have pormote these three methods into the DatabaseObject class


    /*save() prevent mistakes, *** cuz create() will create a record, but if we said create again
    it will create the record again, create() does't has a machinesm to check if the record already
    created or not,we add sth on create() to check first then create, but it is easier to do it like this
    (opinion of auther)this will make the object smart*/

    public function save(){
        // A new record won't have an id yet

        return isset($this->id)? $this->update() : $this->create();
    }

    // if we gonna use save, create and update should be protected
    public function create(){
        global $database;
        $attributes = $this->sanitized_attributes();

        // don't forget sql syntax and good habits:
        // INSERT INTO table (key, key) VALUES ('value', 'value')
        // single-quotes aroound all values
        // escape all values to prevent SQL injection

        $sql = "INSERT INTO ". self::$table_name ." (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
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
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach($attributes as $key => $value){
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE ". self::$table_name ." SET ";
        $sql .= join(", ", $attribute_pairs);
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