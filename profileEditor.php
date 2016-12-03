<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 14-Nov-16
 * Time: 12:09 AM
 */

require('databaseFile.php');
require('Validation.php');
session_start();
if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}
//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

$user_first_name = '';
$user_last_name = '';
$occupation='';
$address = '';
$summary = '';
$errorMessage = '';

//populating fields by data from user_table
$user_info = TravelDatabase::getUserInfo($_SESSION['joinMeTravel']);
$userDescription = TravelDatabase::getUserDescriptionById($_SESSION['joinMeTravel']);

foreach ($userDescription as $ud){
    $user_city = $ud['destinationCity'];
    $user_country = $ud['destinationCountry'];
    $user_desc = $ud['description'];
    $user_stDate = date_format(new DateTime($ud['startDate']), "m/d/Y");
    $user_endDate = date_format(new DateTime($ud['endDate']), "m/d/Y");

}

foreach ($user_info as $ui) {

    $user_first_name = $ui['user_firstname'];
    $user_last_name = $ui['user_lastname'];
    $occupation = $ui['occupation'];
    $dob = $ui['date_of_birth'];
    $address = $ui['address'];
    $summary = $ui['summary'];
}

//updating information in user_table
if(isset($_POST['btnSubmit'])){

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $occup = $_POST['occupation'];
    $addr = $_POST['address'];
    $dest_city = $_POST['destCity'];
    $dest_country = $_POST['destCountry'];
    $st_date = date_format(new DateTime($_POST['startDate']), "Y-m-d");
    $end_date = date_format(new DateTime($_POST['endDate']), "Y-m-d");
    $desc = $_POST['description'];
    $summ = $_POST['summary'];


    $checkValidAddress = Validation::validAddress($addr);
    if($checkValidAddress ==true){
        $lat = Validation::$latitude;
        $long = Validation::$longitude;
        TravelDatabase::updateUserInfo($_SESSION['joinMeTravel'], $firstName,$lastName, $occup, $addr, $summ, $lat, $long);
        TravelDatabase::updateDescInfo($_SESSION['joinMeTravel'], $desc, $dest_country, $dest_city, $st_date, $end_date);

        header("Location:profilePage.php");
    }else if($checkValidAddress ==false){
        $errorMessage = "Enter valid address";
        $address = $_POST['address'];
    }






}


?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>
        .profileEdit{
            margin-left: 5em;
            margin-top: 2em;
        }
        textarea{
            resize: none;
        }
    </style>
</head>
<body id="profileBody">

<div class="container">
    <?php include "header.php";?>
    <div class="site-container">
        <div class="profileBox" id="profileDetails">

            <?php if (!empty($errorMessage)){ ?>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="alert alert-danger" style="width:100%">
                            <?php echo $errorMessage; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <form class="form-horizontal" action="profileEditor.php" method="post" role="form">
            <div class="row" >
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span>Edit your profile information</span>

                </div>
            </div>
            <div class="profileEdit">
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    First Name
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="firstName" class="form-control" value="<?php echo $user_first_name?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Last Name
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="lastName" class="form-control" value="<?php echo $user_last_name?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Occupation
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="occupation" class="form-control" value="<?php echo $occupation?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Address
                </div>
                <div class="col-sm-7 col-xs-7">
                    <input type="text" name="address" class="form-control" value="<?php echo $address?>">
                </div>
            </div>
                <hr>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Destination city
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="destCity" class="form-control" value="<?php echo $user_city?>">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Destination country
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" name="destCountry" class="form-control" value="<?php echo $user_country?>">
                </div>
            </div><br>

            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Start date
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" value="<?php echo $user_stDate?>" class="form-control" id="datepicker" name="startDate">
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    End date
                </div>
                <div class="col-sm-3 col-xs-3">
                    <input type="text" value="<?php echo $user_endDate?>" class="form-control" id="datepicker1" name="endDate">
                </div>
            </div><br>

            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Description
                </div>
                <div class="col-sm-9 col-xs-9">
                    <textarea type="text" name="description" rows="2" class="form-control" ><?php echo $user_desc?></textarea>
                </div>
            </div><hr>


            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    Summary
                </div>
                <div class="col-sm-9 col-xs-9">
                    <textarea type="text" name="summary" rows="4" class="form-control" ><?php echo $summary?></textarea>
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
    </div>
    <?php include "footer.php";?>
</div>
<script>
    $( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
</script>

</body>
</html>

