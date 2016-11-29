<?php

/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 2016-11-13
 * Time: 8:51 PM
 */
include ("Validation.php");
include ("databaseFile.php");
session_start();
//for contactUs form
$status=-1;
$errorMessage="";
$name="";
$email="";
$message="";

//for feedback form
$statusF=-1;
$errorMessageF="";
$like="";
$time="";
$visit="";
$feedback="";


//check if a session exist
if (!isset($_SESSION['joinMeTravel'])){
    ?>
<style>
    #tab2{
        display:none;
    }
    #tab2-nav{
        display: none;
    }
</style>
<?php


}




//contact us form
if (isset($_POST['btnContactUs'])){

    //validation
    if (!Validation::isRequired($_POST['name'])){
        $status = 0;
        $errorMessage = "Enter your name";
    }
    else if (!Validation::isRequired($_POST['email'])){
        $status = 0;
        $errorMessage = "Enter your email";
    }
    else if (!Validation::isRequired($_POST['message'])){
        $status = 0;
        $errorMessage = "Enter your question";
    }
    else {
        $status=1;
        $name=$_POST['name'];
        $email=$_POST['email'];
        $message=$_POST['message'];
        $errorMessage="Thank you! Your question was successfully sent";
        TravelDatabase::insertContactUs($name, $email, $message);
    }
}
else if (isset($_POST['btnFeedback'])){
    //validation
    if (isset($_POST['like'])){

       $like=$_POST['like'];

    }
    if (isset($_POST['time'])){
        $time=$_POST['time'];
    }
    if (isset($_POST['visit'])){
        $visit=$_POST['visit'];
    }
    if (isset($_POST['comment'])){
        $feedback=$_POST['comment'];
    }
    $status=1;
    $errorMessage="Thank you for your feedback!";
    TravelDatabase::insertFeedback($_SESSION['joinMeTravel'], $like, $time, $visit, $feedback);
}


?>
<!doctype html>
<html>
<head>
    <title>
        Join Me Travel
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">
    <style>
        .tabbable-panel {
            border:1px solid #eee;
            padding: 10px;
        }

        /* Default mode */
        .tabbable-line > .nav-tabs {
            border: none;
            margin: 0px;
        }
        .tabbable-line > .nav-tabs > li {
            margin-right: 2px;
        }
        .tabbable-line > .nav-tabs > li > a {
            border: 0;
            margin-right: 0;
            color: #737373;
        }
        .tabbable-line > .nav-tabs > li > a > i {
            color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
            border-bottom: 4px solid #fbcdcf;
        }
        .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
            border: 0;
            background: none !important;
            color: #333333;
        }
        .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
            color: #a6a6a6;
        }
        .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
            margin-top: 0px;
        }
        .tabbable-line > .nav-tabs > li.active {
            border-bottom: 4px solid #f3565d;
            position: relative;
        }
        .tabbable-line > .nav-tabs > li.active > a {
            border: 0;
            color: #333333;
        }
        .tabbable-line > .nav-tabs > li.active > a > i {
            color: #404040;
        }
        .tabbable-line > .tab-content {
            margin-top: -3px;
            background-color: #fff;
            border: 0;
            border-top: 1px solid #eee;
            padding: 15px 0;
        }
        .portlet .tabbable-line > .tab-content {
            padding-bottom: 0;
        }

        /* Below tabs mode */

        .tabbable-line.tabs-below > .nav-tabs > li {
            border-top: 4px solid transparent;
        }
        .tabbable-line.tabs-below > .nav-tabs > li > a {
            margin-top: 0;
        }
        .tabbable-line.tabs-below > .nav-tabs > li:hover {
            border-bottom: 0;
            border-top: 4px solid #fbcdcf;
        }
        .tabbable-line.tabs-below > .nav-tabs > li.active {
            margin-bottom: -2px;
            border-bottom: 0;
            border-top: 4px solid #f3565d;
        }
        .tabbable-line.tabs-below > .tab-content {
            margin-top: -10px;
            border-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
    </style>
    </head>
<body>


    <div class="container"><?php include "header.php"?>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <h1 class="h1">
                    Contact Us </h1>
            </div>
        </div>
    </div>  <div class="container">
        <div class="row">  <div class="col-md-8">
    <div class="tabbable-panel">
        <div class="tabbable-line">
            <ul class="nav nav-tabs ">
                <li class="active">
                    <a href="#tab1" data-toggle="tab">
                        Contact form </a>
                </li>
                <li>
                    <a href="#tab2" id="tab2-nav" data-toggle="tab">
                        Feedback </a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">

                        <div class="well well-sm">
                            <?php if ($status==0){?>
                                <div class="alert alert-danger">
                                    <?php echo $errorMessage ?>
                                </div>
                            <?php }
                            else if ($status==1){?>
                                <div class="alert alert-success">
                                    <?php echo $errorMessage ?>
                                </div>
                            <?php }?>
                            <form action="contactUs.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">
                                                Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Enter name" />
                                        </div>
                                        <div class="form-group">
                                            <label for="email">
                                                Email Address</label>
                                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                                <input type="email" class="form-control" name="email" placeholder="Enter email"/></div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">
                                                Message</label>
                                            <textarea name="message" name="message" class="form-control" rows="9" cols="25"
                                                      placeholder="Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-primary pull-right" name="btnContactUs" >

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <div class="tab-pane" id="tab2">
                    <form class="form-horizontal" action="contactUs.php" method="post">
                        <fieldset>

                            <!-- Form Name -->
                            <legend>Feedback</legend>

                            <!-- Multiple Radios (inline) -->
                    <div class="form-group">
                        <label class="col-md-5 control-label" for="like">Do you like this website?</label>
                        <div class="col-md-4" style="text-align: left">
                            <label class="radio-inline" for="like-0">
                                <input type="radio" name="like" id="like-0" value="Yes" checked="checked">
                                Yes
                            </label>
                            <label class="radio-inline" for="like-1">
                                <input type="radio" name="like" id="like-1" value="No">
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Multiple Radios (inline) -->
                    <div class="form-group">
                        <label class="col-md-5 control-label" for="time">How long have you been using this website?</label>
                        <div class="col-md-4" style="text-align: left">
                            <div class="radio">
                            <label class="radio" for="time-0">
                                <input type="radio" name="time" id="time-0" value="month" checked="checked">
                                Less than a month
                            </label>
                            <label class="radio" for="time-1">
                                <input type="radio" name="time" id="time-1" value="halfYear">
                                Month - Half a year
                            </label>
                            <label class="radio" for="time-2">
                                <input type="radio" name="time" id="time-2" value="year">
                                Half a year
                            </label>
                                </div>
                        </div>
                    </div>
                            <div class="form-group">
                                <label class="col-md-5 control-label" for="visit">How often do you visit our website?</label>
                                <div class="col-md-4" style="text-align: left">
                                    <div class="radio">
                                        <label class="radio" for="visit-0">
                                            <input type="radio" name="visit" id="visit-0" value="regularly" checked="checked">
                                            Regularly
                                        </label>
                                        <label class="radio" for="visit-1">
                                            <input type="radio" name="visit" id="visit-1" value="week">
                                            Few times a week
                                        </label>
                                        <label class="radio" for="visit-2">
                                            <input type="radio" name="visit" id="visit-2" value="month">
                                            Few times a month
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Textarea -->
                            <div class="form-group">
                                <label class="col-md-5 control-label" for="comment">Leave your feedback about JoinMeTravel</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" id="comment" name="comment"placeholder="Do you want to tell us something?"></textarea>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <input type="submit" class="btn btn-primary pull-right" name="btnFeedback">

                            </div>
                    </fieldset>
                    </form>

                </div>
               </div>
        </div>
    </div>
</div>

        <div class="col-md-4">
            <form>
                <legend><span class="glyphicon glyphicon-globe"></span> About Site</legend>
                <address>
                    <strong>JoinMeTravel</strong><br>
                    Created by: Dmytro Babych, Yuliia Shymbryk, Aishwarya Gokhale<br>


                </address>
                <address>
                    <strong>Email</strong><br>
                    <p>joinMeTravel@gmail.com</p>
                </address>
            </form>
        </div>
    </div>
    <?php include "footer.php"?>
</div>
</body>