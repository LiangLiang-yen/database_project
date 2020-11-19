<?php
require_once('class/Class_Default.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['action_name'] == "Encrypt")
        echo _Default::StringEncryption($_POST['pwd']);
    if($_POST['action_name'] == "Decrypt")
        echo _Default::StringDecryption($_POST['pwd']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>密碼解析</title>
    <script>
        function crypt(action)
        {
            var pwd = document.getElementById("pwd").value;
            $.ajax({
                type:'post',
                url:'#',
                data:{
                    action_name:action,
                    pwd:pwd
                },
                success:function(res){
                    document.getElementById("output").innerText = res.split('\n')[0];
                }
            })
            return false;
        }
    </script>
    <style>
        .content{
            font-family: "Noto Sans TC", sans-serif;
            padding: 30px;
            border: 1px solid #dadce0;
            width: 550px;
            height: 450px;
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .out{
            padding: 5px 10px 5px 10px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="continer">
        <div class="content">
            <h1 id="forms" class="page-header" style="margin-top: 0">密碼解析</h1>
            <div class="form-group">
                <label>Password</label>
                <input type="text" class="form-control" id="pwd" placeholder="Password">
            </div>
            <div style="width: 100% ;height: 50px">
                <button id="Encrypt" class="btn btn-info" onclick="return crypt('Encrypt')">加密</button>
                <button id="Decrypt" class="btn btn-info" onclick="return crypt('Decrypt')">解密</button>
            </div>
            <div class="form-group">
                <label>Output</label>
                <p class="out" id="output" style="height: 150px"></p>
            </div>
        </div>
    </div>
</body>
</html>