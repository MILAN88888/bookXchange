<?php

require '../Include/allcontrollerobj.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $phone = $_POST['phone_no'];
        $pass = $_POST['pass'];
        $user->getLogin($phone, $pass);
    }
    if (isset($_POST['register'])) {
        $user_image = 'user image';
        $user_name = $_POST['user_name'];
        $user_mobile = $_POST['user_mobile_no'];
        $user_address = $_POST['address'];
        $user_email = $_POST['user_email'];
        $user_pass = $_POST['user_pass'];
        $user->getRegister($user_image, $user_name, $user_mobile, $user_address, $user_email, $user_pass);
    }
    if (isset($_POST['forget'])) {
        $mobile_no = $_POST['mobile_no'];
        $user->getForgetPass($mobile_no);
    }
}
if (isset($_GET['type']) && $_GET['type'] == 'bookedit') {
    $bookId =  $_POST['id'];
    $book->getBookDetails($bookId);
}
if (isset($_GET['type']) && $_GET['type'] == 'bookupdate') {
    $old_image = $_POST['old_image'];
    $newBookImage = $old_image;
    if (isset($_FILES['image'])) {
        $newBookImg = $_FILES['image']['name'][0];
        $imgType = strtolower(pathinfo($newBookImg, PATHINFO_EXTENSION));
        $randomno = rand(0, 100000);
        $generateName = 'book'.date('Ymd').$randomno;
        $generateBookImage = $generateName.'.'.$imgType;
        $desImage='../Upload/Books/'.$generateBookImage;
        move_uploaded_file($_FILES['image']['tmp_name'][0], $desImage);
        $newBookImage = $generateBookImage;
    }
    $bookName = $_POST['book_name'];
    $bookId = $_POST['book_id'];
    $bookGenre = $_POST['book_genre'];
    $bookAuthor = $_POST['book_author'];
    $bookEdition = $_POST['book_edition'];
    $bookDes = $_POST['book_des'];
    $bookRating = $_POST['book_rating'];
    $ownerId = $_SESSION['user_id'];
    $book->updateBook(
        $bookId,
        $newBookImage,
        $bookName,
        $bookGenre,
        $bookAuthor,
        $bookEdition,
        $bookDes,
        $bookRating,
        $ownerId
    );
}
if (isset($_GET['type']) && $_GET['type'] == 'bookdelete') {
    $bookId = $_POST['book_id'];
    $book->deletePersonalBook($bookId, $_SESSION['user_id']);
}
