<?php
/* SITE_ROOT"..." won't help us here, cuz when this page first load, it has to know
how to get to that initialize.php, once it gets there then eth is defined and taking care of
but we don't have these convieniance helpers (SITE_ROOT)to work with */

/* SITE_ROOT used so that PHP can locate other PHP files and puts them together
so we can work with them*/
require_once("../../includes/initialize.php");

if($session->is_logged_in()){redirect_to("index.php"); }

if(isset($_POST["submit"])){ // form has been submitted
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // check database to see if username/password exists
    $found_user = User::authenticate($username, $password);

    if($found_user){
        $session->login($found_user);
        redirect_to("index.php");
    }else{
        // username/password combo was not found in the database
        $message = "Username/Password combination incorrect";
    }
} else { // form hasn't been submitted
    $username = "";
    $password = "";
    $message = "";
}

?>

<html>
<head>
    <title>Photo Gallery</title>
    <link href="../stylesheets/main.css" media="all" rel="stylesheet"
          type="text/css" />
</head>
<body>
<div id="header">
    <h1>Photo Gellery</h1>
</div>
<div id="main">
    <h2>Staff Login</h2>
    <?php echo output_message($message); ?>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>Username:</td>
                <td>
                    <input type="text" name="username" maxlength="30"
                           value="<?php echo htmlentities($username); ?>" />
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
</div>
<div id="footer">
    Copyright <?php echo date("Y", time());?>, Jehad Alghamdi
</div>

</body>
</html>
<?php if(isset($database)) {$database->close_connection(); } ?>
