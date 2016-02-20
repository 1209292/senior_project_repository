<?php
    require_once ("../includes/database.php");
    require_once ("../includes/user.php");

if(isset($database)) { echo "true";} else { echo "false";}
echo "<br />";
echo $database->escape_value("It's Working?");
echo "<br />";
//$sql = "insert into users (id, username, password, first_name, last_name)";
//$sql .= " values (1, 'almiringi14', 'tiger', 'jehad', 'alghamdi')";
//$result = $database->query($sql);

$sql = "select * from users where id = 1";
$result = $database->query($sql);
$found_user = mysqli_fetch_assoc($result);
echo $found_user['username'];

echo "<hr />";

$found_user = User::find_by_id(1);
echo $found_user['username'];

echo "<hr />";

$found_user = User::find_all();
while($user = $database->fetch_array($found_user)){
    echo "User: " . $user['username'];
    echo " Name: " . $user['first_name'] . " " . $user["last_name"];
}

?>