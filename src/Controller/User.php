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
    protected $userM;

    /**
     * Constructor for User.
     */
    public function __construct()
    {
        $this->userM = new UserM();
    }

    /**
     * Function logout.
     * 
     * @param $userId login user id.
     *
     * @return void nothing 
     */
    public function logout(string $userId):void
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
    public function getLogin(string $phone, string $pass, int $id=0):void
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
                    $_SESSION['msg'] = "Login Successfully!";
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
    ):void {
        $isPhoneExit = $this->userM->isPhoneExit($userMobile);
        if ($isPhoneExit === false) {
            $isEmailExit = $this->userM->isEmailExit($userEmail);
            if ($isEmailExit === false) {
                $hashPass = password_hash($userPass, PASSWORD_BCRYPT);
                $isRegister = $this->userM->getRegister(
                    $userImage,
                    $userName, $userMobile, $userAddress, $userEmail, $hashPass
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
    public function getForgetPass(string $mobileNo):void
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
    public function updatePassword(string $pass, string $mobile):bool
    {
        $hashPass = password_hash($pass, PASSWORD_BCRYPT);
        $updatePass = $this->userM->updatePassword($hashPass, $mobile);
        return $updatePass;
    }
}
?>