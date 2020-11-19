<?php
require_once('class/Class_ForgetPwd.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['id']) && isset($_GET['k']) && isset($_GET['q'])) {
        $resetPwd = new forgetPwd($db);
        if(!$resetPwd->expiresCheck($_GET['q'])) {
            header('Location: index.php?error=1');
        }
        if($resetPwd->checkKey($_GET['k']))
            $resetPwd->showResetPannel($_GET['id'], $_GET['k']);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>登入</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Animated Background Headers | Demo 3</title>
    <meta name="description" content="Examples for creative website header animations using Canvas and JavaScript" />
    <meta name="keywords" content="header, canvas, animated, creative, inspiration, javascript" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="background/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="background/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="background/css/component.css" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:200,400,800|Londrina+Outline' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="icon" href="images/NFU_Logo.svg .ico" type="image/x-icon" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>
    .hid {
        display: none;
    }
</style>
<script>
    function key() {
        if (event.keyCode == 13){
            if(!$("#send_email").hasClass("hid"))
                document.getElementById("submit").click();
            else if (typeof pannel !== 'undefined')
                document.getElementById("changePwd").click();
        }
    }

    window.onload = function(){
        if (typeof pannel !== 'undefined') {
            pannel();
        } else {
            $("#send_email").removeClass("hid");
        }
    }

    function send_email(email) {
        email = email.value;
        if (!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
            alert("錯誤的Email格式!");
            return false;
        }
        $(':button').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: 'class/Class_ForgetPwd.php',
            data: {
                action_name: 'resetPwd',
                email: email
            },
            success: function(res) {
                $(':button').prop('disabled', false);
                if (res == -1) {
                    alert('此emil尚未註冊, 請聯絡管理員');
                    return false;
                } else if (res == 0) {
                    alert('email寄送失敗, 請聯絡管理員');
                    return false;
                } else {
                    document.getElementById("forget_pwd").classList.add("hid")
                    document.getElementById("check_email").classList.remove("hid")
                }
            }
        })
    }
</script>

<body class="container" style="height:100%;" onkeydown="key()">
    <div>
        <div class="content">
            <div id="large-header" class="large-header" style="z-index: -1;position: absolute;top:0;left:0;background:white">
                <canvas id="demo-canvas"></canvas>
            </div>

            <!-- 輸入認證信箱畫面 -->
            <div id="send_email" class="row hid" style="margin-top:5%;height: 400px;">
                <div class="col-sm-4"></div>
                <div id="forget_pwd" class="col-sm-4 " style="text-align: center; height: 100%;">
                    <div class="col-sm-12">
                        <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/7/75/NFU_Logo.svg/1200px-NFU_Logo.svg.png" width="80px" height="60px">
                    </div>
                    <div class="col-sm-12">
                        <h2 style="font-weight:bold;">密碼重置</h2>
                        <br>
                    </div>
                    <div class="col-sm-12">
                        輸入驗證信箱，我們將向您發送密碼重置連結，請確保信箱實際存在
                    </div>
                    <div class="col-sm-12" style="margin-top:5%">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input id="email" style="border-top-right-radius:5px;border-bottom-right-radius:5px;"  type="text" class="form-control" name="userid" placeholder="請輸入電子郵件" >
                                    <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
                                </div>
                                <br>
                        <button id="submit" onclick="send_email(email)" type="button" style="width: 100%;" class="btn-success btn">發送密碼重置信</button>
                    </div>
                    <div class="col-sm-12" style="text-align: right;">
                        <br>
                        <a href="index.php" style="color: black;">返回登入</a>&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: black;" href="https://www.nfu.edu.tw/zh/"> nfu_首頁</a>
                    </div>
                </div>
                <!-- 認證信件寄出畫面 -->
                <div id="check_email" class="col-sm-4 hid" style="text-align: center; height: 100%;">
                    <div class="col-sm-12">
                        <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/7/75/NFU_Logo.svg/1200px-NFU_Logo.svg.png" width="80px" height="60px">
                    </div>
                    <div class="col-sm-12">
                        <h2 style="font-weight:bold;">重置信件寄出</h2>
                        <br>
                    </div>
                    <div class="col-sm-12">
                        檢查你的電子郵件是否獲取重置密碼連結，如果數分鐘後仍未收到，請確認驗整信箱是否輸入正確或是檢查垃圾郵件
                    </div>
                    <div class="col-sm-12" style="margin-top:5%">
                        <a href="forgetpwd.php" id="submit" type="submit" style="width: 100%;" class="btn-success btn">重新發送密碼重置信</a>
                        <a href="index.php" id="submit" type="submit" style="width: 100%;margin-top:1%;" class="btn-success btn">返回登入介面</a>
                    </div>
                </div>
            </div>
            <!-- 重置密碼畫面 -->
            <div id="table" class="row" />
        </div>
    </div><!-- /container -->

</body>

</html>