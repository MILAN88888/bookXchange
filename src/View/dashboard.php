<?php
require '../Include/common.php';
use Bookxchange\Bookxchange\Controller\Dashboard;
$dashboard = new Dashboard();
echo $dashboard->getDashboard($_SESSION['user_id']);
?>