<?php
require '../Include/allcontrollerobj.php';

if ($_SESSION['token'] != null) {
$user->logout($_SESSION['user_id']);
}

?>