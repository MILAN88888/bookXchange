<?php
namespace Bookxchange\Bookxchange\Controller;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Home 
{ 
    private $_twig;
    private $_loader;
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
    }
    public function getHome()
    {
        return $this->_twig->render('index.html.twig');
    }
    public function getHeader($session)
    {
        return $this->_twig->render('header.html.twig',['session'=>$session]);
    }
    public function passwordReset()
    {
        return $this->_twig->render('reset.html.twig');
    }
    public function otpVerify()
    {
        return $this->_twig->render('otpverify.html.twig');
    }
}

?>