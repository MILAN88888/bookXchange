<?php
require '../../vendor/autoload.php';
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['token'])) {
    header('location:../../index.php');
}
?>