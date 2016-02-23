<?php
require_once ("../../includes/initialize.php");
if(!$session->is_logged_in()){redirect_to("login.php"); }
?>
<?php include_layout_template('admin_header.php'); ?>
<?php


// create
//$user = new User();
//$user->username = "jaalghamdi";
//$user->password = "secret";
//$user->first_name= "adnan";
//$user->last_name= "alghamdi";
//$user->create();


// update
//$user = User::find_by_id(2);
//$user->first_name = "Adel";
//$user->update();


// delete
$user = User::find_by_id(5);
$user->delete();
echo $user->first_name . " was deleted."; //**although the record was deleted but the object still around


?>

<?php include_layout_template('admin_footer.php'); ?>