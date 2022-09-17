<?php
require 'vendor/autoload.php';
require 'src/Include/baseurl.php';
$home = new \Bookxchange\Bookxchange\Controller\Home($baseurl);
session_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header('location:src/View/dashboard.php');
} else {
    echo $home->getHome();
    if (isset($_SESSION['fail']) && $_SESSION['fail'] != '') {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        '.$_SESSION['fail'].'
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>';
        unset($_SESSION['fail']);
    }
    if (isset($_SESSION['msg'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        '.$_SESSION['msg'].'
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>';
        unset($_SESSION['msg']);
    }
}
?>