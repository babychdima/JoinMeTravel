<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 21-Nov-16
 * Time: 12:26 AM
 */

require_once "databaseFile.php";
require_once "Validation.php";
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
$status=-1;
$errorMessage=0;
$feedback="";
$feedbacks="";

$count=10;
if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}



//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

$getUserId = TravelDatabase::getUserId();

//check if feedback button was clicked
if (isset($_GET['btnSubmit'])){
    $user_id = $_GET['user_id'];
if (!Validation::isRequired($_GET['feedback'])){
$status=0;
    $errorMessage="Please enter your feedback";
}
else {
    $feedback=$_GET['feedback'];
    $status=1;
    $errorMessage="Thank you for your feedback";
    TravelDatabase::insertFeedbackToUser($_SESSION['joinMeTravel'], $user_id, $feedback);
    header("Location:userPage.php?user_id=$user_id");
}
}
else {
    $user_id = $_GET['user_id'];
}

//get all feedbacks for this user
$feedbacks=TravelDatabase::getAllFeedbacksForUser( $user_id);
if (count($feedbacks)>10){
    $count=5;
}
else {
    $count=count($feedbacks)/2;
}

foreach ($getUserId as $uId){
    if($user_id == $uId['user_id']){
        $getUserById = TravelDatabase::getUserInfoById($user_id);

        foreach ($getUserById as $userInfo) {
            $userId = $userInfo['user_id'];
            $user_name = $userInfo['user_firstname'];
            $occupation = $userInfo['occupation'];
            $dob = $userInfo['date_of_birth'];
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
    <!--Profile content-->
    <div class="site-container">

        <div class="profileBox">
            <form method="post" action="userMessage.php">
            <div class = "row">
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span> Profile Information</span>
                    <a href="userMessage.php?user_id=<?php echo $userId;?>" id="editbtn"><button type="button" class="btn btn-primary btn-md">Contact <?php echo $user_name?></button></a>

                </div>
            </form>
            </div>
            <div class="row">

                <div class="col-sm-3 col-xs-3" id="profilePic">
                    <div id="profileImage">
                        <img <?php echo $displayImage?> style="height:100%;width:100%" />
                        <?php echo $noProfileImageErr?>
                    </div>



                </div>
                <div class="col-sm-9 col-xs-9">
                    <div class="col-sm-12 col-xs-12" id="profileDetails">

                        <div class="row">
                            <div class="col-sm-3 col-xs-3" id="profileDetails1">Full name:</div>
                            <div class="col-sm-9 col-xs-9"><?php echo $user_name;?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-3" id="profileDetails1">Occupation:</div>
                            <div class="col-sm-9 col-xs-9"><?php echo $occupation;?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-3" id="profileDetails1">Date of birth:</div>
                            <div class="col-sm-9 col-xs-9"><?php echo $dob;?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-3" id="profileDetails1">Description:</div>
                            <div class="col-sm-9 col-xs-9">On the next trip of mine I would surely love to go to Blue Mountain next month, anybody
                                up for it?</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-xs-3" id="profileDetails1">Address:</div>
                            <div class="col-sm-9 col-xs-9"><?php echo $address;?></div>
                        </div>

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
                <div class="col-sm-12 form-group">
                    <form action="userPage.php" method="get">
                        <label>Have something to say? Leave your feedback and help other travellers</label>
                        <textarea name="feedback" placeholder="Leave your feedback here" rows="3" class="form-control"></textarea>
                </div>
                <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']?>">
                <input type="submit"  class="btn btn-primary btn-md" value="Leave feedback" name="btnSubmit">
                </form>

                <?php
                for ($i = 0; $i < $count; ++$i) {
$item=$feedbacks[$i];
?>



                  <?php
                    $userInfo = TravelDatabase::getUserInfoById($item['from_user_id']);
foreach ($userInfo as $user){
                    ?>
    <?php

    $image_dir = 'image/'.$user['user_id'].'/profileImages';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

//get image name from user_table
    $imageName = TravelDatabase::getImage($user['user_id']);

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

    ?>
                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">


                               <div class="row">

                              <div class="col-sm-2 col-xs-2" >

                                    <div id="feedbackImage">
                                        <img <?php echo $displayImage ?> style="height:100%;width:100%" />
                                    </div>
                                </div>
                                <div class="col-sm-10 col-xs-10">
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        <span style="font-weight: bold"><?php echo $user['user_firstname'] ?></span>
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        <?php echo $user['country'] ?>
                                    </div>
                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                        <?php echo $item['feedback'] ?>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            <?php } }?>

                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Show all
                </button>
            <div class="collapse" id="collapseExample">
                <?php
                for ($i=$count+1; $i < count($feedbacks); ++$i) {
                    $item=$feedbacks[$i];

                    $userInfo = TravelDatabase::getUserInfoById($item['from_user_id']);
                    foreach ($userInfo as $user){
                        ?>
                        <?php

                        $image_dir = 'image/'.$user['user_id'].'/profileImages';
                        $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

//get image name from user_table
                        $imageName = TravelDatabase::getImage($user['user_id']);

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

                        ?>
                        <div class="col-sm-12 col-xs-12" id="boxContent" >
                            <div class="panel panel-default">
                                <div class="panel-body">


                                    <div class="row">
                                        <div class="col-sm-2 col-xs-2" >
                                            <div id="feedbackImage">
                                                <img <?php echo $displayImage ?> style="height:100%;width:100%" />
                                            </div>
                                        </div>
                                        <div class="col-sm-10 col-xs-10">
                                            <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                                <span style="font-weight: bold"><?php echo $user['user_firstname'] ?></span>
                                            </div>
                                            <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                                <?php echo $user['country'] ?>
                                            </div>
                                            <div class="col-sm-12 col-xs-12" id="feedbackDetails">
                                                <?php echo $item['feedback'] ?>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php } }?>
                </div>
            </div>
<!---->
<!--            <div class="row">-->
<!---->
<!---->
<!--                <div class="col-sm-12 col-xs-12" id="boxContent" >-->
<!--                    <div class="panel panel-default">-->
<!--                        <div class="panel-body">-->
<!---->
<!--                            <div class="row">-->
<!--                                <div class="col-sm-2 col-xs-2" >-->
<!--                                    <div id="feedbackImage">-->
<!--                                        <img src="image/rachel2.jpg" style="height:100%;width:100%" />-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="col-sm-10 col-xs-10">-->
<!--                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">-->
<!--                                        <span style="font-weight: bold">Rachel Green</span>-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">-->
<!--                                        Company Executive-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-12 col-xs-12" id="feedbackDetails">-->
<!--                                        Phoebe is a fun and such a great combination of fun, creativity and planning. She is a lovely-->
<!--                                        person and I have been on several trips with her and I surely enjoy her company everytime.-->
<!--                                    </div>-->
<!---->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->


            </div>

        </div>

    </div>

    <?php include "footer.php"?>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js'></script>

<script src="js/slider.js"></script>
<script>
    function showMore(){
     <?php $count+=2;
        $flag=true;?>
    }
</script>
</body>
</html>

