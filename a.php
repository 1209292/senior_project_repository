<?php var_dump($_POST); ?>
<?php var_dump($_GET); ?>
<?php if(isset($_POST["submit"])){
    echo "Submitted <br />";
    echo "" . $_POST["username"] . "<br />";
    echo "" . $_POST["password"] . "<br />";
    $message = "This is insane! <br />";
} else {
    echo "First Visit <br />"	;
}
?>

<form action="a.php?username=aa&password=sese" method="post">
    <table>
        <tr>
            <td>Username:</td>
            <td>
                <input type="text" name="username" maxlength="30"
                       value="" />
            </td>
        </tr>
        <tr>
            <td>Password:</td>
            <td>
                <input type="password" name="password" maxlength="30"
                       value="" />
            </td>
        </tr>
        <tr>

            <td colspan="2">
                <input type="submit" name="submit" value="login" />
            </td>
        </tr>
    </table>

</form>

<?php

function output_message($message=""){
    if(!empty($message)){
        return "<p class=\"message\">{$message}</p>";
    }else{
        return "";
    }
}

function num($num = 5){
    if($num == 5){
        echo "Five";
    }else{
        echo "Zero";
    }
}

?>


