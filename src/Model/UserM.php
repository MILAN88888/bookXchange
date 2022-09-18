<?php
namespace Bookxchange\Bookxchange\Model;
use Bookxchange\Bookxchange\Config\DbConnection;
class UserM
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
    public function getRegister($userImage, $userName, $userMobile, $userAddress, $userEmail, $userPass): int
    {
        $token = '';
        $usertype = 0;
        $status = 'active';
        $sql = "INSERT INTO `register` (image,user_name,mobile_no,address,email,password,status,token,user_type) values(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->_conn->prepare($sql);
        $stmt->bind_param("sssssssss",$userImage, $userName, $userMobile, $userAddress, $userEmail,  $userPass, $status, $token, $usertype);
        $stmt->execute();
        if ($stmt->affected_rows > 0 )
        {
            $res = $this->_conn->insert_id;
        } else {
            $res = 0;
        }
        $stmt->close();
        return $res;
    }
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