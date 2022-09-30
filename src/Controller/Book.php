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
    ): bool {
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
            $edit = "You edited $bookName!";
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
        $bookDetails = $this->bookM->getBookDetails($bookId);
        $deleteBook = $this->bookM->deletePersonalBook($bookId);
        if ($deleteBook === true) {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render(
                'personalbook.html.twig',
                ['personalBooks'=>$personalBooks]
            );
            $bookName = $bookDetails[0]['book_name'];
            $delete = "You deleted $bookName!";
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
     * @param $userId is user  id.
     *
     * @return void array in json format.
     */
    public function bookFeedback(int $bookid, int $userId): void
    {
        $feedback = $this->bookM->bookFeedback($bookid);
        $allfeedback = $this->bookM->allBookFeedback($bookid);
        $requestStatus = $this->bookM->requestStatus($bookid, $userId);
        $rqst = isset($requestStatus['status']) ? $requestStatus['status'] : -1;
        $bookFeedback = $this->_twig->render(
            'feedback.html.twig',
            ['bookfeedback'=>$feedback,'userId'=>$userId,'reqst'=>$rqst]
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
    public function insertBookFeedback(
        int $bookId,
        string $feedback,
        int $userid,
        string $userName
    ): void {
        $feedback = $this->bookM->insertBookFeedback(
            $bookId,
            $feedback,
            $userid,
            $userName
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
    /**
     * Function bookRequest for request book.
     *
     * @param $bookId      is book id.
     * @param $ownerId     is book ownerId.
     * @param $requesterId is  id  of requester.
     *
     * @return void array in json format.
     */
    public function bookRequest(int $bookId, int $ownerId, int $requesterId): void
    {
        $request = $this->bookM->bookRequest($bookId, $ownerId, $requesterId);
        $res = ['request'=>$request];
        echo json_encode($res);
    }

    /**
     * Function bookReturnRequest for issued book.
     *
     * @param $bookId      is book id.
     * @param $ownerId     is book ownerId.
     * @param $requesterId is  id  of requester.
     *
     * @return void array in json format.
     */
    public function bookReturnRequest(int $bookId, int $ownerId, int $requesterId)
    {
        $request = $this->bookM->bookReturnRequest($bookId, $ownerId, $requesterId);
        $res = ['returnrequest'=>$request];
        echo json_encode($res);
    }

    /**
     * Function getAllSentRequest to get all request book.
     *
     * @param $userId is user id.
     *
     * @return static twig file sentrequest with all list.
     */
    public function getAllSentRequest(int $userId)
    {
        $allSentRequest = $this->bookM->getAllSentRequest($userId);
        return $this->_twig->render(
            'sentrequest.html.twig',
            ['allsentrequest'=>$allSentRequest]
        );
    }

    /**
     * Function getAllRecievedRequest to get allreceived request book.
     *
     * @param $userId is user id.
     *
     * @return static twig file  with all received request list.
     */
    public function allReceivedRequest(int $userId)
    {
        $allReceivedRequest = $this->bookM->getAllReceivedRequest($userId);
        return $this->_twig->render(
            'receivedrequest.html.twig',
            ['allreceivedrequest'=>$allReceivedRequest]
        );
    }

    /**
     * Function updateRequest update request status.
     *
     * @param $requesterId is id of requester.
     * @param $bookId      is book id.
     * @param $ownerId     is book owner id.
     * @param $status      is status of book.
     * @param $reason      is reason for reject book.
     *
     * @return void nothing return.
     */
    public function updateRequest(
        int $requesterId,
        int $bookId,
        int $ownerId,
        int $status,
        string $reason
    ): void {
        $request = $this->bookM->updateRequest(
            $requesterId,
            $bookId,
            $ownerId,
            $status,
            $reason
        );
        if ($status == 1) {
            if ($request == true) {
                $_SESSION['msg'] = 'Request Granted';
            } else {
                $_SESSION['msg'] = "Request not Granted";
            }
        }
        if ($status == 4) {
            if ($request == true) {
                $_SESSION['msg'] = 'Request is Rejected';
            } else {
                $_SESSION['msg'] = "Request isnot Rejected";
            }
        }
        if ($status == 3) {
            if ($request == true) {
                $_SESSION['msg'] = 'Return Request Granted';
            } else {
                $_SESSION['msg'] = "Return Request not Granted";
            }
        }
        header('location:response.php');
    }
}
