<?php
/**
 * Common header.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require 'allcontrollerobj.php';
if (!isset($_SESSION['user_id']) && !isset($_SESSION['token'])) {
    header('location:../../index.php');
}
echo $home->getHeader($_SESSION['user_id']);
if (isset($_SESSION['msg'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
 '.$_SESSION['msg'].'
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
    unset($_SESSION['msg']);
}
?>
