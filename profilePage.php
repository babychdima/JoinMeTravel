<?php require('databaseFile.php');
session_start();
$emptyLogin = false;
$regEmail = false;
$sentEmail = false;
$login = "";
$result=0;
$email = null;
$password = null;
$user_name='';

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


        $sql = "SELECT email,user_password FROM user_table WHERE email = '" . $email . "' and user_password = '" . $password . "'";
        $statement = $db->prepare($sql);
        $statement->execute();
        $signIn = $statement->fetch();
        $count = $statement->rowCount();
        $statement->closeCursor();
        if ($count > 0) {

            //creating session when email and password are correct
            $_SESSION['joinMeTravel'] = $email;


            if (isset($_POST['rememberMe'])) {


                $name = "email";
                $value = $_GET['email'];
                $name2 = "password";
                $value2 = $_GET['password'];

                if (isset($_POST['rememberMe'])) {
                    $expire = new DateTime('+1 month');
                    setcookie('email', $value, $expire->getTimestamp(), "/", "localhost", false, true);
                    setcookie('password', $value2, $expire->getTimestamp(), "/", "localhost", false, true);

                } else {
                    $expire = new DateTime('-1 month');
                    setcookie('email', '', $expire->getTimestamp(), "/", "localhost", false, true);
                    setcookie('password', '', $expire->getTimestamp(), "/", "localhost", false, true);
                }
            }

        }else{
            //transferring to the main page if email / password is not correct
            header("Location:mainMainPage.php?action=login&result=5");
        }
    }

    //transferring to the main page if session is not set
}else if(empty($_SESSION['joinMeTravel'])){

    header("Location:mainMainPage.php");

}

//getting all user info from user_table
$userInfo = TravelDatabase::getUserInfo($_SESSION['joinMeTravel']);

foreach ($userInfo as $ui) {

    $user_name = $ui['user_firstname'];
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
            <div class = "row">
                <div class="col-sm-12 col-xs-12" id="boxHeader" >
                    <span> Profile Information</span>
                    <span class="label label-default" id="editbtn">edit</span>
                </div>

            </div>
             <div class="row">

                 <div class="col-sm-3 col-xs-3" id="profilePic">
                    <div id="profileImage">
                        <img src="image/phoebe.jpg" style="height:100%;width:100%" />
                    </div>
                </div>
                <div class="col-sm-8 col-xs-8">
                    <div class="col-sm-12 col-xs-12" id="profileDetails">
                        <table>
                            <tr>
                                <td style="font-weight: bold">Full name:</td>
                                <td><?php echo $user_name;?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold">Occupation:</td>
                                <td><?php echo $occupation;?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold">Date of birth:</td>
                                <td><?php echo $dob;?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold">Description:</td>
                                <td>On the next trip of mine I would surely love to go to Blue Mountain next month, anybody
                                    up for it?</td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold">Address</td>
                                <td><?php echo $address;?></td>
                            </tr>

                       </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="profileBox">

            <div class="row" >
                <div class="col-sm-12 col-xs-12" id="boxHeader">
                    <span>Summary</span>
                    <span class="label label-default" id="editbtn">edit</span>
                </div>

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
                <span class="label label-default" id="editbtn">edit</span>

            </div>
            <div class="col-sm-12 col-xs-12" id="boxContent">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="wrapper">
                            <div class="portfolio-item-slider">
                                <div class="slick-slider-item">
                                    <img src="image/img_fjords_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/img_mountains_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/img_nature_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/purple_aurora_borealis-wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/stunning_sunset-wallpaper-1920x1080.jpg" alt="" />
                                </div>


                            </div>
                            <!-- .portfolio-item-slider -->
                            <div class="portfolio-thumb-slider">
                                <div class="slick-slider-item">
                                    <img src="image/img_fjords_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/img_mountains_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/img_nature_wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/purple_aurora_borealis-wide.jpg" alt="" />
                                </div>
                                <div class="slick-slider-item">
                                    <img src="image/stunning_sunset-wallpaper-1920x1080.jpg" alt="" />
                                </div>


                            </div>
                            <!-- .portfolio-thumb-slider -->

                            <div class="controls"></div>
                        </div>
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
                <div ></div>
                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">

                                <div class="col-sm-2 col-xs-2" id="profilePic">
                                    <div id="feedbackImage">
                                        <img src="image/ross.jpg" style="height:100%;width:100%" />
                                    </div>
                                </div>
                                <div class="col-sm-10 col-xs-10">
                                    <div class="col-sm-12 col-xs-12" id="profileDetails">
                                        <p style="font-weight: bold">Ross Gellar</p>
                                        <p>Paleontologist</p>
                                        <p>Phoebe is a very enthusiastic, fun loving and humorous lady. She likes exploring new places
                                        and I think she mostly likes adventurous trips, sometimes luxurious and definitely loves
                                        travelling in groups</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div ></div>
                <div class="col-sm-12 col-xs-12" id="boxContent" >
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">

                                <div class="col-sm-2 col-xs-2" id="profilePic">
                                    <div id="feedbackImage">
                                        <img src="image/rachel2.jpg" style="height:100%;width:100%" />
                                    </div>
                                </div>
                                <div class="col-sm-10 col-xs-10">
                                    <div class="col-sm-12 col-xs-12" id="profileDetails">
                                        <p style="font-weight: bold">Rachel Green</p>
                                        <p>Company Executive</p>
                                        <p>Phoebe is a fun and such a great combination of fun, creativity and planning. She is a lovely
                                        person and I have been on several trips with her and I surely enjoy her company everytime.
                                        </p>
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

