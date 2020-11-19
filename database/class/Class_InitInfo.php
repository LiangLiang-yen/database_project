<?php
require_once("Class_Default.php");
require_once('../connectDB.php');

class InitInfo extends _Default{
   
    public static $gmail_Error = 0;
    public static $phone_Error = 1;
    public static $password_Error = 2;
    public static $passwordcheck_Error = 3;
    public static $passwordcheck_password_Error = 4;
    public static $beforepassword_Error = 5;

    public function __construct($db) {
        parent::__construct($db);
    }
   
/**
 *  function initInfo_UPDATE : 將資料UPDATE上去
 *  @param mixed $gmail 信箱
 *  @param mixed $phone 電話
 *  @param mixed $passworde 新密碼
 *  @param mixed $imgpath 相片位置預設=""
 *  @param mixed $studentId 學生ID
 */
public function initInfo_UPDATE($gmail,$phone,$password,$imgpath,$userid,$stat){

    $userid = $this->SQL_Injection_check($userid);
    $gmail = $this->SQL_Injection_check($gmail);
    $phone = $this->SQL_Injection_check($phone);
    $imgpath = $this->SQL_Injection_check($imgpath);
    $password = $this->SQL_Injection_check($password);
    if($stat=="InitInfo")
    {
        $query = "UPDATE Account SET email=:gmail, phoneNum=:phone, passWord=:password, imagePATH=:imgpath, firstLogin=0 WHERE userId=:USERID";
         $password= _Default::StringEncryption($password);
        $stmt=$this->db->prepare($query);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':gmail', $gmail);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':imgpath', $imgpath);
        $stmt->bindParam(':USERID', $userid);
        $stmt->execute();
    
        if ($stmt->rowCount() <= 0) {
            $stmt = null;
            return "UpddateFail";
        }
        else{
            $_SESSION['email']=$gmail;
            $_SESSION['phoneNum']=$phone;
            $_SESSION['imagePATH']=$imgpath;
            $stmt = null;
            return "Success";
        }
    }
    else
    {
        $query = "SELECT imagePATH,email,phoneNum  FROM Account WHERE userId=:USERID";
        $stmt=$this->db->prepare($query);
        $stmt->bindParam(':USERID',$userid);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $query='UPDATE Account SET ';
        $count=0;
        $pattern = "/_/";
        if($row['imagePATH']!="https://www.w3schools.com/bootstrap4/img_avatar1.png" && $imgpath!="https://www.w3schools.com/bootstrap4/img_avatar1.png")
        {
            $result1 = preg_split($pattern, $row['imagePATH']);
            $result2 = preg_split($pattern, $imgpath);
            if($result1[2]!=$result2[2])
            {
                $query.='imagePATH="' . $imgpath. '",';
                if(file_exists($row['imagePATH']))
                {
                    unlink($row['imagePATH']);
                }  
                $count++;
            }
        }
        elseif($row['imagePATH'] == "https://www.w3schools.com/bootstrap4/img_avatar1.png" && $imgpath!="https://www.w3schools.com/bootstrap4/img_avatar1.png")
        {
            $query.='imagePATH="' . $imgpath. '",';
            $count++;
        }
        if($row['email']!=$gmail)
        {
            $query.='email="' . $gmail. '",';
            $count++;
        }
        if($row['phoneNum']!=$phone)
        {
            $query.='phoneNum="' . $phone. '",';
            $count++;
        }
        if($password!=null)
        {
            $password= _Default::StringEncryption($password);
            $query.='passWord="' . $password. '",';
            $count++;
        }
        if($count==0)
        {
            $stmt = null;
            return "NoneedUPDATE";
        }
        else
        {
            $query = substr($query, 0, -1);
            $query.=' WHERE userId=:USERID';
            $stmt=$this->db->prepare($query);
            $stmt->bindParam(':USERID', $userid);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                $stmt = null;
                return "UpddateFail";
            }
            else
            {
                $_SESSION['email']=$gmail;
                $_SESSION['phoneNum']=$phone;
                $_SESSION['imagePATH']=$imgpath;
                $stmt = null;
                return "Success";
            }
        }

    }
     
}

/**
 * function check : 判斷 student_InitInfo視窗輸入值是否正確?
 * @param mixed $gmail 信箱
 * @param mixed $phone 電話
 * @param mixed $passworde 新密碼
 * @param mixed $passwordcheck 新密碼確認
 * @return 0 =  $gmail_Error 信箱格式錯誤
 * @return 1 =  $phone_Error 電話格是錯誤
 * @return 2 =  $password_Error 新密碼為空值
 * @return 3 =  $passwordcheck_Error 新密碼確認為空值
 * @return 4 =  $passwordcheck_phone_Error 新密碼 != 新密碼確認 
 * @return 5 =  $beforepassword_Error 原始密碼不正確
 * $count 計算錯誤的總數量
 */
public function check($gmail,$phone,$password,$passwordcheck,$imgpath,$beforepassword,$stat){
    $gmail= $this->SQL_Injection_check($gmail);
    $phone=$this->SQL_Injection_check($phone);
    $password=$this->SQL_Injection_check($password);
    $passwordcheck=$this->SQL_Injection_check($passwordcheck);
    $imgpath=$this->SQL_Injection_check($imgpath);
    $beforepassword=$this->SQL_Injection_check($beforepassword);
    
    $imgpath =str_replace("http://"._Default::$hostip,'../',$imgpath);
    if($imgpath==null)
    {
        $imgpath="https://www.w3schools.com/bootstrap4/img_avatar1.png";
    }
    $stack = array();

    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $gmail)) 
    {
        array_push($stack,InitInfo::$gmail_Error);
    }
    if(!preg_match("/^[0-9]{10}$/", $phone)) 
    {
        array_push($stack,InitInfo::$phone_Error);
    }
    if($password==null && $stat =="InitInfo")
    {
        array_push($stack, InitInfo::$password_Error);
    }elseif($passwordcheck==null && $stat =="InitInfo")
    {
        array_push($stack, InitInfo::$passwordcheck_Error);
    }elseif($password!=$passwordcheck && $stat =="InitInfo")
    {
        array_push($stack, InitInfo::$passwordcheck_password_Error);
    }
    if($stat=="changeinfo" && $beforepassword!=null)
    {
        if($beforepassword!=$this->selectpassword())
        {
            array_push($stack, InitInfo::$beforepassword_Error);
        }
        else
        {
            if($password==null)
            {
                array_push($stack, InitInfo::$password_Error);
            }elseif($passwordcheck==null)
            {
                array_push($stack, InitInfo::$passwordcheck_Error);
            }elseif($password!=$passwordcheck)
            {
                array_push($stack, InitInfo::$passwordcheck_password_Error);
            }
        }
    }
    if(count($stack)==0)
    {
        return $this -> initInfo_UPDATE($gmail,$phone,$password,$imgpath,$_SESSION['userid'],$stat);
    }
    else{
        $resault = "";
        foreach($stack as $item)
            $resault .= $item . ",";
        return $resault;
    }
}
/**
 * function selectid():查詢使用者名稱與ID
 */

public function selectid()
{
    $query = "SELECT name,email,phoneNum,imagePATH FROM Account WHERE userId=:USERID";
    $stmt=$this->db->prepare($query);
    $stmt->bindParam(':USERID', $_SESSION['userid']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $name=$row['name']." " . $_SESSION['userid'];
        $_SESSION['email']=$row['email'];
        $_SESSION['phoneNum']=$row['phoneNum'];
        $_SESSION['imagePATH']=$row['imagePATH'];
        $this->studentId= $_SESSION['userid'];
        $stmt = null;
        return $name;
    }
    $stmt = null;
}

public function selectfirstLogin()
{
    $query = "SELECT firstLogin FROM Account WHERE userId=:USERID";
    $stmt=$this->db->prepare($query);
    $stmt->bindParam(':USERID', $_SESSION['userid']);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
    {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row['firstLogin'] == 1)
        {
            $stmt = null;
            return true;
        }
        else
        {
            $stmt = null;
            return false;
        }
       
    }
    $stmt = null;
}

public function selectpassword()
{
    $query = "SELECT passWord FROM Account WHERE userId=:USERID";
    $stmt=$this->db->prepare($query);
    $stmt->bindParam(':USERID', $_SESSION['userid']);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
    {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return _Default::StringDecryption($row['passWord']);
    }
    $stmt = null;
}

public function select_emai_phoneNum_imagePATH()
{
    $query = "SELECT email,phoneNum,imagePATH FROM Account WHERE userId=:USERID";
    $stmt=$this->db->prepare($query);
    $stmt->bindParam(':USERID', $_SESSION['userid']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['email']=$row['email'];
        $_SESSION['phoneNum']=$row['phoneNum'];
        $_SESSION['imagePATH']=$row['imagePATH'];
        $this ->studentId= $_SESSION['userid'];
        $stmt = null;

    }
    $stmt = null;
}

}

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $imgPath = $_POST['imgPath'];
    $gmail = $_POST['gmail'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $passwordcheck = $_POST['passwordcheck'];
    $stat=$_POST['stat'];
    $initInfo =new InitInfo($db);
    if($_POST['stat']=="changeinfo")
    {
        $beforepassword=$_POST['beforepassword'];
    }
    else
    {
        $beforepassword="";
    }
    if (preg_match("#^data:image/\w+;base64,#i", $imgPath))
     {
        list($type, $imgPath) = explode(';', $imgPath);
        //unlink("../student_image/40643146_E3semVyzDm.png");
        list(, $imgPath)      = explode(',', $imgPath);
        $imgPathbase64 = base64_decode($imgPath);   
        $imgPath='../student_image/'. $_SESSION['userid'].'_'._Default::randomStringGenerate(10).'.png';
        //clearstatcache();
        file_put_contents($imgPath, $imgPathbase64);
    }
    echo $initInfo->check($gmail,$phone,$password,$passwordcheck,$imgPath,$beforepassword,$stat);
}
?>