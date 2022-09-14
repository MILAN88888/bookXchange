<?php
require '../Include/common.php';
use Bookxchange\Bookxchange\Controller\User;
$user = new User();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $phone = $_POST['phone_no'];
        $pass = $_POST['pass'];
        $user->getLogin($phone, $pass);
    }
    if (isset($_POST['register'])) {
        $user_image = 'user image';
        $user_name = $_POST['user_name'];
        $user_mobile = $_POST['user_mobile_no'];
        $user_address = $_POST['address'];
        $user_email = $_POST['user_email'];
        $user_pass = $_POST['user_pass'];
        $user->getRegister($user_image, $user_name, $user_mobile, $user_address, $user_email, $user_pass);
    }
    if (isset($_POST['forget'])) {
        $mobile_no = $_POST['mobile_no'];
        $user->getForgetPass($mobile_no);
    }
   
}

?>