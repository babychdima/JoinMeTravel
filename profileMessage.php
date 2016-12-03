<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 30-Nov-16
 * Time: 6:07 PM
 */

require('databaseFile.php');
session_start();
$successMessage = '';
$errorMessage = '';

if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}
//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

$inc_msg = TravelDatabase::getMessages($_SESSION['joinMeTravel']);

    if(isset($_GET['submit'])){

        if(trim($_GET['message'])==''){
            $errorMessage = "enter message";
        }else {

            $msg_id = $_GET['msg_id'];
            $msg = $_GET['message'];
            $date = new DateTime(null, new DateTimeZone('America/Toronto'));
            $d = $date->format(\DateTime::ISO8601);
            TravelDatabase::insertMessage($d, $_SESSION['joinMeTravel'], $_GET['user_id'], $msg);
            TravelDatabase::updateRepliedStatus($msg_id);
            $successMessage = "message was sent";

            $inc_msg = TravelDatabase::getMessages($_SESSION['joinMeTravel']);
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
    <style>
        .panel-default{
            min-height: 2em;
        }

        #boxHeader{

            padding-right: 30px;
            padding-left: 140px;
            text-align: center;
            font-size: 17px;
            padding-bottom: 10px;
        }
        #date{
            float: right;
            font-size: 12px;
            padding-bottom: 10px;
        }

    </style>

</head>
<body id="profileBody">

<div class="container">
    <?php include "header.php";?>
    <div class="site-container">

            <div class="profileBox">
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

                <?php foreach ($inc_msg as $im){?>
                <form method="get" action="">

                    <div class="row" >
                        <div class="col-sm-12 col-xs-12" id="boxHeader">
                            <span >From: <?php echo $im['user_firstname'];?> <?php echo $im['user_lastname'];?></span>
                            <span id="date"><?php echo date_format(new DateTime($im['date']), 'g:i a \o\n jS F Y');?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-xs-2">
                            Message
                        </div>
                        <div class="col-sm-10 col-xs-10" id="boxContent">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo $im['message_content'];?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($im['replied']==0){?>
                    <div class="row">
                        <div class="col-sm-2 col-xs-2">
                            Reply here
                        </div>
                        <div class="col-sm-10 col-xs-10">
                            <textarea type="text" name="message" class="form-control"></textarea>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <input type="hidden" name="user_id" value="<?php echo $im['message_from'];?>"/>
                        <input type="hidden" name="msg_id" value="<?php echo $im['message_id'];?>"/>
                        <button type="submit" name="submit" class="btn btn-primary btn-md" id="editbtn" style="width: 8em; margin-right: 2%">Reply</button>
                    </div>
                    <?php }?>
                    <hr style="height: 2px; background-color: #25183d">
                </form>
                <?php } ?>
            </div>

    </div>
    <?php include "footer.php";?>
</div>
</body>
</html>
