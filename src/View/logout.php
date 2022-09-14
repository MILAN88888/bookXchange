<?php
require '../Include/common.php';
use Bookxchange\Bookxchange\Controller\User;
$user = new User();
$user->logout();
?>