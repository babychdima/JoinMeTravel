<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 14-Nov-16
 * Time: 12:09 AM
 */

require('databaseFile.php');
session_start();
$successMessage = '';
$errorMessage = '';
$user_id = $_GET['user_id'];
$profile_id = $_SESSION['joinMeTravel'];

if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}
//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

if(isset($_POST['btnSubmit'])){

    if(trim($_POST['message'])==''){
        $errorMessage = "enter message";
    }else{

        $msg = $_POST['message'];

        $date = new DateTime(null, new DateTimeZone('America/Toronto'));
        $d=$date->format(\DateTime::ISO8601);
        TravelDatabase::insertMessage($d, $profile_id, $user_id, $msg);

        $successMessage = "message was sent";
    }

}


?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">
    <title>Message</title>
</head>
<body id="profileBody">

<div class="container">
    <?php include "header.php";?>
    <div class="site-container">
        <div class="profileBox">
            <form class="form-horizontal" action="" method="post" role="form">

                <?php if (!empty($successMessage)){ ?>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="alert alert-success" style="width:100%">
                                <?php echo $successMessage; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($errorMessage)){ ?>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="alert alert-danger" style="width:100%">
                                <?php echo $errorMessage; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-sm-3 col-xs-3">
                        Your message:
                    </div>
                    <div class="col-sm-9 col-xs-9">
                        <textarea type="text" name="message" class="form-control"></textarea>
                    </div>
                </div>

                <br>
                <br>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-3 col-sm-offset-3">
                            <button type="submit" name="btnSubmit" class="btn btn-primary btn-block">Send message</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include "footer.php";?>
</div>
</body>
</html>

