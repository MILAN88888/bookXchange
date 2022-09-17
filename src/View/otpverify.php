<?php
require '../Include/allcontrollerobj.php';

echo $home->otpVerify();
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if (isset($_POST['otpsubmit'])) {
    $otp = $_POST['otp'];
    if (isset($_SESSION['otp']) && isset($_SESSION['otpExpire'])) {
        if( time() <= $_SESSION['otpExpire']) {
            if ($otp == $_SESSION['otp']) {
                $_SESSION['msg'] = "otp verification done!!";
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


