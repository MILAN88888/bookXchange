<?php
/**
 * User page that controls User.
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
 * User class that controls Users.
 *
 * PHP version 8.1.3
 *
 * @category BookXchange.
 * @package  BookXchange
 * @author   Original Author <chaudharymilan996@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://pear.php.net/package/PackageName
 */
class UserM
{

   
    private $_conn;

     /**
      * Constructor for User.
      */
    public function __construct()
    {
        $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }

    /**
     * Function isEmailExit check email.
     * 
     * @param $email email.
     * 
     * @return bool true or false.
     */
    public function isEmailExit(string $email):bool
    {
        $sql = "SELECT id FROM  `register` WHERE `email`=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;  
    }

    /**
     * Function isPhoneExit check phone
     * 
     * @param $phone phone number
     * 
     * @return bool true or false
     */
    public function isPhoneExit(string $phone):bool
    {
        $sql = "SELECT id FROM  `register` WHERE mobile_no=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function getLogin get login
     * 
     * @param $phone phone number
     * 
     * @return array all details
     */
    public function getLogin(string $phone):array
    {
        $sql = "SELECT * FROM `register` WHERE mobile_no=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) { 
            exit('No row selected');
        }
        $res = $arr->fetch_assoc();
        $stmt->close();
        return $res;
    }

     /**
      * Function getRegister give register
      * 
      * @param $userImage   image for user.
      * @param $userName    is user name.
      * @param $userMobile  is user mobile.
      * @param $userAddress is user address.
      * @param $userEmail   is user email.
      * @param $userPass    is password.
      * 
      * @return int insert id.
      */
    public function getRegister($userImage, $userName,
        $userMobile, $userAddress, $userEmail, $userPass
    ): int {
        $token = '';
        $usertype = 0;
        $status = 'active';
        $sql = "INSERT INTO `register` (image,user_name,mobile_no,address,
        email,password,status,token,user_type) values(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param(
            "sssssssss", $userImage, $userName, $userMobile,
            $userAddress, $userEmail,  $userPass, $status, $token, $usertype
        );
        $stmt->execute();
        if ($stmt->affected_rows > 0 ) {
            $res = $this->_conn->insert_id;
        } else {
            $res = 0;
        }
        $stmt->close();
        return $res;
    }

    /**
     * Function updateToken update token
     * 
     * @param $userId user id
     * @param $token  token for user
     * 
     * @return bool true or false
     */
    public function updateToken(string $userId, $token):bool
    {
        $sql = "UPDATE `register` SET token=? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $token, $userId);
        $stmt->execute();
        if ($stmt->affected_rows === 0 ) {
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }

    /**
     * Function updatePassword
     * 
     * @param $pass   password.
     * @param $mobile mobile number.
     * 
     * @return bool true or false
     */
    public function updatePassword(string $pass, string $mobile):bool
    {
        $sql = "UPDATE `register` SET password=? WHERE mobile_no = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $pass, $mobile);
        $stmt->execute();
        if ($stmt->affected_rows === 0 ) {
            $res = false;
        } else {
            $res = true;
        }
        $stmt->close();
        return $res;
    }
           
}
?>
