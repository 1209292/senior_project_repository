<?php
/*
 * A class to help work with Sessions
 * In our case, primarily to manage ligging in & out
 *
 * keep in mind when you working with session that it is generally
 * inadvisable to store DB-related objects in sessions, you can store the id of an object
 * and then go and look in the databse for it, but not the whole object
 * the reason is because the object info can be updated by other users, then your session will have
 * an old version of the object
 *
 * */
class Session{

    private $logged_in=false;
    public $user_id;

    function __construct()
    {
        session_start();
        $this->check_login();
        if($this->logged_in){
            // do a certain action right away if user logged in, not redirecting, don't be falled by that
        }else{
            // actions to take care if a user not logged in
        }

    }

    public function is_logged_in(){
        return $this->logged_in;
    }

    public function login($user){
        // DB should find user based on username/password
        if($user){
            $this->user_id = $_SESSION['user_id'] = $user->id;
        }
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = false;
    }

    private function check_login(){
        if(isset($_SESSION['user_id'])){
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        }else{
            unset($this->user_id);
            $this->logged_in = false;
        }
    }
}

$session = new Session();

?>