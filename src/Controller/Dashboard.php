<?php
namespace Bookxchange\Bookxchange\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Bookxchange\Bookxchange\Model\BookM;

class Dashboard
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
    public function getDashboard()
    {
        $books = $this->bookM->getAllBooks();
        return $this->_twig->render('dashboard.html.twig',['books'=>$books]);
    }
}
?>