<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 2016-11-28
 * Time: 8:56 PM
 */
$questions="";
$id="";
$email="";
include ("databaseFile.php");
include ("PHPMailer/gmail.php");
$questions=TravelDatabase::getAllQuestions();
if (isset($_POST['Answer'])) {
    $id =$_POST['id'];
$email=TravelDatabase::getContactEmail($id);

    phpMailerQuestion($email['email'], $_POST['message']);
    //if the question has been answered the active should be set to 0, so it would be removed from the list
    TravelDatabase::setQuestionInactive($id);
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
</head>
<body>
<div class="container"><?php include "header.php"?>
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <h1 class="h1">
               Welcome Admin! </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <h2 class="h1">
                Your current questions </h2>
        </div>
    </div>

<?php foreach ($questions
as $questionNumber){
?>

    <form action="adminPage.php" method="post">
    <div class="col-lg-12 well">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Full Name</label>
                <label class="form-control"><?php echo $questionNumber['name']?> </label>
            </div>
            <div class="form-group">
                <label>Email</label>
                <label class="form-control"><?php echo $questionNumber['email']?> </label>
            </div>
            <div class="form-group">
            <label>Id</label>
            <label class="form-control"><?php echo $questionNumber['contact_id']?> </label>
                </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label>Question</label>
                <label class="form-control"><?php echo $questionNumber['question']?> </label>
            </div>
        </div>    </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">
                        Your Answer</label>
                    <textarea name="message" name="message" class="form-control" rows="9" cols="25"
                              placeholder="Answer the user's question"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <input type="hidden" name="id" value="<?php echo $questionNumber['contact_id']?>">
            <input type="submit" class="btn btn-primary pull-right" name="Answer"  value="Answer Question">
            </div>

        </div>


    </form>

    <?php } ?>

<?php include "footer.php"?>
</div>
</body>