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
     * @return array list of book.
     */
    public function getAllBooks(int $start, int $perPage): array
    {
        $status='active';
        $sql = "SELECT * FROM `books` WHERE status = ? LIMIT ?,?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("sii", $status, $start, $perPage);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $arr;
    }

    public function getRecentBook()
    {
        $start = 5;
        $step = 5;
        $sql = "SELECT * FROM `books` WHERE upload_date<= SYSDATE() ORDER BY upload_date DESC LIMIT ?";
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
    public function getBookDetails(int $id):array
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
    public function deletePersonalBook(int $bookId):bool
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
    public function bookFeedback(int $bookid):array
    {
        $sql = "SELECT b.book_name,b.description,b.image, r.user_name, r.rating,
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
    public function allBookFeedback(int $bookId):array
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
    public function insertBookFeedback(int $bookId, string $feedback,
        int $userid, string $userName
    ):bool {
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
    public function getNumOfBooks()
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
    
}
?>