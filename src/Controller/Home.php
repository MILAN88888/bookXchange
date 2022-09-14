<?php
namespace Bookxchange\Bookxchange\Controller;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Home 
{ 
    private $_twig;
    private $_loader;
    public function __construct()
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
    }
    public function getHome()
    {
        return $this->_twig->render('index.html.twig');
    }
}

?>