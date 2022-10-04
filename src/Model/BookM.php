<?php
/**
 * Book Model.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */

namespace Bookxchange\Bookxchange\Model;

use Bookxchange\Bookxchange\Config\DbConnection;

/**
 * BookM that controls book database.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class BookM
{
    private $_conn;

    /**
     * Constructor for the Book Model.
     */
    public function __construct()
    {
        $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }

    /**
     * Function getAllBooks gives all book.
     *
     * @param $start   start for pagination.
     * @param $perPage is record per page.
     * @param $userId is user id.
     *
     * @return array list of book.
     */
    public function getAllBooks(int $start, int $perPage, int $userId): array
    {
        $status='active';
        $sql = "SELECT b.*,r.status as rstatus FROM `books` as b
        LEFT JOIN `request` as r on r.book_id = b.id AND r.requester_id = ?
         WHERE b.status = ? LIMIT ?,?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("isii", $userId, $status, $start, $perPage);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }
    /**
     * Function getRecentBook give recent books.
     *
     * @return array array of recent book.
     */
    public function getRecentBook()
    {
        $start = 5;
        $step = 5;
        $sql = "SELECT * FROM `books` WHERE
         upload_date<= SYSDATE() ORDER BY upload_date DESC LIMIT ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $start);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function addNewbook adds new book.
     *
     * @param $bookId      book id.
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
        int $bookId = null,
        string $bookImage,
        string $bookName,
        string $bookGenre,
        string $bookAuthor,
        string $bookEdition,
        string $bookDes,
        float $bookRating,
        int $ownerId
    ): bool {
        $sql = "INSERT INTO `books` (id,image, book_name, genre, author,
         edition, description, rating, owner_id) VALUES (?,?,?,?,?,?,?,?,?)
          ON DUPLICATE KEY UPDATE
           id=VALUES(id),image=VALUES(image),book_name=VALUES(book_name),
           genre=VALUES(genre),author=VALUES(author),edition=VALUES(edition),
           description=VALUES(description),rating=VALUES(rating),
           owner_id=VALUES(owner_id) ";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "issssssdi",
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
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function getPersonalBook give the personalbooks.
     *
     * @param $id id of owner.
     *
     * @return static  list of book
     */
    public function getPersonalBook(int $id)
    {
        $sql = "SELECT * FROM `books` WHERE owner_id=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getBookDetails give books detail.
     *
     * @param $id is book id.
     *
     * @return array array of bookdetail.
     */
    public function getBookDetails(int $id): array
    {
        $sql = "SELECT * FROM `books` WHERE id=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function deletePersonalBook delete personal book.
     *
     * @param $bookId is book id.
     *
     * @return bool true or false.
     */
    public function deletePersonalBook(int $bookId): bool
    {
        $sql = "DELETE FROM `books` WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * Function bookFeedback give feedback for book.
     *
     * @param $bookid is book id.
     *
     * @return array array of bookfeedback.
     */
    public function bookFeedback(int $bookid): array
    {
        $sql = "SELECT b.id,b.book_name,b.description,
        b.image,b.owner_id, r.user_name, r.rating,
         r.image as user_image,r.mobile_no, r.address, r.email
        FROM books as b
        INNER JOIN register as r on r.id = b.owner_id
        WHERE b.id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book details present');
        }
        $arr = $res->fetch_assoc();
        $stmt->close();
        return $arr;
    }

    /**
     * Function allBookFeedback
     *
     * @param $bookId is bookid.
     *
     * @return array all feed back
     */
    public function allBookFeedback(int $bookId): array
    {
        $sql = "SELECT * FROM feedback WHERE book_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function insertBookFeedback for feedback
     *
     * @param $bookId   is book id.
     * @param $feedback is feedback.
     * @param $userid   is user id.
     * @param $userName is name of user.
     *
     * @return bool  true or false.
     */
    public function insertBookFeedback(
        int $bookId,
        string $feedback,
        int $userid,
        string $userName
    ): bool {
        $sql = "INSERT INTO feedback (commenter_name, feedback, user_id, book_id)
         VALUES (?,?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ssii", $userName, $feedback, $userid, $bookId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function getNumOfBooks
     *
     * @return int number of books.
     */
    public function getNumOfBooks(): int
    {
        $status = 'active';
        $sql = "SELECT * FROM `books` WHERE status = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No books');
        }
        return $res->num_rows;
    }

    /**
     * Function requestStatus status of request book
     *
     * @param $bookid is book id.
     * @param $userId is userId.
     *
     * @return mixed array of status
     */
    public function requestStatus(int $bookid, int $userId): mixed
    {
        $sql ="SELECT status FROM `request` WHERE book_id = ? and requester_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ii", $bookid, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        return $arr;
    }

    /**
     * Function isPresentId
     *
     * @param $bookId      is book id.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return mixed id of request id
     */
    public function isPresentId(int $bookId, int $ownerId, int $requesterId): mixed
    {
        $sql = "SELECT id FROM `request` WHERE
         book_id = ? AND owner_id = ? AND requester_id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("iii", $bookId, $ownerId, $requesterId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr = $res->fetch_assoc();
        return $arr;
    }

    /**
     * Function bookRequest
     *
     * @param $bookId      is book id.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return bool true or false
     */
    public function bookRequest(int $bookId, int $ownerId, int $requesterId): bool
    {
        $rqst_date = date("Y-m-d h:i:sa");
        $issued_date =  '';
        $return_date = '';
        $status = 0;
        $reason = '';
        $isPresentId = $this->isPresentId($bookId, $ownerId, $requesterId);
        $presentId = isset($isPresentId['id']) ? $isPresentId['id'] : null;
        $sql = "INSERT INTO `request` 
        (id,requester_id,owner_id,book_id,status,
        reason,rqst_date,issued_date,return_date)
         VALUES (?,?,?,?,?,?,?,?,?)
         ON DUPLICATE KEY UPDATE id=VALUES(id),requester_id=VALUES(requester_id),
         owner_id=VALUES(owner_id), book_id=VALUES(book_id),
          status=VALUES(status), reason=VALUES(reason),
           rqst_date=VALUES(rqst_date), issued_date=VALUES(issued_date),
            return_date=VALUES(return_date)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "iiiiissss",
            $presentId,
            $requesterId,
            $ownerId,
            $bookId,
            $status,
            $reason,
            $rqst_date,
            $issued_date,
            $return_date
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function bookReturnRequest
     *
     * @param $bookId      is bookid.
     * @param $ownerId     is owner id.
     * @param $requesterId is requester id.
     *
     * @return bool true or false.
     */
    public function bookReturnRequest(
        int $bookId,
        int $ownerId,
        int $requesterId
    ): bool {
        $status = 2;
        $returnDate = date("Y-m-d h:i:sa");
        $sql = "UPDATE `request` SET status = ?, return_date = ? WHERE
         requester_id = ? AND book_id = ? AND owner_id= ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "isiii",
            $status,
            $returnDate,
            $requesterId,
            $bookId,
            $ownerId
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function  getAllSentRequest
     *
     * @param $userId is user id.
     *
     * @return array array of all sent request.
     */
    public function getAllSentRequest(int $userId): array
    {
        $sql = "SELECT r.status, r.reason,b.book_name,rg.user_name as owner_name
         FROM `request` as r
        INNER JOIN `books` as b on b.id=r.book_id
        INNER JOIN `register` as rg on rg.id=r.owner_id
         WHERE requester_id =?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows ===0) {
            exit('no sent request');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function  getAllReceivedRequest
     *
     * @param $userId is user id.
     *
     * @return array array of all received request.
     */
    public function getAllReceivedRequest(int $userId): array
    {
        $sql = "SELECT r.status,r.requester_id,r.book_id,r.owner_id,
        b.book_name,rg.user_name as requester_name 
        FROM `request` as r
        INNER JOIN `books` as b on b.id=r.book_id
        INNER JOIN `register` as rg on rg.id=r.requester_id
         WHERE r.owner_id =?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows ===0) {
            exit('no sent request');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function updateRequest
     *
     * @param $requesterId requster id
     * @param $bookId      is book id
     * @param $ownerId     is owner id.
     * @param $status      is status.
     * @param $reason      is reason for book.
     *
     * @return bool  true or false.
     */
    public function updateRequest(
        int $requesterId,
        int $bookId,
        int $ownerId,
        int $status,
        string $reason
    ): bool {
        $issuedDate = '';
        if ($status == 1) {
            $issuedDate =date("Y-m-d h:i:sa");
        }
        $sql = "UPDATE `request` SET status = ?, reason = ?, issued_date=?
         WHERE requester_id = ? AND book_id = ? AND owner_id= ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "issiii",
            $status,
            $reason,
            $issuedDate,
            $requesterId,
            $bookId,
            $ownerId
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $res = true;
        } else {
            $res = false;
        }
        $stmt->close();
        return $res;
    }

     /**
     * Function bookSeach
     *
     * @param $bookData is book data to search
     *
     * @return mixed is array of matched records
     */
    public function bookSearch(string $bookData): mixed
    {
        $bookData = "%".$bookData."%";
        $sql = "SELECT * FROM `books` WHERE book_name LIKE ? OR author LIKE ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $bookData, $bookData);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No Record Found');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    /**
     * Function getBookRating
     * 
     * @param $bookId is book id.
     * 
     * @return array is array of rating and rater.
     */
    public function getBookRating(int $bookId):array 
    {
        $sql = "SELECT rating, rater FROM `books` WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $res = $stmt->get_result();
        $arr =$res->fetch_assoc();
        $stmt->close();
        return $arr;
        
    }
    /**
     * Function updateBookRating
     *
     * @param $bookId is book id.
     * @param $bookRating is book rating.
     * @param $rater is no of rater.
     *
     * @return void nothing
     */
    public function updateBookRating(int $bookId, float $bookRating, int $rater): void
    {
        $sql = "UPDATE `books` SET rating = ?, rater = ? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("dii", $bookRating, $rater, $bookId);
        $stmt->execute();
    }
}
