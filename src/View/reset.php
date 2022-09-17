<?php
require '../Include/allcontrollerobj.php';

echo $home->passwordReset();
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
