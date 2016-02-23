<?php
// difine the core paths
//define them as absolute paths to make sure thet require_once works as expected

/*DIRECTORY_SEPARATOR is a PHP pre-defined constant
 for windows it is backslash (\) for usnix it is slash (/) */
defined('DS')? NULL : define('DS', DIRECTORY_SEPARATOR);

// define the site root for the file system path not the server path
/* I'm doing it here with server path cuz couldn't find file system path (C:\wamp\www\photo_gallery)*/
defined('SITE_ROOT')? NULL : define('SITE_ROOT', 'C:'.DS.'wamp'.DS.'www'.DS.'photo_gallery');

// library path, includes file
defined('LIB_PATH')? NULL : define('LIB_PATH', SITE_ROOT.DS.'includes');


/*** the order is important, HOW? I don't know***/


// first load the config.php first
require_once(LIB_PATH.DS."config.php");


// load basic functions first, so theat eth after can use them
require_once(LIB_PATH.DS. "functions.php");


// Load core objects
require_once(LIB_PATH.DS. "session.php");
require_once(LIB_PATH.DS. "database.php");
require_once(LIB_PATH.DS. "database_object.php");


//Load database related classes
require_once(LIB_PATH.DS. "user.php");


?>