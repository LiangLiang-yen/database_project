<?php

require_once("Class_Default.php");

$uri = explode("/",$_SERVER["REQUEST_URI"]);
if ($uri[count($uri)-1] == "connectDB.php")
    header('Location: index.php');


$user="dbuser";
$pwd="dbuser123";
$host="localhost";
$db_name="dbPrj";

$dsn="mysql:host=$host;dbname=$db_name";
try
{
    $db=new \PDO($dsn,$user,$pwd);
    $db->query("SET NAMES 'utf8'");
}
catch(\PDOException $e)
{
    $db=null;  //斷開連結
    exit;
}

class forgetPwd extends _Default
{

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function showResetPannel($id, $k)
    {
        echo '<script type="text/javascript">
        var id = "' . $id . '";
        var k = "' . $k . '";

        function pannel(){
        $("#table").html(\'<div class="col-sm-4"></div><div id="rest_pwd" class="col-sm-4" style="text-align: center; height: 100%;margin-top:5%">\
        <div class="col-sm-12">\
            <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/7/75/NFU_Logo.svg/1200px-NFU_Logo.svg.png" width="80px" height="60px">\
        </div>\
        <div class="col-sm-12">\
            <h2 style="font-weight:bold;">密碼重置</h2>\
            <br>\
        </div>\
        <div class="col-sm-12">請輸入新密碼</div>\
        <div class="col-sm-12" style="margin-top:5%">\
            <div id="pwdInput" class="input-group col-sm-12">\
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>\
                <input id="pwd" style="border-top-right-radius:5px;border-bottom-right-radius:5px;" type="password" maxlength="50" class="form-control" placeholder="請輸入新密碼" >\
                <span id="icon" style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>\
            </div>\
            <br>\
            <div id="pwdcheckInput" class="input-group col-sm-12">\
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>\
                <input id="pwdcheck" style="border-top-right-radius:5px;border-bottom-right-radius:5px;" type="password" maxlength="50" class="form-control" placeholder="新密碼確認" >\
                <span id="icon" style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>\
            </div>\
            <br>\
            <button id="changePwd" onclick="setPwd()" type="button" style="width: 100%;" class="btn-success btn">密碼修改確認</button>\
            </div>\
        </div>\
        </div>\');
        }
            function setPwd() {
        var error = false;
        var pwd = document.getElementById("pwd").value;
        var pwdcheck = document.getElementById("pwdcheck").value;

        $("#pwdInput").addClass("col-sm-12");
        $("#pwdInput").removeClass("has-error has-feedback");
        $("#pwdcheckInput").addClass("col-sm-12");
        $("#pwdcheckInput").removeClass("has-error has-feedback");
        $("#icon").css("display", "none");

        if (!pwd) { //check NULL
            $("#pwdInput").removeClass("col-sm-12");
            $("#pwdInput").addClass("has-error has-feedback");
            $("#pwdInput #icon").css("display", "block");
            error = true;
        }

        if (!pwdcheck) { //check NULL
            $("#pwdcheckInput").removeClass("col-sm-12");
            $("#pwdcheckInput").addClass("has-error has-feedback");
            $("#pwdcheckInput #icon").css("display", "block");
            error = true;
        }

        if (pwd !== pwdcheck && !error) { //check first and second password are match
            $("#pwdcheckInput").removeClass("col-sm-12");
            $("#pwdcheckInput").addClass("has-error has-feedback");
            $("#pwdcheckInput #icon").css("display", "block");
            error = true;
        }

        if(error)
            return;

        $(":button").prop("disabled", true);
        $.ajax({
            type:"post",
            url:"/class/Class_ForgetPwd.php",
            data:{
                id:id,
                k:k,
                pwd:pwd
            },
            success:function(res) {
                $(":button").prop("disabled", false);
                switch(res){
                case "1": //更新成功
                    alert("密碼重設成功, 請用新密碼登入");
                    window.location.href = "index.php";
                    break;
                case "-1": //更新失敗
                    alert("更新失敗, 請聯絡管理員");
                    break;
                case "2": //無此帳號
                    alert("更新失敗, 查無此帳號, 請聯絡管理員");
                    break;
                }
            }
        })
    }
        </script>';
    }

    public function resetPasswd($userid, $key, $pwd)
    {
        $userid = $this->SQL_Injection_check($userid);
        $email = $this->SQL_Injection_check($this->StringDecryption($key));
        $pwd = $this->SQL_Injection_check(_Default::StringEncryption($pwd));

        $query = "SELECT * FROM Account WHERE userId=:USERID AND email=:email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':USERID', $userid);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $query = "UPDATE Account SET passWord=:password WHERE userId=:USERID";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':password', $pwd);
            $stmt->bindParam(':USERID', $userid);
            $stmt->execute();

            if ($stmt->rowCount() > 0)
                return "1";
            else
                return "-1";
        }
        return "2";
    }

    public function checkKey($key)
    {
        $email = $this->StringDecryption($key);
        return preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email);
    }

    public function expiresCheck($q)
    {
        $time = $this->StringDecryption($q);
        if ($time >= time())
            return true;
        else
            return false;
    }

        /**
     * 寄送重設密碼
     */
    function forgetPwd($email)
    {
        $email = $this->SQL_Injection_check($email);
        $query = "SELECT email, userId, name FROM Account WHERE email=:Email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $key = $this->StringEncryption($row['email']);
            $time = $this->StringEncryption(time() + 1800);
            $url = 'http://' . _Default::$hostip . '/forgetpwd.php?id=' . $row['userId'] . '&k=' . $key . '&q=' . $time;

            $to = $row['email'];
            $subject = "密碼重設"; //信件標題
            $msg = '<html><head><linkhref="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap"rel="stylesheet"><title>NFU賃居管理系統</title><style>body{margin:0;padding:0;}div{display:block;}.continer{height:100vh;text-align:center;}.content{font-family:"NotoSansTC",sans-serif;padding:30px;width:550px;height:450px;border-radius:8px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}</style></head>
            <body><div class="continer"><div class="content"><img src="http://nfuaca.nfu.edu.tw/images/%E5%87%BA%E7%89%88%E7%B5%84/%E5%AD%B8%E6%A0%A1logo/nfuLogo_%E7%99%BD%E5%BA%95%E8%97%8D%E5%AD%97.jpg" alt="nfu_Logo">
            <h1>'.$row['name'].'您好：</h3><h2>30分鐘後連結將失效, 請點擊以下連結進行重設密碼</h2><a href="'.$url.'">'.$url.'</a><h3 style="color:red">請勿直接回覆本郵件，此信件為系統自動發送<br></h3></div></div></body></html>';
            $headers = "Content-type:text/html;charset=UTF-8" . "\r\n"; //設定特殊標頭

            if ($this->sendEmail($to, $subject, $msg, $headers))
                return 1;
            else
                return 0;
        } else
            return -1;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resetPwd = new forgetPwd($db);
    if (isset($_POST['action_name']) && isset($_POST['email'])) {
        echo $resetPwd->forgetPwd($_POST['email']);
    }
    if (isset($_POST['id']) && isset($_POST['k']) && isset($_POST['pwd'])) {
        $id = $_POST['id'];
        $k = $_POST['k'];
        $pwd = $_POST['pwd'];

        echo $resetPwd->resetPasswd($id, $k, $pwd);
    }
}
