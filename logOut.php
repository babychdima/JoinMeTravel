<?php/**
 * Created by PhpStorm.
 * User: n01121228
 * Date: 10/31/2016
 * Time: 12:40 PM
 */

include "header.php";?>
<?php
$expire = new DateTime('-1 month');
setcookie('email', '', $expire->getTimestamp(), "/", "localhost", false, true);
setcookie('password', '', $expire->getTimestamp(), "/", "localhost", false, true);

session_start();

echo isset($_SESSION['email']) ?  $_SESSION['email'] :  "not found";


//destroy username session variable
unset($_SESSION['email']);
header("Location:mainMainPage.php");
?>



<h1>Logged out successfully</h1>
<?php include "footer.php"?>

