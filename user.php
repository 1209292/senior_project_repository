<?php
// require DB to any class that needs it before we start

require_once ("database.php");
class User{

    public static function find_all(){
        global $database;
        $result_set = $database->query("select * from users");
        return $result_set;
    }

    public static function find_by_id($id=0){
        global $database;
        $result_set = $database->query("select * from users where id ={$id} limit 1");
        $found = $database->fetch_array($result_set);
        return $found;
    }

    public static function find_by_sql($sql=""){
        global $database;
        $result_set = $database->query($sql);
        return $result_set;
    }

}

?>