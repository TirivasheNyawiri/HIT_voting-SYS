<?php
session_start();
include 'connect.php';
include('smtp/PHPMailerAutoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';


function smtp_mailer($to,$subject, $msg){
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    //$mail->SMTPDebug = 2; 
    $mail->Username = "h220290g@hit.ac.zw"; // Sender's Email
    $mail->Password = "juls cpkr zirp zjgu"; //Sender's Email App Password
    $mail->SetFrom("h220290g@hit.ac.zw"); // Sender's Email
    $mail->Subject = $subject;
    $mail->Body =$msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
    ));
    if(!$mail->Send()){
        echo $mail->ErrorInfo;
    }else{
        echo "we have sent 6 digit otp code to your email :".$to;
    }
}


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['next'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email']=$email;

    $sql = "SELECT * FROM users WHERE email='$email'";
    $query = mysqli_query($con, $sql);
    $data = mysqli_fetch_array($query);

    if ($data && password_verify($password, $data['password'])) {

        $otp=rand(100000,999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('now +15 minutes'));

        $receiverEmail=$email ;
        $subject="Email Verification";
        $emailbody= "Your 6 digit code :";
        echo smtp_mailer($receiverEmail,$subject, $emailbody.$otp);

        $stmt = $con->prepare("UPDATE users SET otp_expiry=? , otp=? WHERE id=?");
        $stmt->bind_param("sis",  $otp_expiry,$otp, $data['id']);
        $stmt->execute();


        $_SESSION['temp_user'] = ['id' => $data['id'], 'otp' => $otp];
        header("Location: otp_verification.php");
        exit();
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            margin-left: 400px;
            border: 1px solid black;
            width: 440px;
            padding: 20px;
            margin-top: 40px;
        }
        input[type=text],input[type=password]{
            width: 300px;
            height: 20px;
            padding: 10px;
        }
        label{
            font-size: 20px;
            font-weight: bold;
        }
        form{
            margin-left: 50px;
        }
        a{
            text-decoration: none;
            font-weight: bold;
            font-size: 21px;
            color: blue;
        }
        a:hover{
            cursor: pointer;
            color: purple;
        }
        input[type=submit]{
            width: 70px;
            background-color: blue;
            border: 1px solid blue;
            color: white;
            font-weight: bold;
            padding: 7px;
            margin-left: 130px;
        }
        input[type=submit]:hover{
            background-color: purple;
            cursor: pointer;
            border: 1px solid purple;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="startvoting.php">
            <label for="email">Email</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br><br>
            <input type="submit" name="next" value="next"><br><br>
            <label>Did you forgot your password? </label><a href="reset_password.php">Reset Password</a>

        </form>
    </div>

</body>
</html>


