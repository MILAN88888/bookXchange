<?php
/**
 * Add book handle add books.
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addbook'])) {
        $bookImage = $_FILES['book_image']['name'];
        $tempBookImage = $_FILES['book_image']['tmp_name'];
        // $imgSize = $_FILES['book_image']['size'];
        $imgType = strtolower(pathinfo($bookImage, PATHINFO_EXTENSION));
        // $allowedExt = array('jpg','png','jpeg','svg');

        $randomno = rand(0, 100000);
        $generateName = 'book'.date('Ymd').$randomno;
        $newBookImage = $generateName.'.'.$imgType;
        $desImage='../Upload/Books/'.$newBookImage;
        move_uploaded_file($tempBookImage, $desImage);
        $bookName = $_POST['book_name'];
        $bookGenre = $_POST['book_genre'];
        $bookAuthor = $_POST['book_author'];
        $bookEdition = $_POST['book_edition'];
        $bookDes = $_POST['book_des'];
        $bookRating = $_POST['book_rating'];
        $ownerId = $_SESSION['user_id'];
        $addNewBook = $book->addNewBook(
            $newBookImage,
            $bookName,
            $bookGenre,
            $bookAuthor,
            $bookEdition,
            $bookDes,
            $bookRating,
            $ownerId
        );
        if ($addNewBook === true) {
            $_SESSION['msg'] = "You uploaded $bookName";
            header('location:personal.php');
        } else {
           
            $_SESSION['msg'] = "No Book uploaded!";
            header('location:addbook.php');
        }
    }
}
echo $book->addBooK();
?>
