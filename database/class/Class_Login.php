<?php
require_once("Class_Default.php");
require_once('./connectDB.php');

$uri = explode("/", $_SERVER["REQUEST_URI"]);
if ($uri[count($uri) - 1] == "Class_Login.php")
    header('Location: ../index.php');

class Login extends _Default
{
    private $Username = "";
    private $Password = "";

    /**
     * 建構子
     */
    public function __construct($id, $pw, $db)
    {
        parent::__construct($db);
        $this->Username = $this->SQL_Injection_check($id);
        $this->Password = $this->SQL_Injection_check($pw);
        $this->saveLoginInformation();
    }
    /**
     * 儲存登入資料到session中
     */
    private function saveLoginInformation()
    {
        $_SESSION['userid'] = $this->Username;
    }

    /**
     * 登入
     */
    public function login()
    {
        //查詢資料庫帳號及密碼
        $query = "SELECT * FROM Account WHERE userId=:USERID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':USERID', $this->Username);
        $stmt->execute();
        if ($stmt->rowCount() <= 0) {
            //沒找到帳號
            $GLOBALS['userIdError'] = TRUE;
        } else {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            //判斷帳密是否相同
            $pwd = _Default::StringDecryption($result['passWord']);
            if(($result['userId'] == $this->Username) && ($pwd == $this->Password) && $pwd){
                setcookie("login", "True", time() + 3600, "/");
                if ($result['admin'] == 1) {
                    $_SESSION['identity'] = "teacher";
                    header('Location: teacher/adm.php');
                } else {
                    $_SESSION['identity'] = "student";
                    if ($result['firstLogin'] == TRUE) { //判斷是否為首次登入
                        //首次登入
                        header('Location: student/initInfo.php');
                    } else {
                        //非首次登入
                        header('Location: student/form.php');
                    }
                }
            }else{
                $GLOBALS['userIdError'] = FALSE;
                $GLOBALS['pwdError'] = TRUE;
            }
        }
        $stmt = null;
    }
}
