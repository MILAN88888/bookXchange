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

namespace Bookxchange\Bookxchange\Controller;

use Bookxchange\Bookxchange\Model\UserM;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
class User
{
    private $_twig;
    private $_loader;
    protected $userM;

    /**
     * Constructor for User.
     * 
     * @param $baseurl is baseurl
     */
    public function __construct($baseurl)
    {
        $this->userM = new UserM();
        $this->_loader = new FilesystemLoader(__DIR__.'/../View/templates');
        $this->_twig = new Environment($this->_loader);
        $this->_twig->addGlobal('baseurl', $baseurl);
    }

    /**
     * Function logout.
     *
     * @param $userId login user id.
     *
     * @return void nothing
     */
    public function logout(string $userId): void
    {
        $token=null;
        $updateToken = $this->userM->updateToken($userId, $token);
        if ($updateToken == true) {
            session_unset();
            session_destroy();
            $_SESSION['msg'] = "Logout successfully!!";
            header("location:../../index.php");
        } else {
            $_SESSION['msg'] = "Error in logout";
            header("location:dashboard.php");
        }
    }

    /**
     * Function getLogin to get Login
     *
     * @param $phone phone of user.
     * @param $pass  password.
     * @param $id    user id.
     *
     * @return void nothing
     */
    public function getLogin(string $phone, string $pass, int $id=0): void
    {
        session_start();
        $isPhoneExit = $this->userM->isPhoneExit($phone);
        if ($isPhoneExit === true) {
            $getLoginDetail = $this->userM->getLogin($phone);
            if (password_verify($pass, $getLoginDetail['password'])) {
                if ($getLoginDetail['token'] == null) {
                    $token = bin2hex(random_bytes(32));
                    $updateToken = $this->userM->updateToken(
                        $getLoginDetail['id'],
                        $token
                    );
                    $_SESSION['user_id'] = $getLoginDetail['id'];
                    $_SESSION['token'] = $token;
                    $_SESSION['user_name'] = $getLoginDetail['user_name'];
                    $loginName = $_SESSION['user_name'];
                    $_SESSION['msg'] = "Welcome $loginName";
                    header('location:dashboard.php');
                } else {
                    $_SESSION['fail'] = "No token!";
                    header('location:../../index.php');
                }
            } else {
                $_SESSION['fail'] = "Invalid Password!";
                header('location:../../index.php');
            }
        } else {
            $_SESSION['fail'] = "Phone Number isnot Exit!";
            header('location:../../index.php');
        }
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
     * @return void nothing
     */
    public function getRegister(
        $userImage,
        $userName,
        $userMobile,
        $userAddress,
        $userEmail,
        $userPass
    ): void {
        $isPhoneExit = $this->userM->isPhoneExit($userMobile);
        if ($isPhoneExit === false) {
            $isEmailExit = $this->userM->isEmailExit($userEmail);
            if ($isEmailExit === false) {
                $hashPass = password_hash($userPass, PASSWORD_BCRYPT);
                $isRegister = $this->userM->getRegister(
                    $userImage,
                    $userName,
                    $userMobile,
                    $userAddress,
                    $userEmail,
                    $hashPass
                );
                if ($isRegister > 0) {
                    $this->getLogin($userMobile, $userPass, $isRegister);
                } else {
                    $_SESSION['fail'] = "Registration failed!";
                    header('location:../../index.php');
                }
            } else {
                $_SESSION['fail'] = "Email already exit!";
                header('location:../../index.php');
            }
        } else {
            $_SESSION['fail'] = "Phone no. already exit!";
            header('location:../../index.php');
        }
    }

    /**
     * Function getForgetPass give forget control.
     *
     * @param $mobileNo is mobile number of user.
     *
     * @return void nothing
     */
    public function getForgetPass(string $mobileNo): void
    {
        $isPhoneExit = $this->userM->isPhoneExit($mobileNo);
        if ($isPhoneExit === true) {
            $otp = mt_rand(1111, 9999);
            $otpExpire = time()+120;
            $_SESSION['otp'] = $otp;
            $_SESSION['otpExpire'] = $otpExpire;
            $_SESSION['mobile'] = $mobileNo;
            $myfile = fopen("otp.txt", "w") or die("Unable to open file!");
            fwrite($myfile, (string) $otp);
            fclose($myfile);
            $_SESSION['msg'] = "Otp sent..";
            header('location:otpverify.php');
        } else {
            $_SESSION['fail'] = "Phone no. not  exit!";
            header('location:../../index.php');
        }
    }

    /**
     * Function updatePassword update pass.
     *
     * @param $pass   is password.
     * @param $mobile is mobile no.
     *
     * @return bool true or false.
     */
    public function updatePassword(string $pass, string $mobile): bool
    {
        $hashPass = password_hash($pass, PASSWORD_BCRYPT);
        $updatePass = $this->userM->updatePassword($hashPass, $mobile);
        return $updatePass;
    }

    /**
     * Function userProfile show the profile of user.
     *
     * @param $userId user id.
     *
     * @return static twig file.
     */
    public function userProfile(int $userId)
    {
        $userProfile = $this->userM->userProfile($userId);
        return $this->_twig->render('profile.html.twig', ['user'=>$userProfile]);
    }

    /**
     * Function isNumber Exit
     *
     * @param $phone phone number
     *
     * @return bool true or false
     */
    public function isNumberExit(string $phone): bool
    {
        return $this->userM->isPhoneExit($phone);
    }

     /**
      * Function isEmailExit check
      *
      * @param $newUserEmail email of user
      *
      * @return bool true or false
      */
    public function isEmailExit($newUserEmail): bool
    {
        return $this->userM->isEmailExit($newUserEmail);
    }

     /**
      * Function updateProfile 
      * 
      * @param $newUserImage   user image.
      * @param $newUserName    name of user
      * @param $newUserNumber  number of user.
      * @param $newUserAddress address of user.
      * @param $newUserEmail   email of user.
      * @param $userId         is id of user.
      * 
      * @return bool true or false.
      */
    public function updateProfile(
        $newUserImage,
        $newUserName,
        $newUserNumber,
        $newUserAddress,
        $newUserEmail,
        $userId
    ) {
        $updateProfile = $this->userM->updateProfile(
            $newUserImage,
            $newUserName,
            $newUserNumber,
            $newUserAddress,
            $newUserEmail,
            $userId
        );
        return $updateProfile;
    }
}
