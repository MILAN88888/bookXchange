<?php
namespace Bookxchange\Bookxchange\Model;
use Bookxchange\Bookxchange\Config\DbConnection;
class User
{   
    private $_conn;
    public function __construct()
    {   $db = new DbConnection();
        $this->_conn = $db->getConnection();
    }
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
    public function getLogin(string $phone):array
    {
        $sql = "SELECT * FROM `register` WHERE mobile_no=?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("s",$phone);
        $stmt->execute();
        $arr = $stmt->get_result();
        if ($arr->num_rows === 0) exit('No row selected');
        $res = $arr->fetch_assoc();
        $stmt->close();
        return $res;
    }
    public function getRegister($user_image, $user_name, $user_mobile, $user_address, $user_email, $user_pass): int
    {
        $lattitude = 0;
        $longitude = 0;
        $token = 0;
        $rating = 0;
        $joindate = date("Y-m-d");
        $usertype = 0;
        $status = 0;
        $sql = "INSERT INTO `register` (image,user_name,mobile_no,address,email,lattitude,longitude,password,rating,status,token,user_type,join_date) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("sssssssssssss",$user_image, $user_name, $user_mobile, $user_address, $user_email, $lattitude, $longitude, $user_pass, $rating, $status,$token,$usertype,$joindate);
        $stmt->execute();
        if ($stmt->affected_rows > 0 )
        {
            $res = $this->_conn->insert_id;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function updateToken(string $user_id, string $token):bool
    {
        $sql = "UPDATE `register` SET token=? WHERE id = ?";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("ss", $token, $user_id);
        $stmt->execute();
        if ($stmt->affected_rows === 0 ) {
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }
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
        return $res;
    }
           
}
?>