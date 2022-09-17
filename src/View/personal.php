<?php
require '../Include/common.php';
echo $book->getPersonalBook($_SESSION['user_id']);

?>