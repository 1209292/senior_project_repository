<?php
    require_once ("../includes/initialize.php");


$user = User::find_by_id(1);
echo $user->full_name();

echo "<hr />";

$users = User::find_all();
foreach($users as $user)
    echo "User: " . $user->username;
    echo " Name: " . $user->first_name . " " . $user->last_name;


?>