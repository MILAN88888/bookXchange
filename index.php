<?php
require 'vendor/autoload.php';
$home = new \Bookxchange\Bookxchange\Controller\Home();
session_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header('location:src/View/dashboard.php');
} else {
    echo $home->getHome();
    if (isset($_SESSION['fail']) && $_SESSION['fail'] != '') {
        echo $_SESSION['fail'];
        unset($_SESSION['fail']);
    }
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
}
?>