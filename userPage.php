<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 21-Nov-16
 * Time: 12:26 AM
 */

require_once "databaseFile.php";
require_once "imageUploader/image_util.php";
require_once "imageUploader/file_util.php";
session_start();
$emptyLogin = false;
$regEmail = false;
$sentEmail = false;
$login = "";
$result=0;
$email = null;
$password = null;
$user_name='';
$emptyNameErr = '';
$noImagesErr ='';
$imageNameResult ='';

if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}
//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

$getUserId = TravelDatabase::getUserId();

$user_id = $_GET['user_id'];

foreach ($getUserId as $uId){
    if($user_id == $uId['user_id']){
        $getUserById = TravelDatabase::getUserInfoById($user_id);
        $getDescription = TravelDatabase::getUserDescriptionById($user_id);
        foreach ($getDescription as $ud){
            $user_city = $ud['destinationCity'];
            $user_country = $ud['destinationCountry'];
            $user_desc = $ud['description'];
            $user_stDate = $ud['startDate'];
            $user_endDate = $ud['endDate'];
        }

        foreach ($getUserById as $userInfo) {


            $userId = $userInfo['user_id'];
            $user_first_name = $userInfo['user_firstname'];
            $user_last_name = $userInfo['user_lastname'];
            $occupation = $userInfo['occupation'];
            $address = $userInfo['address'];
            $summary = $userInfo['summary'];
            $userEmail = $userInfo['email'];
        }
    }
}

$galleryDir='image/'.$user_id;
$image_dir = 'image/'.$user_id.'/profileImages';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

//get image name from user_table
$imageName = TravelDatabase::getImage($user_id);

foreach($imageName as $in){
    $imageNameResult = $in['image_name'];
}

if($imageNameResult!=''){
    $displayImage ="src=".$image_dir.DIRECTORY_SEPARATOR.$imageNameResult."";
    $noProfileImageErr = '';
}else{
    $displayImage='';
    $noProfileImageErr = 'no profile image';
}


$galleryImageName = TravelDatabase::getGalleryImage($user_id);
if(empty($galleryImageName)){
    $noImagesErr = "No images to display yet...";
}


?>

<!doctype html>
<html>
<head>

    <title>
        Profile Page
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Dosis|Kaushan+Script|Open+Sans|Pathway+Gothic+One|Roboto+Condensed|Roboto:700" rel="stylesheet">

    <link rel='stylesheet prefetch' href='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.css'>
    <link rel='stylesheet prefetch' href='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick-theme.css'>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>

    <link rel="stylesheet" href="css/slider.css">
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">


</head>
<body id="profileBody">

<div class="container">

    <?php include "header.php"?>

    <!--Profile content-->
    <div class="site-container">
        <div class="profileBox">
            <form method="post" action="userMessage.php">
            <div class = "row">
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span> Profile Information</span>
                    <a href="userMessage.php?user_id=<?php echo $userId;?>" id="editbtn"><button type="button" class="btn btn-primary btn-md">Contact <?php echo $user_name?></button></a>

                </div>
            </div>
            </form>

            <div class="row">

                <div class="col-sm-3 col-xs-3" id="profilePic">
                    <div id="profileImage">
                        <img <?php echo $displayImage?> style="height:100%;width:100%" />
                        <?php echo $noProfileImageErr?>
                    </div>



                </div>
                <div class="col-sm-9 col-xs-9">
                    <div class="col-sm-12 col-xs-12" id="profileDetails">
                        <form>
                            <fieldset >
                                <legend style="font-size: 15px;">General information</legend>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Full name:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo $user_first_name;?> <?php echo $user_last_name;?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Occupation:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo $occupation;?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Your address:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo $address;?></div>
                                </div>
                            </fieldset>
                            <fieldset >
                                <legend style="font-size: 15px;">Description</legend>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Destination place:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo $user_city;?>, <?php echo $user_country;?></div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Travelling dates:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo date_format(new DateTime($user_stDate), 'jS F Y');?><br><?php echo date_format(new DateTime($user_endDate), 'jS F Y');?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3" id="profileDetails1">Description:</div>
                                    <div class="col-sm-9 col-xs-9"><?php echo $user_desc;?></div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="profileBox">

            <div class="row" >
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span>Summary</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo $summary;?>

                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="profileBox">
            <div class="row">
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span> Pictures</span>

                </div>
                <div class="col-sm-12 col-xs-12" id="boxContent">
                    <div class="panel panel-default">
                        <div class="panel-body" style="text-align: center">


                            <?php if(!empty($galleryImageName)){?>
                                <div class="wrapper" >
                                    <div class="portfolio-item-slider" >



                                        <?php foreach ($galleryImageName as $gin){ ?>
                                            <div class="slick-slider-item">
                                                <img src="<?php echo $galleryDir . DIRECTORY_SEPARATOR . $gin['gallery_image_700']?>" />
                                            </div>
                                        <?php }?>



                                    </div>
                                    <!-- .portfolio-item-slider -->
                                    <div class="portfolio-thumb-slider">
                                        <?php foreach ($galleryImageName as $gin){ ?>
                                            <div class="slick-slider-item">
                                                <img src="<?php echo $galleryDir . DIRECTORY_SEPARATOR . $gin['gallery_image_700']?>" />
                                            </div>
                                        <?php }?>

                                    </div>
                                    <!-- .portfolio-thumb-slider -->

                                    <div class="controls"></div>
                                </div>
                            <?php }else{?>
                                <?php echo $noImagesErr;}?>
                        </div>


                    </div>
                </div>

            </div>
        </div>

        <div class="profileBox">


            <div class="row">

                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span> Feedback</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-2 col-xs-2" >
                                    <div id="feedbackImage">
                                        <img src="image/ross.jpg" style="height:100%;width:100%" />
                                    </div>
                                </div>
                                <div class="col-sm-10 col-xs-10">
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        <span style="font-weight: bold">Ross Gellar</span>
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        Paleontologist
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        Phoebe is a very enthusiastic, fun loving and humorous lady. She likes exploring new places
                                        and I think she mostly likes adventurous trips, sometimes luxurious and definitely loves
                                        travelling in groups
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="row">


                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-2 col-xs-2" >
                                    <div id="feedbackImage">
                                        <img src="image/rachel2.jpg" style="height:100%;width:100%" />
                                    </div>
                                </div>
                                <div class="col-sm-10 col-xs-10">
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        <span style="font-weight: bold">Rachel Green</span>
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        Company Executive
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        Phoebe is a fun and such a great combination of fun, creativity and planning. She is a lovely
                                        person and I have been on several trips with her and I surely enjoy her company everytime.
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <?php include "footer.php"?>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js'></script>

<script src="js/slider.js"></script>
</body>
</html>

