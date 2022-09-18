<?php
/**
 * Otp verfiy page.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require '../Include/allcontrollerobj.php';

echo $home->otpVerify();
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if (isset($_POST['otpsubmit'])) {
    $otp = $_POST['otp'];
    if (isset($_SESSION['otp']) && isset($_SESSION['otpExpire'])) {
        if (time() <= $_SESSION['otpExpire']) {
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
