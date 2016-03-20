<?php

require_once ("../../includes/database.php");
require_once ("../../includes/member.php");
require_once ("../../includes/session.php");
require_once ("../../includes/functions.php");
require_once ("../../includes/admin.php");

if($session->is_logged_in()){redirect_to("manage_content.php"); }

if(isset($_POST["submit"])){ // form has been submitted
    $id = trim($_POST['id']);
    $password = trim($_POST['password']);

    // check database to see if username/password exists
    $found_user = Admin::authenticate($id, $password);

    if($found_user){
        $session->login($found_user);
        redirect_to("manage_content.php");
    }else{
        // username/password combo was not found in the database
        $message = "ID/Password combination incorrect";
    }
} else { // form hasn't been submitted
    $id = "";
    $password = "";
    $message = "";
}

?>
<?php include("../layouts/admin_header.php"); ?>
<div id="main">
    <div id="navigation">
        </div>
    <h2>Staff Login</h2>
    <?php echo output_message($message); ?>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>ID:</td>
                <td>
                    <input type="text" name="id" maxlength="30"
                           value="<?php echo htmlentities($id); ?>" />
                </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>
                    <input type="password" name="password" maxlength="30"
                           value="<?php echo htmlentities($password); ?>" />
                </td>
            </tr>
            <tr>

                <td colspan="2">
                    <input type="submit" name="submit" value="login" />
                </td>
            </tr>
        </table>

    </form>

    <?php include ("../layouts/admin_footer.php");?>
