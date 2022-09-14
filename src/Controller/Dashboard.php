<?php
namespace Bookxchange\Bookxchange\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Dashboard
{ 
    private $_twig;
    private $_loader;
    public function __construct()
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
    }
    public function getDashboard($session)
    {
        return $this->_twig->render('dashboard.html.twig',['session'=>$session]);
    }
}

?>