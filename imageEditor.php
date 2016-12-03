<?php
/**
 * Created by PhpStorm.
 * User: Dmytro
 * Date: 13-Nov-16
 * Time: 9:18 PM
 */
require('databaseFile.php');
require_once "imageUploader/image_util.php";
require_once "imageUploader/file_util.php";
session_start();
if(empty($_SESSION['joinMeTravel'])){
    header("Location:profilePage.php");
}
$count = 0;
$err='';
$noImagesErr='';
//getting all user info from user_table
$userInfo = TravelDatabase::getUserInfo($_SESSION['joinMeTravel']);

foreach ($userInfo as $ui){
    $user_id = $ui['user_id'];
}

if (!file_exists('image/'.$user_id)) {
    mkdir('image/'.$user_id, 0777, true);
}

$image_dir = 'image/'.$user_id;
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;
//saving images to folder and database
if (isset($_POST['imgUpload'])) {
    if (isset($_FILES['file1'])) {
        //get image name
        $filename = $_FILES['file1']['name'];


        if (empty($filename)) {
            $err =  "You didn't select an image";
        }else{
            $time = time();

            $i = strrpos($filename, '.');
            $image_name = substr($filename, 0, $i);
            $ext = substr($filename, $i);
            $filenameWithTime = $image_name .'_'. $time . $ext;


            $source = $_FILES['file1']['tmp_name'];
            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filenameWithTime;

            //upload file to galleryImages folder
            move_uploaded_file($source, $target);

            // get imageName with 400px width
            $image_name_400 = process_image_400($image_dir_path, $filenameWithTime);
            $image_name_700 = process_image_700($image_dir_path, $filenameWithTime);

            //Upload original and 400px image names to user_table
            TravelDatabase::insertGalleryImages($_SESSION['joinMeTravel'], $filenameWithTime, $image_name_700, $image_name_400);
        }

    }
}

//deleting images from galleryImage folder and database
if(isset($_GET['action'])){

    $filename_400 = $_GET['filename_400'];
    $filename_700 = $_GET['filename_700'];
    $filename = $_GET['filename'];
    $target_400 = $image_dir_path . DIRECTORY_SEPARATOR . $filename_400;
    $target_700 = $image_dir_path . DIRECTORY_SEPARATOR . $filename_700;
    $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;
    if (file_exists($target)||file_exists($target_400)||file_exists($target_700)) {
        unlink($target);
        unlink($target_400);
        unlink($target_700);
        TravelDatabase::deleteGalleryImages($_SESSION['joinMeTravel'], $filename);
    }
}
$galleryImageName = TravelDatabase::getGalleryImage_400_700($_SESSION['joinMeTravel']);
if(empty($galleryImageName)){
    $noImagesErr = "No images to display yet...";
}
?>
<html>
<head>

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/cover.css" rel="stylesheet">

    <link href="css/profileStyle.css" rel="stylesheet">

    <style>
        #upload_button {
            margin-left: 7px;
        }
    </style>
</head>
<body id="profileBody">

<div class="container">
<?php include "header.php";?>
    <div class="site-container">
        <div class="profileBox">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <h2>Upload your images</h2><br>
            </div>
        </div>
            <div class="row">

                <div class="col-sm-12 col-xs-12 " id="imageUploader" >
                <form id="upload_form"
                      action="imageEditor.php" method="POST"
                      enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload"/>
                    <div class="input-group">
                        <input id="BSbtninfo" type="file" name="file1"/>
                        <span class="input-group-btn"></span>
                        <input class="btn btn-primary" id="upload_button" name="imgUpload" type="submit" value="Upload"/>
                    </div>
                    <br>
                    <?php echo $err?>
                </form>
                    <script>
                        $('#BSbtninfo').filestyle({
                            input: false,
                            buttonName : 'btn-primary',
                            buttonText : 'Select Image'
                        });
                    </script>
            </div>

            </div>
        </div>
        <div class="profileBox">
            <div class="row">
            <div class="col-sm-12 col-xs-12" id="boxHeader">
                <span>Your image gallery</span>
            </div>


            <div class="col-sm-12 col-xs-12" id="profileDetails" style="text-align: center">

                    <h4><?php echo $noImagesErr;?></h4>
                    <?php foreach ($galleryImageName as $gin){ $count++;


                        $delete_url = 'imageEditor.php?action=delete&filename_400=' .
                            urlencode($gin['gallery_image_400']).'&filename_700=' .
                            urlencode($gin['gallery_image_700']).'&filename=' .
                            urlencode($gin['gallery_image']);

                        ?>
                        <?php if($count%4==0){
                            echo "<div class='row'>";
                        }?>

                        <div class="col-sm-3 col-xs-3 thumb">

                                <a class="thumbnail"  href="#">
                                    <img class="img-responsive" src="<?php echo $image_dir.DIRECTORY_SEPARATOR.$gin['gallery_image_400']?>" />
                                </a>

                            <a href="<?php echo $delete_url;?>"><span style="color: black">Delete</span></a>
                        </div>


                        <?php if($count%4==0){
                            echo "</div>";
                        }?>
                    <?php }?>


        </div>
    </div>

</div>
        </div>


<?php include "footer.php";?>
        </div>
</body>
</html>
