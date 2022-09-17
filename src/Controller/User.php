<?php

namespace Bookxchange\Bookxchange\Controller;

use Bookxchange\Bookxchange\Model\UserM;

class User
{
    protected $userM;
    public function __construct($baseurl)
    {
        $this->userM = new UserM();
    }
    public function logout(string $user_id)
    {
        $token=null;
        $updateToken = $this->userM->updateToken($user_id, $token);
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
    public function getLogin(string $phone, string $pass, int $id=0)
    {
        session_start();
        $isPhoneExit = $this->userM->isPhoneExit($phone);
        if ($isPhoneExit === true) {
            $getLoginDetail = $this->userM->getLogin($phone);
            if (password_verify($pass, $getLoginDetail['password'])) {
                if ($getLoginDetail['token'] == null) {
                    $token = bin2hex(random_bytes(32));
                    $updateToken = $this->userM->updateToken($getLoginDetail['id'], $token);
                    $_SESSION['user_id'] = $getLoginDetail['id'];
                    $_SESSION['token'] = $token;
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
    public function getRegister(
        $user_image,
        $user_name,
        $user_mobile,
        $user_address,
        $user_email,
        $user_pass
    ) {
        $isPhoneExit = $this->userM->isPhoneExit($user_mobile);
        if ($isPhoneExit === false) {
            $isEmailExit = $this->userM->isEmailExit($user_email);
            if ($isEmailExit === false) {
                $hashPass = password_hash($user_pass, PASSWORD_BCRYPT);
                $isRegister = $this->userM->getRegister($user_image, $user_name, $user_mobile, $user_address, $user_email, $hashPass);
                if ($isRegister > 0) {
                    $this->getLogin($user_mobile, $user_pass, $isRegister);
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
    public function getForgetPass(string $mobile_no)
    {
        $isPhoneExit = $this->userM->isPhoneExit($mobile_no);
        if ($isPhoneExit === true) {
            $otp = mt_rand(1111, 9999);
            $otpExpire = time()+120;
            $_SESSION['otp'] = $otp;
            $_SESSION['otpExpire'] = $otpExpire;
            $_SESSION['mobile'] = $mobile_no;
            $myfile = fopen("otp.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $otp);
            fclose($myfile);
            $_SESSION['msg'] = "Otp sent..";
            header('location:otpverify.php');
        } else {
            $_SESSION['fail'] = "Phone no. not  exit!";
            header('location:../../index.php');
        }
    }
    public function updatePassword(string $pass, string $mobile)
    {
        $hashPass = password_hash($pass, PASSWORD_BCRYPT);
        $updatePass = $this->userM->updatePassword($hashPass, $mobile);
        return $updatePass;
    }
}
