<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 14-Nov-16
 * Time: 12:09 AM
 */

require('databaseFile.php');
session_start();
if(empty($_SESSION['joinMeTravel'])){
    header("Location:profilePage.php");
}

$user_name = '';
$occupation='';
$address = '';
$summary = '';

//updating information in user_table
if(isset($_POST['btnSubmit'])){

    $firstName = $_POST['firstName'];
    $occup = $_POST['occupation'];
    $addr = $_POST['address'];
    $summ = $_POST['summary'];


    TravelDatabase::updateUserInfo($_SESSION['joinMeTravel'], $firstName, $occup, $addr, $summ);

    header("Location:profilePage.php");
}

//populating fields by data from user_table
$user_info = TravelDatabase::getUserInfo($_SESSION['joinMeTravel']);
foreach ($user_info as $ui) {

    $user_name = $ui['user_firstname'];
    $occupation = $ui['occupation'];
    $dob = $ui['date_of_birth'];
    $address = $ui['address'];
    $summary = $ui['summary'];
}
?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">


</head>
<body id="profileBody">

<div class="container">
    <?php include "header.php";?>
    <div class="site-container">
        <div class="profileBox">
        <form class="form-horizontal" action="profileEditor.php" method="post" role="form">
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    First Name
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="firstName" class="form-control" value="<?php echo $user_name?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Occupation
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="occupation" class="form-control" value="<?php echo $occupation?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Address
                </div>
                <div class="col-sm-5 col-xs-5">
                    <input type="text" name="address" class="form-control" value="<?php echo $address?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    Summary
                </div>
                <div class="col-sm-9 col-xs-9">
                    <textarea type="text" name="summary" class="form-control" ><?php echo $summary?></textarea>
                </div>
            </div>

            <br>
            <br>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <button type="submit" name="btnSubmit" class="btn btn-primary btn-block">Submit changes</button>
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

