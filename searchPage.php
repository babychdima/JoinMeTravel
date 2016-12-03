<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 07-Nov-16
 * Time: 1:31 PM
 */
require('databaseFile.php');
include ("Validation.php");
session_start();

//$search = $_GET['btnSearch'];
$var=3;
$status=4;
$searchString=null;
$locationsSearch=null;
$locationsSearch2=null;
$errorMessage=null;
$infoW=null;
$date1=0;
$date2=0;
$dateEnd=0;
$dateStart=0;
$exists=null;
$var=3;
$userName = '';
$user_info = '';
$tempL=null;
$l=null;

//---------show all the travellers----------------
$locations=TravelDatabase::showTravellers();
//$names=TravelDatabase::showTravellers2();
$var=0;

if (isset($_GET['btnSearch'])) {
    $searchString = $_GET['searchBox'];
    //getting start and end date from input field
    $stDate = $_GET ['startDate'];
    $endDate = $_GET ['endDate'];


    if (empty($searchString)) {
        if (!Validation::isRequired($_GET['searchBox'])) {
            $status = 0;
            $var = 2;
            $errorMessage = "Enter destination city or country";
        }
    }
    else if (empty($stDate)) {
        if (!Validation::isRequired($_GET['searchBox'])) {
            $status = 0;
            $var = 2;
            $errorMessage = "Enter start date";
        }
    }
    else if (empty($endDate)) {
        if (!Validation::isRequired($_GET['searchBox'])) {
            $status = 0;
            $var = 2;
            $errorMessage = "Enter end date";
        }
    }
    else {

//-----------search travellers-------------

        //converting start and end date from input field to the Y-m-d format
        $formattedStDate = date('Y-m-d', strtotime($stDate));

        $formattedEndDate = date('Y-m-d', strtotime($endDate));

        $locationsSearch = TravelDatabase::searchTravellers($_GET['searchBox']);

        //function to get intersection between dates
        function getIntersection($a1, $a2, $b1, $b2)
        {
            $a1 = strtotime($a1);
            $a2 = strtotime($a2);
            $b1 = strtotime($b1);
            $b2 = strtotime($b2);
            //$locationSearch=$locationsSearch;

            if ($b1 > $a2 || $a1 > $b2|| $a2 < $a1 || $b2 < $b1 ) {
                return false;
            }
            $start = $a1 < $b1  ? $b1 : $a1;

            $end = $a2 < $b2  ? $a2 : $b2;


            return  array('start' => $start, 'end' => $end);
        }

        //getting start and end date from database
        $userDesc = TravelDatabase::getUserDescription();
        foreach ($userDesc as $ud) {

            $startDate = $ud['startDate'];
            $endDate = $ud['endDate'];

            //assigning dates from inputs and database to variables
            $a1 = $startDate;
            $a2 = $endDate;
            $b1 = $formattedStDate;
            $b2 = $formattedEndDate;
            $locationSearch=$l;

            $intersection = getIntersection($a1, $a2, $b1, $b2);
            if ($intersection === false) {
                //echo 'No intersecting dates found';
            } else {
//            echo 'From ' . date('d-M-Y', $intersection['start']) . ' till ' . date('d-M-Y', $intersection['end']);
                //if intersection not false - get user id and user info
                $user_id = $ud['user_id'];

                $user_info[] = TravelDatabase::getUserInfoById($user_id);

                //$var=1;

            }

        }


        $status = 1;
        if($status==1) {
            $errorMessage = "Search results";
            $var = 1;
        }
        else

            $errorMessage = "not found";

        if($locationsSearch==""){
            $status=3;
            $errorMessage = "not found";
        }
    }



}

?>
<!doctype html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>

    <title>
        Search Page
    </title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        #user_id{
            width: 80%;
        }
    </style>

    <script src="http://maps.google.com/maps/api/js?key=AIzaSyB_vs15JiqBDy5yxypHVOZg9agHNpmgntY&sensor=false" type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Dosis|Kaushan+Script|Open+Sans|Pathway+Gothic+One|Roboto+Condensed|Roboto:700" rel="stylesheet">
    <link rel='stylesheet prefetch' href='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.css'>
    <link rel='stylesheet prefetch' href='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick-theme.css'>
    <link rel="stylesheet" href="css/slider.css">
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>
<body id="profileBody">

<div class="container">

    <?php include "header.php"?>

    <div class="site-container">

        <div  class="col-sm-12">

            <div class="profileBox" >


                <div class = "row">
                    <p class="lead" id="imageTextTitle">Search your destination city or country with the best available
                        dates for you and find travellers planning for the same destinations</p>
                    <form name="formSearch" method="get">
                        <div class="col-sm-3" >



                            <label>Enter city or country</label>
                            <div class="input_container">
                                <input type="text" id="user_id" onkeyup="autocomplete()"  name="searchBox">
                                <ul id="summary"></ul>

                            </div>



                        </div>


                        <div class="col-sm-3">

                            <label>Start Date</label>
                            <div class="input_container">
                                <input type="text"  class="datepicker"  name="startDate">
                            </div>
                        </div>


                        <div class="col-sm-3">

                            <label>End Date</label>
                            <div class="input_container">
                                <input type="text"  class="datepicker" name="endDate">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div id="boxHeader" >
                                <input type="submit" style="margin-left:13px;margin-top: 10px" class="btn btn-primary btn-md"  id="idBtnSearch" value="Search" name="btnSearch">

                            </div>
                        </div>

                        <div class="row" style="margin-left:20px">
                            <div class="col-sm-11">
                                <?php if ($status==0){?>

                                    <div class="alert alert-danger" style="width:100%">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php }

                                else if ($status==3){?>
                                    <div class="alert alert-danger" style="width:100%">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php }

                                else if ($status==1){?>

                                    <div class="alert alert-success" style="width:100%">

                                        <?php echo $errorMessage; ?>

                                    </div>
                                <?php }
                                ?>
                            </div>

                        </div>

                    </form>



                </div>


                <script>
                    $( ".datepicker" ).datepicker();
                </script>

            </div>

        </div>

        <div class="col-sm-12">
            <div  class="profileBox">


                <div class="row">

                    <div class="col-sm-12">
                        <div id="map">

                        </div>

                        <script type="text/javascript">

                            <?php
                            if($var==0){  ?>
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 2,
                                center: new google.maps.LatLng(18.496257 ,73.836944),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            map.setOptions({ minZoom: 2, maxZoom: 15 });
                                <?php
                                foreach($locations as $location): ?>{
                                var marker;

                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(<?php  echo $location['latitude']; ?>,<?php echo $location['longitude']; ?>),

                                    map: map

                                });

                                var contentString = "<?php echo $user_name = $location['user_firstname'] . " " . $location['user_lastname']; ;?>";
                                var infowindow1;

                                infowindow1 = new google.maps.InfoWindow({
                                    content: contentString,

                                });

                                infowindow1.open(map, marker);

                            }

                            <?php endforeach; ?>


                            <?php    }

                            else if($var==1){  ?>
                            var map1 = new google.maps.Map(document.getElementById('map'), {
                                zoom: 2,
                                center: new google.maps.LatLng(18.496257, 73.836944),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });


                            map1.setOptions({minZoom: 2, maxZoom: 15});

                            var marker1;


                            <?php
                            if($user_info != ''){
                            foreach ($user_info as $ui):{
                            foreach ($ui as $i){?>
                            marker1 = new google.maps.Marker({
                                position: new google.maps.LatLng(<?php  echo $i['latitude']; ?>,<?php echo $i['longitude']; ?>),
                                map: map1
                            });

                            var contentString2 = "<?php echo $user_name = $i['user_firstname'] . " " . $i['user_lastname']; ;?>";
                            var infowindow1;

                            infowindow1 = new google.maps.InfoWindow({
                                content: contentString2,

                            });

                            infowindow1.open(map, marker1);

                            <?php }} endforeach; ?>
                            <?php
                            }
                            }

                            ?>

                        </script>
                    </div>

                </div>
            </div>
        </div>

        <!------Traveller list---------->

        <div class="col-sm-12">

            <div class="profileBox">
                <div class="col-sm-1" id="boxHeader">
                    <span> Travellers </span>

                </div>
                <?php


                if($var==1){


                if($user_info!='' ){?>



                    <?php

                foreach ($user_info as $ui){
                foreach ($ui as $i){?>


                    <div class="row">

                        <div class="col-sm-12 col-xs-12" id="boxContent" >

                            <div class="panel panel-default">

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-sm-2" id="profilePic">

                                            <div id="feedbackImage">

                                            </div>
                                        </div>
                                        <div class="col-sm-10 ">
                                            <div class="row">
                                                <span style="font-weight: bold">

                                                    <div class="col-sm-3">

                                                            <a href="userPage.php?user_id=<?php echo $i['user_id'];?>"><b style="color:blue; "><?php echo $i['user_firstname'] ." ".$i['user_lastname']  ; ?></b></a>

                                                    </div>

                                                </span>
                                            </div>


                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?php echo $i['summary']; ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php   } } }
                }
                else if($var==2){  ?>
                    <script>
                        var map2 = new google.maps.Map(document.getElementById('map'), {
                            zoom: 2,

                            center: new google.maps.LatLng(18.496257 ,73.836944),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        map2.setOptions({ minZoom: 2, maxZoom: 15 });



                    </script>
                <?php  }
                ?>

            </div>
        </div>


    </div>



</div>

<div class="container">

    <?php include "footer.php"?>

</div>

</body>
</html>