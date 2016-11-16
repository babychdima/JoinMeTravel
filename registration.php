<?php
/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 2016-11-03
 * Time: 2:46 PM
 */
include ("Validation.php");
include ("databaseFile.php");

$status=-1;
$errorMessage;
$firstName="";
$lastName="";
$password="";
$email="";
$address="";
$city="";
$country="";
$occupation="";
$description="";
$destCountry="";
$destCity="";
$startDate="";
$endDate="";

if (isset($_POST['btnSubmit'])){
    //validation

    //firstName
    if (!Validation::isRequired($_POST['firstName'])) {
        $status = 0;
        $errorMessage = "Enter your first name";
    }
    else if (!Validation::isString($_POST['firstName'])) {
        $status = 0;
        $errorMessage = "First name must contain only letters";

    }

    //lastname
    else if (!Validation::isRequired($_POST['lastName'])) {
        $status = 0;
        $errorMessage = "Enter your last name";
    }
    else if (!Validation::isString($_POST['lastName'])) {
        $status = 0;
        $errorMessage = "Last name must contain only letters";

    }

    //phone
    else if (!Validation::isRequired($_POST['phone'])) {
        $status = 0;
        $errorMessage = "Enter your phone number";
    }
    else if (!Validation::validNumber($_POST['phone'])) {
        $status = 0;
        $errorMessage = "Phone number must contain only digits";
    }


    //password
    else if (!Validation::isRequired($_POST['pass'])) {
        $status = 0;
        $errorMessage = "Enter your password";
    }
    else if (!Validation::isRequired($_POST['cpass'])) {
        $status = 0;
        $errorMessage = "Confirm your password";
    }
    else if (!Validation::validPassword($_POST['pass'])) {
        $status = 0;
        $errorMessage = "Password must contain at least 8 characters";
    }

    else if ($_POST['pass']!=$_POST['cpass']) {
        $status = 0;
        $errorMessage = "Password should match";

    }


    //email
    else if (!Validation::isRequired($_POST['email'])) {
        $status = 0;
        $errorMessage = "Enter your email";
    }
    else if (!Validation::validEmail($_POST['email'])) {
        $status = 0;
        $errorMessage = "Enter the valid email";

    }

    //address
    else if (!Validation::isRequired($_POST['address'])) {
        $status = 0;
        $errorMessage = "Enter your address";
    }


    //check if the address exists
    else if (!Validation::validAddress($_POST['address'])) {
        $status = 0;
        $errorMessage = "Address does not exist";

    }
    else if (!Validation::isRequired($_POST['city'])) {
        $status = 0;
        $errorMessage = "Enter your city";

    }
    else if (!Validation::isRequired($_POST['country'])) {
        $status = 0;
        $errorMessage = "Enter your country";

    }

    //occupation
    else if (!Validation::isRequired($_POST['occupation'])) {
        $status = 0;
        $errorMessage = "Enter your occupation";

    }

    //description
    else if (!Validation::isRequired($_POST['description'])) {
        $status = 0;
        $errorMessage = "Enter your description";
        $description="";
    }
    else if (!Validation::isRequired($_POST['destCountry'])) {
        $status = 0;
        $errorMessage = "Enter the country you want to travel to";

    }
    else if (!Validation::isRequired($_POST['destCity'])) {
        $status = 0;
        $errorMessage = "Enter the city you want to travel to";

    }
    else if (!Validation::isRequired($_POST['startDate'])) {
        $status = 0;
        $errorMessage = "Enter where you want to start your journey";

    }
    else if (!Validation::isRequired($_POST['endDate'])) {
        $status = 0;
        $errorMessage = "Enter where you want to finish your journey";

    }
    else {
        $status = 1;
        $firstName = $_POST['firstName'];
        $lastName=$_POST['lastName'];
        $email=$_POST['email'];
        $password=$_POST['pass'];
        $occupation=$_POST['occupation'];
        $address=$_POST['address'];
        $city=$_POST['city'];
        $country=$_POST['country'];
        $description=$_POST['description'];
        $destCountry=$_POST['destCountry'];
        $destCity=$_POST['destCity'];
        $startDate=$_POST['startDate'];
        $sDate=date('Y-m-d',strtotime($startDate));
        $endDate=$_POST['endDate'];
        $eDate=date('Y-m-d',strtotime($endDate));
        //inserting a new user into user_table
        TravelDatabase::insertUser($firstName, $lastName, $email, $password, $occupation, $address, $city, $country, $description, Validation::$longitude, Validation::$latitude);;
        //insert a description into a description_table
        TravelDatabase::insertDescription($email, $description, $destCountry, $destCity, $sDate, $eDate);
        $errorMessage=$sDate;


    }
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/profileStyle.css" rel="stylesheet">
    <style>
        .header h3 {
            padding-bottom: 19px;
            margin-top: 0;
            margin-bottom: 0;
            line-height: 40px;
        }

        /* Custom page footer */
        .footer {
            padding-top: 19px;
            color: #777;
            border-top: 1px solid #e5e5e5;
        }

        /* Customize container */
        /*@media (min-width: 768px) {*/
            /*.registrationCont {*/
                /*max-width: 730px;*/
            /*}*/
        /*}*/
        /*.container-narrow > hr {*/
            /*margin: 30px 0;*/
        /*}*/

        /* Main marketing message and sign up button */
        .jumbotron {
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .jumbotron .btn {
            padding: 14px 24px;
            font-size: 21px;
        }

        /* Supporting marketing content */
        .marketing {
            margin: 40px 0;
        }
        .marketing p + h4 {
            margin-top: 28px;
        }

        /* Responsive: Portrait tablets and up */
        /*@media screen and (min-width: 768px) {*/
            /*!* Remove the padding we set earlier *!*/
            /*.header,*/
            /*.marketing,*/
            /*.footer {*/
                /*padding-right: 0;*/
                /*padding-left: 0;*/
            /*}*/
            /*!* Space out the masthead *!*/
            /*.header {*/
                /*margin-bottom: 30px;*/
            /*}*/
            /*!* Remove the bottom border on the jumbotron for visual effect *!*/
            /*.jumbotron {*/
                /*border-bottom: 0;*/
            /*}*/
        /*}*/
    </style>
</head>
<body>
<div class="container">

    <?php include "headerReg.php"?>

        <div class="regContainer">
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
    <h1 class="well">Registration Form</h1>
    <!--block one-->
    <div class="col-lg-12 well">
        <div class="row">
            <form method="post" name="registrationForm" action="registration.php">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>First Name</label>
                            <input type="text" name="firstName" placeholder="Enter First Name Here.." class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Last Name</label>
                            <input type="text" name="lastName" placeholder="Enter Last Name Here.." class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Password</label>
                            <input type="text" name="pass" placeholder="Enter Password Here.." class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Confirm a Password</label>
                            <input type="text" name="cpass" placeholder="Confirm your password.." class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="Enter Phone Number Here.." class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Email Address</label>
                            <input type="text" name="email" placeholder="Enter Email Address Here.." class="form-control">
                        </div>
                    </div>
                    <div class="row">

                        <!--     <div class="form-group  col-sm-6">
                                 <label >Gender</label><br>
                                 <label class="radio-inline"><input type="radio" name="gender">Male</label>
                                 <label class="radio-inline"><input type="radio" name="gender">Female</label>
                             </div>-->

                        <div class="form-group  col-sm-6">
                            <!--   <form id="upload_form"
                                     action="." method="POST"
                                     enctype="multipart/form-data">
                                   <div class="form-group col-sm-6">
                                       <label >Profile Image</label>
                                   <input type="hidden" name="action" value="upload"/>
                                   <input type="file" name="file1"/></div>

                               </form> -->
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!--block two-->
    <div class="col-lg-12 well">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" placeholder="Enter Address Here.." rows="3" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>City</label>
                        <input name="city" type="text" placeholder="Enter City Name Here.." class="form-control">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Country</label>
                        <input name="country" type="text" placeholder="Enter Country Name Here.." class="form-control">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--block three-->
    <div class="col-lg-12 well" style="margin-bottom: 5px">
        <div class="row">
            <div class="col-sm-12">

                <div class="col-sm-12 form-group">
                    <label>Occupation</label>
                    <input type="text" name="occupation" placeholder="Enter Your Occupation Here.." class="form-control">
                </div>
                <div class="col-sm-12 form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Write Something About Yourself..." rows="3" class="form-control"></textarea>
                </div>
                <div class="col-sm-12 form-group">
                    <label>Describe where you want to go?</label>
                    <textarea placeholder="Include As Much Specific Information As Possible..." rows="3" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Destination Country</label>
                        <input type="text" name="destCountry" placeholder="Enter The Country You Want To Travel To.." class="form-control">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Destination City</label>
                        <input type="text" name="destCity" placeholder="Enter The City You Want To Travel To.." class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Start Date</label>
                            <input type="text" name="startDate" class="datepicker" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>End Date</label>
                            <input type="text" name="endDate" class="datepicker" class="form-control">
                        </div>
                    </div>

                </div> <script>
                    $( ".datepicker" ).datepicker();
                </script>
                <input type="submit"  class="btn btn-primary btn-md" value="Submit" name="btnSubmit">
            </div>
            </form>
        </div>

    </div>
    </div>
    <?php include "footer.php"?>
</div>

</body>
</html>

