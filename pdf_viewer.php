
<?php
/**
 * Created by PhpStorm.
 * User: windows
 * Date: 14/03/2016
 * Time: 11:05 am
 */
$file = $_GET['file_name'];

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename='" . $_GET['file_name'] . "'");
header("Content-Transfer-Encoding: binary");
header("Accept-Ranges: bytes");
@readfile($file);
?>
