<?php
require '../../vendor/autoload.php';
require 'baseurl.php';
use Bookxchange\Bookxchange\Controller\User;
use Bookxchange\Bookxchange\Controller\Book;
use Bookxchange\Bookxchange\Controller\Home;
use Bookxchange\Bookxchange\Controller\Dashboard;
session_start();

$book = new Book($baseurl);
$user = new User($baseurl);
$home = new Home($baseurl);
$dashboard = new Dashboard($baseurl);
?>