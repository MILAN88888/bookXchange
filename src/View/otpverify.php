<?php
session_start();
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
echo $_SESSION['otp'];
if (isset($_POST['otpsubmit'])) {
    $otp = $_POST['otp'];
    if (isset($_SESSION['otp']) && isset($_SESSION['otpExpire'])) {
        if( time() <= $_SESSION['otpExpire']) {
            if ($otp == $_SESSION['otp']) {
                $_SESSION['msg'] = "otp veried";
                header('location:reset.php');
            } else {
                $_SESSION['msg'] = "otp not matched";
                header('location:otpverify.php');
            }
        } else {
            $_SESSION['msg'] = 'Time Expired..';
            header('location:../../index.php');
        }
    }
}
?>
<form action="otpverify.php" method="post">
<input type="tesxt" name="otp" placeholder="enter 4 digit otp" required> 
<input type="submit" name="otpsubmit" value="submit otp"> 
</form>

