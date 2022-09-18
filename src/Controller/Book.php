<?php
/**
 * Index page that controls login.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

namespace Bookxchange\Bookxchange\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Model\BookM;

/**
 * Book that controls Book section.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Book
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Constructor for the Book controller.
     *
     * @param $baseurl is the object for book controller.
     */
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $this->bookM = new BookM();
    }
    /**
     * Addbook function add the books
     * 
     * @return static return twig file.
     */
    public function addBook()
    {
        return $this->_twig->render('addbook.html.twig');
    }

    /**
     * Function addNewbook adds new book.
     * 
     * @param $bookImage   image for book.
     * @param $bookName    name for book.
     * @param $bookGenre   genre for book.
     * @param $bookAuthor  author name of book.
     * @param $bookEdition edition of book.
     * @param $bookDes     description of book.
     * @param $bookRating  book rating.
     * @param $ownerId     book owner id.
     * 
     * @return bool return true or false.
     */
    public function addNewBook(
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookDes,
        float $bookRating,
        int $ownerId
    ):bool {
        $bookId = null;
        $addNewBook = $this->bookM->addNewBook(
            $bookId,
            $bookImage,
            $bookName,
            $bookGenre,
            $bookAuthor,
            $bookEdition,
            $bookDes,
            $bookRating,
            $ownerId
        );
        return $addNewBook;
    }

    /**
     * Function getPersonalBook give the personalbooks.
     * 
     * @param $id id of owner.
     * 
     * @return static 
     */
    public function getPersonalBook(int $id)
    {   
        $personalBooks = $this->bookM->getPersonalBook($id);
        return $this->_twig->render(
            'personalbook.html.twig',
            ['personalBooks'=>$personalBooks]
        );     
    }

    /**
     * Function getBookDetails give books detail.
     * 
     * @param $id is book id.
     * 
     * @return void array in json form.
     */
    public function getBookDetails(int $id)
    {
        $personalBooks = $this->bookM->getBookDetails($id);
        echo json_encode($personalBooks);
    }

    /**
     * Function updateBook update the books.
     * 
     * @param $bookId      is id for book.
     * @param $bookImage   image of book.
     * @param $bookName    is name of book.
     * @param $bookGenre   is Genre of book.
     * @param $bookAuthor  is author of book.
     * @param $bookEdition is edition of book.
     * @param $bookDes     is description of book.
     * @param $bookRating  is rating of book.
     * @param $ownerId     is owner id.
     * 
     * @return void json encodeed array.
     */
    public function updateBook(
        int $bookId,
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookDes,
        float $bookRating,
        int $ownerId
    ) {
        $addNewBook = $this->bookM->addNewBook(
            $bookId,
            $bookImage,
            $bookName,
            $bookGenre,
            $bookAuthor,
            $bookEdition,
            $bookDes,
            $bookRating,
            $ownerId
        );
        if ($addNewBook == true) {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render(
                'personalbook.html.twig', 
                ['personalBooks'=>$personalBooks]
            );     
            $edit = "Edited Successfully!";
            $response = ['edit'=>$edit, 'html'=>$personalHtml];
        } else {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render(
                'personalbook.html.twig', 
                ['personalBooks'=>$personalBooks]
            );
            $edit = "No any change!";     
            $response = ['edit'=>$edit, 'html'=>$personalHtml];
        }
        echo json_encode($response);
    }

    /**
     * Function deletePersonalBook delete personal book.
     * 
     * @param $bookId  is book id.
     * @param $ownerId is owner id.
     * 
     * @return void array in json format.
     */
    public function deletePersonalBook(int $bookId, int $ownerId)
    {
        $deleteBook = $this->bookM->deletePersonalBook($bookId);
        if ($deleteBook === true) {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render(
                'personalbook.html.twig',
                ['personalBooks'=>$personalBooks]
            );     
            $delete = "Deleted Successfully!";
            $response = ['delete'=>$delete, 'html'=>$personalHtml];
        } else {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render(
                'personalbook.html.twig',
                ['personalBooks'=>$personalBooks]
            );
            $delete = "No Any Delete!";     
            $response = ['delete'=>$delete, 'html'=>$personalHtml];
        }
        echo json_encode($response);
    }

    /**
     * Function bookFeedback give feedback for book.
     * 
     * @param $bookid is book id.
     * 
     * @return void array in json format.
     */
    public function bookFeedback($bookid)
    {
        $feedback = $this->bookM->bookFeedback($bookid);
        $allfeedback = $this->bookM->allBookFeedback($bookid);
        $bookFeedback = $this->_twig->render(
            'feedback.html.twig',
            ['bookfeedback'=>$feedback]
        );
        $allBookFeedback = $this->_twig->render(
            'feedbacklist.html.twig',
            ['allbookfeedback'=>$allfeedback]
        );
        $res = ['html1'=>$bookFeedback, 'html2'=>$allBookFeedback];
        echo json_encode($res);
    }
    /**
     * Function insertBookFeedback for feedback
     * 
     * @param $bookId   is book id.
     * @param $feedback is feedback.
     * @param $userid   is user id.
     * @param $userName is name of user.
     * 
     * @return void  array in json format.
     */
    public function insertBookFeedback(int $bookId, string $feedback,
        int $userid, string $userName
    ) {
        $feedback = $this->bookM->insertBookFeedback(
            $bookId, $feedback,
            $userid, $userName
        );
        if ($feedback == true) {
            $allfeedback = $this->bookM->allBookFeedback($bookId);
            $allBookFeedback = $this->_twig->render(
                'feedbacklist.html.twig',
                ['allbookfeedback'=>$allfeedback]
            );
            $feedbackMsg = "success";
            $res = ['feedbackhtml'=>$allBookFeedback, 'feedbackmsg'=>$feedbackMsg];
        } else {
            $allfeedback = $this->bookM->allBookFeedback($bookId);
            $allBookFeedback = $this->_twig->render(
                'feedbacklist.html.twig',
                ['allbookfeedback'=>$allfeedback]
            );
            $feedbackMsg = "failed!";
            $res = ['feedbackhtml'=>$allBookFeedback, 'feedbackmsg'=>$feedbackMsg];
        }
        echo json_encode($res);
    }
}
?>