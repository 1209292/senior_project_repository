<?php
require_once ("../../includes/initialize.php");
if(!$session->is_logged_in()){redirect_to("login.php"); }
?>

<?php
// find all photos
$photos = Photograph::find_all();
?>

<?php include_layout_template('admin_header.php'); ?>

<h2>Photographs</h2>

<?php  echo output_message($message); ?>

<table class="borderd">
    <tr>
        <th>Image</th>
        <th>Filename</th>
        <th>Caption</th>
        <th>Size</th>
        <th>Type</th>
        <th>Image path</th>
        <th>Comments</th>
        <th>&nbsp;</th>
    </tr>

<?php foreach($photos as $photo): ?>
    <tr>
        <td><img src="../<?php echo $photo->image_path(); ?>" alt="NOT FOUND" width="100" /></td>
        <td><?php echo $photo->filename; ?></td>
        <td><?php echo $photo->caption; ?></td>
        <td><?php echo $photo->size_as_text(); ?></td>
        <td><?php echo $photo->type; ?></td>
        <td><?php echo $photo->image_path(); ?></td>
        <td>
            <!-- if we will use this a lot, it makes more sense to write a SQL stat that count
            the records instead of returning them (COUNT in sql)-->
            <a href="comments.php?id=<?php echo $photo->id; ?>">
                <?php  echo count($photo->comments());?> </a>

        </td>
        <td><a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a> </td>
    </tr>
    <?php endforeach; ?>
</table>
<br />
<a href="photo_upload.php">Upload a new photograph</a>

<?php include_layout_template('admin_footer.php'); ?>


