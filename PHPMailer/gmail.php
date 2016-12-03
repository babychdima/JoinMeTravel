<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'PHPMailerAutoload.php';

function phpMailer($email, $password){
    //Create a new PHPMailer instance
    $mail = new PHPMailer;

//Tell PHPMailer to use SMTP
    $mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    $mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';

//Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
    $mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "dbabych46@gmail.com";


//Password to use for SMTP authentication
    $mail->Password = "123456789aA";

//Set who the message is to be sent from
    $mail->setFrom('dbabych46@gmail.com', 'Dmytro Babych');

//Set an alternative reply-to address
    $mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
    $mail->addAddress($email, 'D B');

//Set the subject line
    $mail->Subject = 'Password reset';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    $mail->msgHTML("<h2>Your password is $password</h2>");

//Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

//Attach an image file
    $mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
    if (!$mail->send()) {

    } else {

    }
}


function phpMailerQuestion($email, $answer){
    //Create a new PHPMailer instance
    $mail = new PHPMailer;

//Tell PHPMailer to use SMTP
    $mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    $mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';

//Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
    $mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "dbabych46@gmail.com";


//Password to use for SMTP authentication
    $mail->Password = "123456789aA";

//Set who the message is to be sent from
    $mail->setFrom('dbabych46@gmail.com', 'JoinMeTravel');

//Set an alternative reply-to address
    $mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
    $mail->addAddress($email, 'D B');

//Set the subject line
    $mail->Subject = 'JoinMeTravel has responded';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    $mail->msgHTML("<h2>$answer</h2>");

//Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

//Attach an image file
    $mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
    if (!$mail->send()) {

    } else {

    }
}

