<?php
require_once("Class_Default.php");
require_once('../connectDB.php');

class ManageUser extends _Default
{
    /**
     * 建構子
     */
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * 查詢 Student_Account 找尋帳號
     */
    public function student_list_load()
    {
        $query = "SELECT * FROM Account WHERE admin<>1 ORDER BY userId ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $counter = 0;
        if ($stmt->rowCount() > 0) {
            echo 'var student_list_content = "";';

            for ($row = $stmt->fetch(PDO::FETCH_ASSOC), $i = 0; $row; $row = $stmt->fetch(PDO::FETCH_ASSOC), $i++) {
                $img = isset($row['imagePATH']) ? $row['imagePATH'] : "https://www.w3schools.com/bootstrap4/img_avatar1.png";
                if ($counter % 10 == 0) {
                    if ($counter == 0) {
                        echo 'student_list_content += \'<div class="page" id="page' . (($counter / 10) + 1) . '">\';';
                    } else {
                        echo 'student_list_content += \'<div class="page hid" id="page' . (($counter / 10) + 1) . '">\';';
                    }
                }
                echo 'student_list_content += \'<div class="list-group" style="margin:0 0 -1 -1 ;">' .
                    '   <div class="row row' . $i . '">' .
                        '<div class="row list-group-item" style="height: 50px;line-height:30px;padding-top:6px;">' .
                            '<span class="col-sm-2">' .
                                '<img src="'.$img.'" style="border-radius:50%;width:35px;height:35px;">' .
                            '</span>' .
                            '<span class="col-sm-2">' .
                                '<label style="font-size: 16px;" class="name">' .  $row['name'] . '</label>' .
                            '</span>' .
                            '<span class="col-sm-2">' .
                                '<label style="font-size: 16px;" class="userId">' . $row['userId'] . '</label>' .
                            '</span>' .
                            '<span class="col-sm-3">' .
                                '<label style="font-size: 16px;" class="email">' . $row['email'] . '</label>' .
                            '</span>' .
                            '<span class="col-sm-2">' .
                            '   <button type="button" onclick="return resetPass(\\\'' . $row['userId'] . '\\\')" class="btn btn-info">重置密碼</button>' .
                            '</span>' .
                            '      <input type="checkbox" class="hid check_send_email" style="width:20px;height:20px;vertical-align:middle;" value="">' .
                            '      <input class="check_del_user hid"   type="checkbox" style="width:20px;height:20px;">' .
                        '</div>' .
                    '   </div>' .
                    '</div>\';';
                if ($counter % 10 == 9) {
                    echo 'student_list_content += \'</div>\';';
                }
                $counter++;
            }
        } else {
            echo 'var student_list_content = "";';
        }
        echo 'student_list_content += \' </div><div class="col-sm-12" style="text-align:center"> <ul class="pagination"><li onclick="change_page(this,\\\'pre\\\')" class="page_li page_bt"><a  href="#">Previous</a></li> \';';
        for ($i = 1; $i < ($counter / 10) + 1; $i++) {
            echo  'student_list_content += \'<li onclick="change_page(this,' . $i . ')" class="page_li"><a  href="#">' . $i . '</a></li>\';';
        }
        echo 'student_list_content += \' <li onclick="change_page(this,\\\'next\\\')" class="page_li"><a  href="#">Next</a></li></ul></div> \';';
    }

    /**
     * 新增使用者
     * @param mixed $Name 姓名
     * @param mixed $Username 帳號(學號)
     * @param mixed $Password 密碼(預設為身分證)
     * 
     * @return TRUE SQL新增資料成功
     * @return FALSE SQL新增資料失敗
     */
    public function addUser($Name, $Username, $Email)
    {
        $Name = parent::SQL_Injection_check($Name);
        $Username = parent::SQL_Injection_check($Username);
        $Password = parent::SQL_Injection_check(_Default::StringEncryption($this->randomStringGenerate(12, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
        $Email = parent::SQL_Injection_check($Email);

        $query = "INSERT INTO Account(name,userId,passWord,email) VALUES (:name, :userid, :pwd, :email)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $Name);
        $stmt->bindParam(':userid', $Username);
        $stmt->bindParam(':pwd', $Password);
        $stmt->bindParam(':email', $Email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "新增成功";
        } else
            return "新增失敗, 請聯絡網頁管理員";
    }

    public function addMoreUser($student_list)
    {
        $str = "";
        foreach ($student_list as $data) {
            $id = ltrim($data['id'], "   \r\n\t\0");
            $name = ltrim($data['name'], "   \r\n\t\0");
            $password = parent::SQL_Injection_check(_Default::StringEncryption($this->randomStringGenerate(12, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
            $email = ltrim($data['email'], "   \r\n\t\0");
            $str .= '(\'' . $name . '\',\'' . $id . '\',\'' . $password . '\',\'' . $email . '\'),';
        }
        $str = substr($str, 0, -1);

        $query = "INSERT INTO Account(name,userId,passWord,email) VALUES " . $str;
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return true;
        else
            return false;
    }

    /**
     * 自動寄出首次提供密碼郵件
     * @param mixed $Username 帳號(學號)
     * @return 錯誤訊息
     * @return "true" 正常寄出
     */
    public function sendPwdMail($Username)
    {
        $Username = parent::SQL_Injection_check($Username);

        $query = "SELECT name,passWord,email FROM Account WHERE userId=:userid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userid', $Username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $url = "http://" . _Default::$hostip;

            $to = $row['email']; //收件者
            $subject = "帳號啟用信"; //信件標題
            $msg = '<html><head><linkhref="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap"rel="stylesheet"><title>NFU賃居管理系統</title>
                    <style>body{margin:0;padding:0;}div{display:block;}.continer{height:100vh;text-align:center;}.content{font-family:"NotoSansTC",sans-serif;padding:30px;border:1px solid #dadce0;width:550px;height:380px;border-radius:8px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}</style></head><body><div class="continer"><div class="content"><img src="http://nfuaca.nfu.edu.tw/images/%E5%87%BA%E7%89%88%E7%B5%84/%E5%AD%B8%E6%A0%A1logo/nfuLogo_%E7%99%BD%E5%BA%95%E8%97%8D%E5%AD%97.jpg" alt="nfu_Logo">
                    <h1>' . $row['name'] . ' 您好：</h3><h2>請到賃居校外學生訪問系統進行登入<br>帳號：' . $Username . '<br>密碼：' . _Default::StringDecryption($row['passWord']) . '<br></h2><a href="'.$url.'">'.$url.'</a><h3 style="color:red">請勿直接回覆本郵件，此信件為系統自動發送<br></h3></div></div></body></html>'; //信件內容
            $headers = "Content-type:text/html;charset=UTF-8" . "\r\n"; //設定特殊標頭

            if ($this->sendEmail($to, $subject, $msg, $headers))
                return "true";
            else
                return $Username . " 寄信失敗, 請聯絡網頁管理員";
        } else
            return $Username . " 查詢失敗, 請聯絡網頁管理員";
    }

    /**
     * 刪除使用者
     * @param mixed $Username 帳號(學號)
     * @return 訊息
     */
    public function delUser($Username)
    {
        //first delete form data
        $query = "DELETE Student_Form,Student_Question
                    FROM (Teacher_ControlForm LEFT JOIN Student_Form ON Teacher_ControlForm.formId = Student_Form.formId)
                    LEFT JOIN Student_Question ON Teacher_ControlForm.formId = Student_Question.formId WHERE Teacher_ControlForm.userId=:Student";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Student', $Username);
        $stmt->execute();

        //second delete primary key of Teacher_ControlForm
        $query = "DELETE FROM Teacher_ControlForm WHERE userId =:Student";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Student', $Username);
        $stmt->execute();

        //last delete primary key of Student_Account
        $query = "DELETE FROM Account WHERE userId=:Student";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Student', $Username);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return "刪除成功";
        else
            return "刪除失敗, 請聯絡網頁管理員";
    }

    /**
     * 重置密碼
     * @param mixed $Username 帳號(學號)
     * @return 訊息
     */
    public function resetPasswd($Username)
    {
        $Username = parent::SQL_Injection_check($Username);
        $Password = parent::SQL_Injection_check(_Default::StringEncryption($this->randomStringGenerate(12, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')));
        $query = "UPDATE Account SET passWord=:pwd, firstLogin=1 WHERE userId=:user";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user', $Username);
        $stmt->bindParam(':pwd', $Password);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return "重置成功";
        else
            return "重置失敗, 請聯絡網頁管理員";
    }

    /**
     * 重置管理者密碼密碼
     * @param mixed $org_pwd 原始密碼
     * @param mixed $new_pwd 新密碼
     * @return 1 更新密碼成功
     * @return 0 原始密碼錯誤
     * @return -1 SQL錯誤
     */
    public function changeMPwd($org_pwd, $new_pwd)
    {
        $Username = $_SESSION['userid'];
        $Org_Password = parent::SQL_Injection_check($org_pwd);
        $New_Password = parent::SQL_Injection_check($new_pwd);
        $New_Password = _Default::StringEncryption($New_Password);
        $query = "SELECT passWord FROM Account WHERE userId=:user";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user', $Username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pwd = _Default::StringDecryption($row['passWord']);
            if ($pwd == $Org_Password && $pwd) {
                $query = "UPDATE Account SET passWord=:pwd WHERE userId=:user";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':user', $Username);
                $stmt->bindParam(':pwd', $New_Password);
                $stmt->execute();
                if ($stmt->rowCount() > 0)
                    return 1;
                else
                    return -1;
            } else
                return 0;
        } else
            return -1;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $manager = new ManageUser($db);
    $action = $_POST['action_name'];

    if ($action == "addUser") {
        $Name = $_POST['name'];
        $Username = $_POST['userid'];
        $Email = $_POST['email'];

        echo $manager->addUser($Name, $Username, $Email);
    } elseif ($action == "delUser") {

        $Username = $_POST['userid'];
        for ($i = 0; $i < count($Username); $i++) {
            echo '第' . $i . '筆 ' . $Username[$i] . ' ' . $manager->delUser($Username[$i]) . '-';
        }
    } elseif ($action == "resetPasswd") {

        $Username = $_POST['userid'];
        echo $manager->resetPasswd($Username);
    } elseif ($action == "sendMail") {

        $ids = $_POST['sendIds'];
        $id_array = preg_split('/,/', $ids);

        for ($i = 0; $i < count($id_array); $i++)
            if ($id_array[$i] == "")
                unset($id_array[$i]);

        $msg = "";
        foreach ($id_array as $id) {
            $ret = $manager->sendPwdMail($id);
            if ($ret != "true")
                $msg .= $ret . "\n";
        }
        if ($msg != "")
            echo $msg;
    } else if ($action == "ExcelAddUser") {
        if (isset($_POST['user_list']))
            echo $manager->addMoreUser($_POST['user_list']);
    } else if ($action == "changeMPwd") {
        if (isset($_POST['org_pwd']) && isset($_POST['new_pwd']))
            echo $manager->changeMPwd($_POST['org_pwd'], $_POST['new_pwd']);
    }
}
