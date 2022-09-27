<?php
/**
 * Home the controls home.
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
 * Home Class controls home.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Home
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Constructor for home class.
     * 
     * @param $baseurl is baseurl.
     */
    public function __construct($baseurl)
    {
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
        $this->bookM = new BookM();
    }

    /**
     * Function getHome give home page.
     * 
     * @return static twig file.
     */
    public function getHome()
    {
        return $this->_twig->render('index.html.twig');
    }

    public function getHome2()
    {
        $bookRecentBook = $this->bookM->getRecentBook();
        return $this->_twig->render('index2.html.twig',['bookList'=>$bookRecentBook]);
    }

    /**
     * Function getHeader give header.
     * 
     * @param $session user session.
     * 
     * @return static twig file.
     */
    public function getHeader($session)
    {
        return $this->_twig->render('header.html.twig', ['session'=>$session]);
    }

    /**
     * Function passwordRest reset password
     * 
     * @return static twig file
     */
    public function passwordReset()
    {
        return $this->_twig->render('reset.html.twig');
    }

    /**
     * Function otpVerify for verify otp.
     * 
     * @return static twig file.
     */
    public function otpVerify()
    {
        return $this->_twig->render('otpverify.html.twig');
    }
}
?>