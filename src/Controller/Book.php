<?php

namespace Bookxchange\Bookxchange\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Model\BookM;

class Book
{
    private $_twig;
    private $_loader;
    protected $bookM;
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $this->bookM = new BookM();
    }

    public function addBook()
    {
        return $this->_twig->render('addbook.html.twig');
    }
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
    public function getPersonalBook(int $id)
    {   
        $personalBooks = $this->bookM->getPersonalBook($id);
        return $this->_twig->render('personalbook.html.twig',['personalBooks'=>$personalBooks]);     
    }
    public function getBookDetails(int $id) {
        $personalBooks = $this->bookM->getBookDetails($id);
        echo json_encode($personalBooks);
    }
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
            $personalHtml = $this->_twig->render('personalbook.html.twig',['personalBooks'=>$personalBooks]);     
            $edit = "Edited Successfully!";
            $response = ['edit'=>$edit, 'html'=>$personalHtml];
        } else {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render('personalbook.html.twig',['personalBooks'=>$personalBooks]);
            $edit = "No any change!";     
            $response = ['edit'=>$edit, 'html'=>$personalHtml];
        }
        echo json_encode($response);
    }
    public function deletePersonalBook(int $bookId, int $ownerId)
    {
        $deleteBook = $this->bookM->deletePersonalBook($bookId);
        if ( $deleteBook === true) {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render('personalbook.html.twig',['personalBooks'=>$personalBooks]);     
            $delete = "Deleted Successfully!";
            $response = ['delete'=>$delete, 'html'=>$personalHtml];
        } else {
            $personalBooks = $this->bookM->getPersonalBook($ownerId);
            $personalHtml = $this->_twig->render('personalbook.html.twig',['personalBooks'=>$personalBooks]);
            $delete = "No Any Delete!";     
            $response = ['delete'=>$delete, 'html'=>$personalHtml];
        }
        echo json_encode($response);
    }
}
