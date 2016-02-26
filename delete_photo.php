<?php
require_once ("../../includes/initialize.php");
if(!$session->is_logged_in()){redirect_to("login.php"); }
?>

<?php
if(empty($_GET['id'])){
$session->message("No photograph ID was provided.");
redirect_to("list_photos.php");
}
?>
<?php
$photo = Photograph::find_by_id($_GET['id']);
if($photo && $photo->destroy()){
$session->message("The photo {$photo->filename} was deleted"); /* even though we deleted the photo we still hva the object around
                                                                and this is how we could display the photo->filename */
    redirect_to("list_photos.php");
} else {
    $session->message("The photo couldn't be deleted");
    redirect_to('list_photos.php');
}
?>
<!-- cuz we don't have header and footer we will close the database, it is closed automatically BUT
 it is a good habit to close it.-->
<?php  if(isset($database)) { $database->close_connection(); } ?>


