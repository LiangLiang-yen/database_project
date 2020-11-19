<?php
require_once('./class/Class_Login.php');
$userIdError = FALSE;
$pwdError = FALSE;
global $userIdError, $pwdError;

//呼叫的是POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loginRequest = new Login($_POST['userid'], $_POST['pwd'], $db);
    $loginRequest->login();
}

//讀取登入資訊
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";

//自動登入
if (isset($_COOKIE["login"]) && isset($_SESSION['userid']) && isset($_SESSION['pwd']) && isset($_SESSION['identity'])) {
    $loginRequest = new Login($_SESSION['userid'], $_SESSION['pwd'], $db);
    $loginRequest->login();
}

//連結過期提示
if (isset($_GET['error']))
    echo '<script>alert(\'連結已過期\');</script>';

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
    <link rel="icon" href="images/NFU_Logo.svg .ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        window.onload = function() {

        };

        function key() {
            if (event.keyCode == 13)
                document.getElementById("submit").click();
        }
    </script>
    <style>
    </style>
</head>

<body class="container" style="height:100%;" onkeydown="key()">
    <div>
        <div class="content">
            <div id="large-header" class="large-header" style="z-index: -1;position: absolute;top:0;left:0;background:white">
                <canvas id="demo-canvas"></canvas>
            </div>

            <div class="row" style="margin-top:5%;height: 400px;">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 " style="text-align: center; height: 100%;">
                    <dvi class="col-sm-12">
                        <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/7/75/NFU_Logo.svg/1200px-NFU_Logo.svg.png" width="80px" height="60px">
                    </dvi>
                    <div class="col-sm-12">
                        <h2 style="font-weight:bold;">NFU-賃居表單系統</h2>
                        <br>
                    </div>
                    <form action="#" method="post">
                        <div class="input-group <?php if ($userIdError) echo " has-error has-feedback"; else echo " col-sm-12"; ?>">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="userid" style="border-top-right-radius:5px;border-bottom-right-radius:5px;" type="text" class="form-control" name="userid" placeholder="userid" value="<?php echo $userid; ?>">
                            <span style="display: <?php if ($userIdError) echo " block";
                                                    else echo " none"; ?>;" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        </div>
                        <br>
                        <div class="input-group <?php if ($pwdError) echo " has-error has-feedback"; else echo " col-sm-12"; ?>">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="pwd" style="border-top-right-radius:5px;border-bottom-right-radius:5px;" type="password" class="form-control" name="pwd" placeholder="Password">
                            <span style="display: <?php if ($pwdError) echo " block";
                                                    else echo " none"; ?>;" class="glyphicon glyphicon-remove form-control-feedback"></span>
                        </div>
                        <br>
                        <button id="submit" type="submit" style="width: 100%;" class="btn-info btn">登入</button>
                    </form>
                    <div class="col-sm-12" style="text-align: right;">
                        <br>
                        <a href="forgetpwd.php" style="color: black;">忘記密碼</a>&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: black;" href="https://www.nfu.edu.tw/zh/"> nfu_首頁</a>
                    </div>
                </div>
                <div class="col-sm-3"></div>

            </div>



        </div>
    </div><!-- /container -->

    <script src="background/js/TweenLite.min.js"></script>
    <script src="background/js/EasePack.min.js"></script>
    <script src="background/js/rAF.js"></script>
    <script src="background/js/demo-3.js"></script>
</body>

</html>