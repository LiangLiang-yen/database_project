<?php
require_once('../class/Class_InitInfo.php');
require_once('../class/Class_StudentHeader.php');
require_once('../header.php');

$initInfo = new InitInfo($db);
$name = $initInfo->select_emai_phoneNum_imagePATH();
$email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
$phoneNum = isset($_SESSION['phoneNum']) ? $_SESSION['phoneNum'] : "";
$imagePATH = isset($_SESSION['imagePATH']) ? $_SESSION['imagePATH'] : "https://www.w3schools.com/bootstrap4/img_avatar1.png";
/*unset($_SESSION['student_photo']);
  //呼叫的是POST
*/
?>
<html>

<head>
  <title>ChangeInfo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="student_temp.js"></script>
        <link rel="stylesheet" href="student_temp.css" />
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

  <script>
    var photo = "";
    window.onload = function() {
      load_temp() //載入導航覽樣板
      image_table_title = "更換大頭貼"
      load_image_table(image_table_title)

      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            photo = e.target.result;
            console.log(photo)
            $('#blah').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
      }

      $("#imgInp").change(function() {
        readURL(this);
      });
    }


    function Close_ChangeImg_table(bt) {
      imgname = document.getElementById("imgInp").value.split("\\")[2] //取得圖片檔名 
      console.log(imgname)
      if (bt.innerText == "確認") {
        document.getElementById("student_photo").setAttribute("src", photo)
      }
      document.getElementById("ChangeImg_table").className = "row hid"
    }

    function check() {
      var imgPath = document.getElementById("student_photo").src;
      var gmail = document.getElementById("email").value;
      var phone = document.getElementById("phone").value;
      var password = document.getElementById("pwd").value;
      var passwordcheck = document.getElementById("pwdcheck").value;
      var beforepassword = document.getElementById("beforepwd").value;
      //console.log(imgPath);

      $.ajax({
        type: "post",
        url: "../class/Class_InitInfo.php",
        data: {
          imgPath: imgPath,
          gmail: gmail,
          phone: phone,
          password: password,
          passwordcheck: passwordcheck,
          beforepassword:beforepassword,
          stat: 'changeinfo'
        },
        cache: false,
        success: function(res) {
          console.log(res);
          if (res == "Success") {
            alert('修改成功');
            window.location.href = "form.php";
          } else if (res == "UpddateFail") {
            alert('修改失敗');
            $("#error").html('更新錯誤，聯絡管理員');
          } else if (res == "NoneedUPDATE") {
            alert('不需修改');
          } else {
            $(".col-sm-12").addClass("col-sm-12");
            $(".col-sm-12").removeClass("has-error has-feedback");
            $(".form-control-feedback").css("display", "none");

            var split = res.split(",");
            var error_msg = "";
            $("#error").html('');
            split.forEach(element => {


              switch (element) {
                case "0":
                  $("#email-input").addClass("has-error has-feedback");
                  $("#email-input .form-control-feedback").css("display", "block");
                  error_msg += "信箱格式錯誤<br>";
                  break;
                case "1":
                  $("#phone-input").addClass("has-error has-feedback");
                  $("#phone-input .form-control-feedback").css("display", "block");
                  error_msg += "電話錯誤<br>";
                  break;
                case "2":
                  $("#passwd-input").addClass("has-error has-feedback");
                  $("#passwd-input .form-control-feedback").css("display", "block");
                  error_msg += "密碼為空<br>";
                  break;
                case "3":
                  $("#passwdchk-input").addClass("has-error has-feedback");
                  $("#passwdchk-input .form-control-feedback").css("display", "block");
                  error_msg += "密碼確認為空<br>";
                  break;
                case "4":
                  $("#passwd-input").addClass("has-error has-feedback");
                  $("#passwd-input .form-control-feedback").css("display", "block");
                  $("#passwdchk-input").addClass("has-error has-feedback");
                  $("#passwdchk-input .form-control-feedback").css("display", "block");
                  error_msg += "密碼更改與密碼更改確認不相同<br>";
                  break;
                case "5":
                  $("#beforepwd-input").addClass("has-error has-feedback");
                  $("#beforepwd-input .form-control-feedback").css("display", "block");
                  error_msg += "原始密碼不相同<br>";
                  break;
                default:
                  break;
              }
            });
            document.getElementById("error").innerHTML = error_msg;
          }
        }
      });
      return false;
    }
  </script>
  <style>
    .hid {
      display: none;
    }
  </style>
</head>

<body style="height:1200px;" class="container">

  <div id="student_data" class="col-sm-12 container mtop" style="margin-top:100px;">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <form>
        <div id="info" class="card" style="width:400px">
          <h2 class="bold">個人資訊設定</h2>
          <br>
          <img id="student_photo" class="card-img-top head-shot" name="student_photo" src=<?php echo $imagePATH ?> alt="Card image" style="width:400px;">
          <div style="background:whitesmoke;text-align: right;">
            <!-- 匯入大頭貼 -->
            <div style="background:white;text-align: right;margin-top:5px;">
              <button type="button" data-target="#myModal" data-toggle="modal" class="btn btn-success mtop">更換大頭貼</button>
            </div>
            <!-- 匯入大頭貼區塊 -->
            <div class="card-body" style="background: white;">
              <br>
              <label id="error" style="color:red;"></label>
              <br>
              <br>
              <div id="email-input" class="input-group col-sm-12">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input id="email" type="email" class="form-control" name="email" placeholder="聯絡信箱" value=<?php echo $email ?>>
                <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
              </div>
              <br>
              <div id="phone-input" class="input-group col-sm-12">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                <input id="phone" type="text" class="form-control" name="phone" placeholder="連絡電話" value=<?php echo $phoneNum  ?>>
                <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
              </div>
              <br>
              <div id="beforepwd-input" class="input-group col-sm-12"> 
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="beforepwd" type="password" class="form-control" maxlength="10" name="beforepwd" placeholder="原始密碼輸入">
                <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
              </div>  
              <br>
              <div id="passwd-input" class="input-group col-sm-12">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="pwd" type="password" class="form-control" maxlength="10" name="pwd" placeholder="密碼更改">
                <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
              </div>
              <br>
              <div id="passwdchk-input" class="input-group  col-sm-12">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="pwdcheck" type="password" class="form-control" maxlength="10" name="pwdcheck" placeholder="密碼更改確認">
                <span style="display:  none" class="glyphicon glyphicon-remove form-control-feedback"></span>
              </div>
              <br>
              <button type="submit" onclick="return check()" class="btn btn-primary">確認</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3"></div>
  </div>

  <br>
</body>

</html>