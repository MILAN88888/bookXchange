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
            if ($_FILES['user_img']['size']>0) {
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
        }

        $userName = $_POST['user_name'];
        $userMobile = $_POST['user_mobile_no'];
        $userAddress = $_POST['address'];
        $userEmail = $_POST['user_email'];
        $userPass = $_POST['user_pass'];
        $user->getRegister(
            $newUserImage,
            $userName,
            $userMobile,
            $userAddress,
            $userEmail,
            $userPass
        );
    }
    if (isset($_POST['forget'])) {
        $mobileNo = $_POST['mobile_no'];
        $user->getForgetPass($mobileNo);
    }
    if (isset($_POST['updateprofile'])) {
        $oldImage = $_POST['old_image'];

        $oldNumber = $_POST['old_number'];
        $oldEmail = $_POST['old_email'];
        $newUserImage = $oldImage;
        if (isset($_FILES['newimage'])) {
            if ($_FILES['newimage']['size']>0) {
                $newUserImg = $_FILES['newimage']['name'];
                $imgType = strtolower(pathinfo($newUserImg, PATHINFO_EXTENSION));
                $randomno = rand(0, 100000);
                $generateName = 'user'.date('Ymd').$randomno;
                $generateUserImage = $generateName.'.'.$imgType;
                $desImage='../Upload/Users/'.$generateUserImage;
                move_uploaded_file($_FILES['newimage']['tmp_name'], $desImage);
                $newUserImage = $generateUserImage;
            }
        }
        $newUserName = $_POST['user_name'];
        $newUserNumber = $_POST['user_phone'];
        $newUserAddress = $_POST['user_address'];
        $newUserEmail = $_POST['user_email'];
        $ok = 1;
        if ($newUserNumber == $oldNumber) {
            $newUserNumber = $oldNumber;
        } else {
            $isNumberExit = $user->isNumberExit($newUserNumber);

            if ($isNumberExit == 1) {
                $_SESSION['msg'] = "Number is aleady exit";
                $ok = 0;
            }
        }
        if ($newUserEmail == $oldEmail) {
            $newUserEmail = $oldEmail;
        } else {
            $isEmailExit = $user->isEmailExit($newUserEmail);
            if ($isEmailExit == 1) {
                $_SESSION['msg'] = "Email is aleady exit";
                $ok = 0;
            }
        }
        if ($ok == 1) {
            $isProfileUpdate = $user->updateProfile(
                $newUserImage,
                $newUserName,
                $newUserNumber,
                $newUserAddress,
                $newUserEmail,
                $_SESSION['user_id']
            );
            if ($isProfileUpdate == true) {
                $_SESSION['msg'] = "Updated successfully!";
                header('location:profile.php');
            } else {
                $_SESSION['msg'] = "No Update";
                header('location:profile.php');
            }
        } else {
            header('location:profile.php');
        }
    }
    if (isset($_POST['requestgrand'])) {
        $requesterId = $_POST['requesterid'];
        $bookId = $_POST['bookid'];
        $ownerId = $_POST['ownerid'];
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $book->updateRequest($requesterId, $bookId, $ownerId, $status, $reason);
    }

    if (isset($_POST['userrating'])) {
        $requesterId = $_POST['requesterid'];
        $rating = $_POST['requester_rating'];
        $bookId = $_POST['bookid'];
        $ownerId = $_POST['ownerid'];
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $user->updateUserRating($rating, $requesterId);
        $book->updateRequest($requesterId, $bookId, $ownerId, $status, $reason);
    }

    if (isset($_POST['reset_new_password'])) {
        $userId = $_SESSION['user_id'];
        $oldPass = $_POST['old_user_password'];
        $newPass = $_POST['new_user_password'];
        $user->resetNewPassword($userId, $oldPass, $newPass);
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
    $book->bookFeedback($bookId, $_SESSION['user_id']);
}
if (isset($_GET['type']) && $_GET['type'] == 'insertfeedback') {
    $bookId = $_POST['bookid'];
    $feedback = $_POST['feedback'];
    $userid = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
    $book->insertBookFeedback($bookId, $feedback, $userid, $userName);
}
if (isset($_GET['type']) && $_GET['type'] == 'bookrequest') {
    $bookId = $_POST['bookid'];
    $ownerId = $_POST['ownerid'];
    $requesterId = $_SESSION['user_id'];
    $book->bookRequest($bookId, $ownerId, $requesterId);
}
if (isset($_GET['type']) && $_GET['type'] == 'bookreturnrequest') {
    $bookId = $_POST['bookid'];
    $ownerId = $_POST['ownerid'];
    $bookRating = $_POST['bookrating'];
    $requesterId = $_SESSION['user_id'];
    $book->bookReturnRequest($bookId, $ownerId, $requesterId, $bookRating);
}

if (isset($_GET['type']) && $_GET['type'] == 'search') {
    $bookData = $_POST['bookdata'];
    $book->bookSearch($bookData);
}
