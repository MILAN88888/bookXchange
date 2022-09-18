<?php
/**
 * Action class handle  action type.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
require '../Include/allcontrollerobj.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $phone = $_POST['phone_no'];
        $pass = $_POST['pass'];
        $user->getLogin($phone, $pass);
    }
    if (isset($_POST['register'])) {
        $newUserImage = 'user image';
        if (isset($_FILES['user_img'])) {
            $userImage = $_FILES['user_img']['name'];
            $userImageTemp = $_FILES['user_img']['tmp_name'];
            $imgType = strtolower(pathinfo($userImage, PATHINFO_EXTENSION));
            $randomno = rand(0, 100000);
            $generateName = 'user'.date('Ymd').$randomno;
            $generateUserImage = $generateName.'.'.$imgType;
            $desImage='../Upload/Users/'.$generateUserImage;
            move_uploaded_file($userImageTemp, $desImage);
            $newUserImage = $generateUserImage;
        }
        
        $userName = $_POST['user_name'];
        $userMobile = $_POST['user_mobile_no'];
        $userAddress = $_POST['address'];
        $userEmail = $_POST['user_email'];
        $userPass = $_POST['user_pass'];
        $user->getRegister(
            $newUserImage, $userName, $userMobile,
            $userAddress, $userEmail, $userPass
        );
    }
    if (isset($_POST['forget'])) {
        $mobileNo = $_POST['mobile_no'];
        $user->getForgetPass($mobileNo);
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
if (isset($_GET['type']) && $_GET['type'] == 'bookfeedback') {
    $bookId = $_POST['book_id'];
    $book->bookFeedback($bookId);
}
if (isset($_GET['type']) && $_GET['type'] == 'insertfeedback') {
    $bookId = $_POST['bookid'];
    $feedback = $_POST['feedback'];
    $userid = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
    $book->insertBookFeedback($bookId, $feedback, $userid, $userName);
}
?>