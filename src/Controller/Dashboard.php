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
 * Dashboard class that controls dashboard.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class Dashboard
{
    private $_twig;
    private $_loader;
    protected $bookM;

    /**
     * Construtor for dashboard class.
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
     * Function getDashboard give the dashboard.
     *
     * @param $start   start pagination
     * @param $perPage number of record per page.
     * @param $pagi    number of pagination.
     * @param $userId user id.
     *
     * @return static twig file.
     */
    public function getDashboard(int $start, int $perPage, int $pagi, $userId)
    {
        $books = $this->bookM->getAllBooks($start, $perPage, $userId);
        return $this->_twig->render(
            'dashboard.html.twig',
            ['books'=>$books,'pagi'=>$pagi]
        );
    }
    /**
     * Function getNumOfBooks is number of books.
     *
     * @return int number of books.
     */
    public function getNumOfBooks(): int
    {
        return $this->bookM->getNumOfBooks();
    }
}
