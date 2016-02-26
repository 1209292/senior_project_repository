<?php
require_once ("../../includes/initialize.php");
if(!$session->is_logged_in()){redirect_to("login.php"); }
?>

<?php
if(empty($_GET['comment_id'])){
$session->message("No comment ID was provided.");
redirect_to("list_photos.php");
}
?>
<?php
$comment = Comment::find_by_id($_GET['comment_id']);
if($comment && $comment->delete()){
$session->message("The comment was deleted"); /* even though we deleted the comment we still hava the object around
                                                                and this is how we could display the photo->filename */
    redirect_to("comments.php?id=" . $comment->photograph_id);
} else {
    $session->message("The comment couldn't be deleted");
    redirect_to('list_photos.php');
}
?>
<!-- cuz we don't have header and footer we will close the database, it is closed automatically BUT
 it is a good habit to close it.-->
<?php  if(isset($database)) { $database->close_connection(); } ?>


