<?php
require '../../vendor/autoload.php';
use Bookxchange\Bookxchange\Controller\User;
$user = new User();
session_start();
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_POST['newpass'])) {
    $newPass = $_POST['pass'];
    $updateRes = $user->updatePassword($newPass, $_SESSION['mobile']);
    if ($updateRes == true) {
        $_SESSION['msg'] = "Passwrod update succefully";
        header('location:../../index.php');
    } else {
        $_SESSION['msg'] = "Password Update Failed";
        header('location:../../index.php');
    }
}
?>
<form action="reset.php" method="post">
   <input type="password" name="pass" placeholder="Enter new password" required >
    <input type="submit" name="newpass" value="reset">
</form>