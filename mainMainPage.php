<?php require('databaseFile.php');
session_start();
$forgerErr = '';
$emptyLogin = false;
$regEmail = false;
$sentEmail = false;
$login = "";
$actionForm = "";
$notFound = false;
$incorrectInput=false;

//if session exists - open profilePage directly
if(!empty($_SESSION['joinMeTravel'])) {

    header("Location: profilePage.php");

}//if cookies are set - open profilePage directly
elseif (isset($_COOKIE['email'])){

    header("Location: profilePage.php");

}



if (isset($_GET['result'])){
    if ($_GET['result']==1){

        $emptyLogin=true;
    }
    else if ($_GET['result']==2){

        $regEmail=true;
    }

    else if ($_GET['result']==3){

        $sentEmail = true;
    }

    else if ($_GET['result']==4){

        $notFound = true;
    }
    else if ($_GET['result']==5){

        $incorrectInput = true;

    }
}




$action = "";
$login = false;
if (isset($_GET)||isset($_POST)){
    if (isset($_GET['action'])&&$_GET['action']='login')
    $login = true;
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
<link href="https://fonts.googleapis.com/css?family=Dosis|Kaushan+Script|Open+Sans|Pathway+Gothic+One|Roboto+Condensed|Roboto:700" rel="stylesheet">
<link href="css/cover.css" rel="stylesheet">
    <script src="build/react.js"></script>
    <script src="build/react-dom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.0-beta.1/JSXTransformer.js"></script>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>



<div class="container">

          <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"><div class="	glyphicon glyphicon-align-justify
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Join Me Travel</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


      <ul class="nav navbar-nav navbar-right">
        <li><a href="mainMainPage.php?action=login">Login</a></li>
       <li><a href="registration.php">Register</a></li>
       <li><a href="#">Contact Us</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
    <div class="main-container">
	<div class="cover-container">


<div class="row">

        <div class="col-sm-12">
          <div class="inner cover" id="imageDiv">

              <?php if (!$login){?>
            <h1 class="cover-heading" id="imageTitle">Join Me Travel</h1>
                  <p class="lead" id="imageTextTitle">Explore the world of new possibilities. Discover new places. Meet new people.</p>
            <?php }
              else {?>
    <h1 class="cover-heading" id="imageTitle">Join Me Travel</h1>



    <form method="post" name="signInForm" action="profilePage.php">
    <div class="row">
    <div class="col-sm-6 col-xs-6"  class="lead" id="imageText">
        <div class="textLogin">Email </div>
    </div>
        <div class="col-sm-6" >
            <div class="inputLogin">
                <input type="text" id="loginName" name="email">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-6" class="lead" id="imageText">
            <div class="textLogin">Password </div>

        </div>
        <div class="col-sm-6" >
            <div class="inputLogin">

                <input type="password" id="loginPassword" name="password" ></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xs-6" class="lead" id="RembMeText">

                <input type="checkbox" value="rememberMe" name="rememberMe"> remember me

        </div>
        <div class="col-sm-6">

            <div class="fPassword">
                <input type="button"  hidden="hidden" onclick="custom_alert()" id="modalBtn"  >
                <input type="submit"   value="Forgot password?" name="fPasswordSubmit" id="fPasswordBtn" >

            </div>
            <?php  if($emptyLogin){?>
                <script>
                    document.getElementById("modalBtn").click();

                    function custom_alert() {
                        $("<div title='Error'>Enter your email!</div>").dialog();
                    }

                </script>


        <?php }
        else if ($regEmail){?>
                <script>
                    document.getElementById("modalBtn").click();

                    function custom_alert() {
                        $("<div title='Error'>Email is not registered!</div>").dialog();
                    }

                </script>


            <?php }
            else if ($sentEmail){?>
            <script>
                document.getElementById("modalBtn").click();

                function custom_alert() {
                    $("<div title='Success'>Password was sent to your email</div>").dialog();
                }

            </script>


            <?php }
            else if ($notFound){?>
            <script>
                document.getElementById("modalBtn").click();

                function custom_alert() {
                    $("<div title='Error'>No user with the data entered has been found</div>").dialog();
                }

            </script>


            <?php }
            else if ($incorrectInput){?>
            <script>
                document.getElementById("modalBtn").click();

                function custom_alert() {
                    $("<div title='Error'>Incorrect email or password</div>").dialog();
                }

            </script>


            <?php }?>

            </div>



        <div class="col-sm-12 col-xs-12" >

            <div class="loginBtn">
                <input type="submit"  class="btn btn-primary btn-md" id="loginBtn" value="Log in" name="btnSubmit"/>
            </div>
        </div>

    </div>
    </form>

<?php }?>
          </div>

        </div>
        </div></div>

            <div class="row" id="mainPageIcons">
                <div class="col-sm-4">
                   <img src="image/globe.png" width="230px" height="200px">
                    <div id="optionText">
                        May your travel dreams come true
                    </div>
                </div>
                <div class="col-sm-4">
                    <img src="image/person.png" width="200px" height="200px">
                    <div id="optionText">
                        Meet new people who love traveling as much as you do
                    </div>
                </div>
                <div class="col-sm-4">
                    <img src="image/dollar.png" width="210px" height="200px">
                    <div id="optionText">
                        The traveling should not be expensive. Save your money
                    </div>
                </div>
            </div>

<div class="row" id="whyJoinMeTravel">
        	<p>Why JoinMeTravel? </p>

</div>
 <div class="row" id="whyJoin">


     <div class="col-sm-6" id="whyImage">

         <img src="image/profile.PNG" style="height:100%;width:100%;" />

     </div>
     <div class="col-sm-6" id="whyImage">
         <ul id="listWhy">
     <li class="whyList">   <span id="whyJoinText1">JoinMeTravel uses a map-based system to connect you with the travelers around the world.</span></li>
       <li class="whyList">      <span id="whyJoinText1">Create a profile and find people who share your interests.</span></li>
         <li class="whyList">  <span id="whyJoinText2">  If you like traveling - this is the site you'd want to use.</span></li>
         </ul>

     </div>

 </div>
    <div class="row">
        <div class="col-sm-12" id="counterTextContainer">
            <span id="counterText">Look, how much time you are on the Welcome page! It's time to REGISTER!</span>
    </div>
        </div>
        <div class="row">
            <div class="col-sm-12" id="counter">
                <div id="oval">  <div id="count">0</div></div>
            </div>
        </div>
    </div>
    <hr style="border-width: 3px; border-color: darkgrey;">
    <?php include "footer.php"?>
</div>

<script type="text/jsx">
    var TimerExample = React.createClass({

        getInitialState: function(){


            return { elapsed: 0 };
        },

        componentDidMount: function(){



            this.timer = setInterval(this.tick, 50);
        },

        componentWillUnmount: function(){


            clearInterval(this.timer);
        },

        tick: function(){


            this.setState({elapsed: new Date() - this.props.start});
        },

        render: function() {

            var elapsed = Math.round(this.state.elapsed / 100);


            var seconds = (elapsed / 10).toFixed(1);


            return <p>{seconds}</p>;
        }
    });


    ReactDOM.render(
        <TimerExample start={Date.now()} />,
        document.getElementById('count')
    );
</script>

	</body>
</html>