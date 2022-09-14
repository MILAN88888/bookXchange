<?php
if (isset($_POST['action']) && $_POST['action'] != '') {
    $mobile_no = $_POST['mobile_no'];
    echo $mobile_no;
}
?>
