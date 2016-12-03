<?php require('databaseFile.php');
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
$user_first_name='';
$emptyNameErr = '';
$noImagesErr = '';
$imageNameResult = '';

if (isset($_POST['fPasswordSubmit']) ){

    include "PHPMailer/gmail.php";

    $email = TravelDatabase::getLogin();

    $login = $_POST['email'];

    if(empty($login)){

        $emptyLogin=true;
        header("Location: mainMainPage.php?action=login&result=1");
    }else{

        foreach ($email as $e){

            if ($e['email'] != $login){

                if ($e === end($email)){

                    $regEmail=true;
                    header("Location: mainMainPage.php?action=login&result=2");
                }
            }
            elseif($e['email'] == $login){

                $password = TravelDatabase::getPassword($e['email']);

                foreach ($password as $p){

//                    phpMailer($login, $p['user_password']);

                    $sentEmail = true;
                    header("Location: mainMainPage.php?action=login&result=3");

                    break 2;
                }
            }
        }
    }
}
else if (isset($_POST['btnSubmit']) ) {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $valResult = TravelDatabase::checkLogin($email, $password);


        if ($valResult > 0) {

            $userId = TravelDatabase::getUserIdByEmail($email);

            foreach ($userId as $uId){
                $uid=$uId['user_id'];
            }

            //creating session when email and password are correct
            $_SESSION['joinMeTravel'] = $uid;

                if (isset($_POST['rememberMe'])) {

                    $expire = new DateTime('+1 month');
                    setcookie('id', $uid, $expire->getTimestamp(), "/", "localhost", false, true);
//                    setcookie('password', $password, $expire->getTimestamp(), "/", "localhost", false, true);

                } else {
                    $expire = new DateTime('-1 month');
                    setcookie('id', '', $expire->getTimestamp(), "/", "localhost", false, true);
//                    setcookie('password', '', $expire->getTimestamp(), "/", "localhost", false, true);

                }

        }else{
            //transferring to the main page if email / password is not correct
            header("Location:mainMainPage.php?action=login&result=5");
        }
    }

//create session after restarting browser
}else if(isset($_COOKIE['id']) && empty($_SESSION['joinMeTravel'])){
    $_SESSION['joinMeTravel'] = $_COOKIE['id'];
}
//transferring to the main page if session is not set
else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}
//getting all user info from user_table
$userInfo = TravelDatabase::getUserInfo($_SESSION['joinMeTravel']);
$userDescription = TravelDatabase::getUserDescriptionById($_SESSION['joinMeTravel']);
foreach ($userInfo as $ui){
    $user_id=$ui['user_id'];
}

if (!file_exists('image/'.$user_id.'/profileImages')) {
    mkdir('image/'.$user_id.'/profileImages', 0777, true);
}

//image uploader
$galleryDir='image/'.$user_id;
$image_dir = 'image/'.$user_id.'/profileImages';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;
if (isset($_POST['imgUpload'])) {
    if (isset($_FILES['file1'])) {

        $filename = $_FILES['file1']['name'];
        if (empty($filename)) {
            $emptyNameErr = "Select image";
        }else{

            //Upload image name to user_table
            TravelDatabase::updateImageName($_SESSION['joinMeTravel'], $filename);

            $source = $_FILES['file1']['tmp_name'];
            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
//        process_image($image_dir_path, $filename);
        }

    }
}

//get image name from user_table
$imageName = TravelDatabase::getImage($_SESSION['joinMeTravel']);

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


$galleryImageName = TravelDatabase::getGalleryImage($_SESSION['joinMeTravel']);
if(empty($galleryImageName)){
    $noImagesErr = "No images to display yet...";
}

foreach ($userDescription as $ud){
    $user_city = $ud['destinationCity'];
    $user_country = $ud['destinationCountry'];
    $user_desc = $ud['description'];
    $user_stDate = $ud['startDate'];
    $user_endDate = $ud['endDate'];

}

foreach ($userInfo as $ui) {

    $user_first_name = $ui['user_firstname'];
    $user_last_name = $ui['user_lastname'];
    $occupation = $ui['occupation'];
    $dob = $ui['date_of_birth'];
    $address = $ui['address'];
    $summary = $ui['summary'];

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

    <style>

    </style>
</head>
<body id="profileBody">

<div class="container">

    <?php include "header.php"?>

<!--Profile content-->
    <div class="site-container">
         <div class="profileBox">
             <form method="post" action="profileMessage.php">
                 <div class = "row">
                     <div class="col-sm-12 col-xs-12" id="boxHeader">
                         <span> Profile Information</span>
                         <a href="profileEditor.php" ><span class="label label-default" id="editbtn">edit</span></a><br>
                         <button type="submit" name="btnMessage" class="btn btn-primary btn-md" id="editbtn">Messages</button>
                     </div>
                 </div>
             </form>

             <div class="row">

                 <div class="col-sm-3 col-xs-3" id="profilePic">
                    <div id="profileImage">
                        <img <?php echo $displayImage?> style="height:100%;width:100%" />
                        <?php echo $noProfileImageErr?>
                    </div>
                     <div>
                        <form id="upload_form"
                              action="profilePage.php" method="POST"
                              enctype="multipart/form-data"><br>
                            <input type="hidden" name="action" value="upload"/>
                            <div class="input-group">
                                <input id="BSbtninfo" type="file" name="file1"/>
                                <span class="input-group-btn"></span>
                                <input class="btn btn-warning btn-xs" id="upload_button" name="imgUpload" type="submit" value="Upload"/><br>
                            </div>
                            <?php echo $emptyNameErr?>
                        </form>
                         <script>
                             $('#BSbtninfo').filestyle({
                                 size: 'xs',
                                 input: false,
                                 buttonName : 'btn-default',
                                 buttonText : 'Select Image'
                             });

                         </script>
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
                    <a href="profileEditor.php" ><span class="label label-default" id="editbtn">edit</span></a>
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

                <a href="imageEditor.php" ><span class="label label-default" id="editbtn">edit</span></a>

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

