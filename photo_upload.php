<?php
require_once ("../../includes/initialize.php");
if(!$session->is_logged_in()){redirect_to("login.php"); }
?>

<?php
// we can put it into the photograph class statically, then use it when you need it
$max_file_size = 1048576; // expressed in bytes
if(isset($_POST['submit'])){
    $photo = new Photograph();
    $photo->caption = $_POST['caption'];
    $photo->attach_file($_FILES['file_upload']);
    if($photo->save()){
        // Success

        // we use session here cuz if we redirect we will lose teh message if it was $message= "something"
        $session->message("Photograph uploaded successfully");
        redirect_to("list_photos.php");
    }else{
        // Failure
        $message = join("<br />", $photo->errors);
    }
}

?>


<?php include_layout_template('admin_header.php'); ?>

<?php  echo output_message($message); ?>

<form action = "photo_upload.php" enctype="multipart/form-data" method="POST">

    <input type="hidden" name="MAX_FILE_SIZE" value="<?echo $max_file_size; ?>"

    <p><input type="file" name="file_upload"/></p>

    <p>Caption: <input type="text" name="caption" value=""/></p>

    <input type="submit" name="submit" value="upload"/>

</form>


<?php include_layout_template('admin_footer.php'); ?>
