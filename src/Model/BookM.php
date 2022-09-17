<?php

namespace Bookxchange\Bookxchange\Model;

use Bookxchange\Bookxchange\Config\DbConnection;

class BookM
{
    private $_conn;
    public function __construct()
    {
        $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }
    public function getAllBooks(): array
    {
        $sql = "SELECT * FROM `books`";
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            exit('No book present');
        }
        $arr =$res->fetch_all(MYSQLI_ASSOC);
        // while ($row = $res->fetch_assoc()) {
        //     $arr[] = $row;
        // }
        $stmt->close();
        return $arr;
    }
    public function addNewBook(
        int $bookId = Null,
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
          ON DUPLICATE KEY UPDATE id=VALUES(id),image=VALUES(image),book_name=VALUES(book_name),genre=VALUES(genre),author=VALUES(author),edition=VALUES(edition),description=VALUES(description),rating=VALUES(rating),owner_id=VALUES(owner_id) ";
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
    public function getPersonalBook($id)
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
    public function getBookDetails($id)
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
}
