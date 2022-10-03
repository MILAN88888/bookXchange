<?php
/**
 * Dashboard page.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require '../Include/common.php';
    $perPage = 5;
    $start = 0;
if (isset($_GET['start'])) {
    $start = $_GET['start'];
    $currentPage = $start;
    $start--;
    $start = $start * $perPage;
}
    $record = $dashboard->getNumOfBooks();
    $pagi = ceil($record/$perPage);
    echo $dashboard->getDashboard($start, $perPage, $pagi, $_SESSION['user_id']);
?>
