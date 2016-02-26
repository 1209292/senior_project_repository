<?php    require_once ("../includes/initialize.php");  ?>
<?php
if(empty($_GET['id'])){
    $session->message("No photo ID was provided");
    redirect_to("index.php");
}
$photo = Photograph::find_by_id($_GET['id']);
if(!$photo){
    $session->message("The photo could not be located");
    redirect_to("index.php");
}




// after checking the id and finding the corresponding photo, now we ready to process the form

if(isset($_POST['submit'])){
    $author = trim($_POST['author']);
    $body = trim($_POST['body']);

    $new_comment = Comment::make($photo->id, $author, $body);
    // make will instantiate a Comment object and assign values to that object, we could have done it
    // here, but we pushed all the complexity to the Comment class.
    if($new_comment && $new_comment->save()){
        // comment saved
        // No message needed; seeing the comment is proof enough.

        /*
         * IMPORTANTE: you could just let the page render from here,
         * but then if you hit reload, the form will try to resubmit the comment
         * so redirect to the same page instead or unset the values*/
        redirect_to("photo.php?id=" . $photo->id);
    } else{
        // failed to save
        $message = "There was an error that prevented the comment from being saved.";
        // you could add a feature to track what went wrong and push all wrongs into an array
    }
}else{
    $author ="";
    $body = "";
}
?>
<?php
// there is a good reason why we put this line here not above (Listing commments minutes 1:00)
// the reason is about making sql and speed

// $comments = Comment::find_comments_on($photo->id); // we used the below way, better
$comments = $photo->comments();
?>

<?php include_layout_template('header.php'); ?>

<a href="index.php">&laquo; Back</a>
<br />

<div style="margin-left: 20px;">
    <img src="<?php echo $photo->image_path(); ?>"/>
    <p><?php echo $photo->caption; ?></p>

    <!-- List comments -->
    <div id="comments">
        <?php foreach($comments as $comment): ?>
        <div class="comment" style="margin-bottom: 2em;">
            <div class="author">
                <?php htmlentities($comment->author) ?> wrote:
            </div>
            <div class="body">
                <?php echo strip_tags($comment->body, '<strong><em><p>') ?>
            </div>
            <div class="meta-info" style="font-size: 0.8em;">
                <?php echo datetime_to_text($comment->created) ; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(empty($comments)) { echo "No Comments." ; } ?>
    </div>


    <div id="comments-form">
        <h3>New Comments</h3>
        <?php echo output_message($message); ?>
        <form action="photo.php?id=<?php echo $photo->id; ?>" method="POST">
            <table>
                <tr>
                    <td>Your name:</td>
                    <td><input type="text" name="author" value="<?php echo $author; ?>" /></td>
                </tr>
                <tr>
                    <td>Your comment:</td>
                    <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="submit" /></td>
                </tr>
            </table>
        </form>
    </div>

</div>
<?php include_layout_template('footer.php'); ?>


