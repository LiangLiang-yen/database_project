<?php
require_once('../class/Class_InitInfo.php');
require_once('../header.php');
$initInfo = new InitInfo($db);
$nameandid = $initInfo->selectid();
if (!$initInfo->selectfirstLogin()) {
  header('Location: form.php');
}
$email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
$phoneNum = isset($_SESSION['phoneNum']) ? $_SESSION['phoneNum'] : "";

/*unset($_SESSION['student_photo']);
  //呼叫的是POST

  $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
  $phone = isset($_SESSION['phone']) ? $_SESSION['pho
  ne'] : "";
  $student_photo = isset($_SESSION['student_photo']) ? $_SESSION['student_photo'] : "https://www.w3schools.com/bootstrap4/img_avatar1.png";
*/
?>
<!-- 設定基本個人資料
  <head>
    <script>
      var photo  圖片的路徑
      window.onload{   頁面剛載入
          function readURL() 讀取資料夾圖片
      }  
      function Open_ChangeImg_table() 當按下更換大頭貼按鈕時可以開啟更改大頭貼的介面

      function Close_ChangeImg_table() 確定更換大頭貼或取消後關閉更改大頭貼介面

    </script>
  </head>
  <body>
    <div id="ChangeImg_table"> 更改大頭貼的介面
    <div id="student_data"> 學生個人資料介面
  </body>


-->

<html>

<head>
  <title>InitInfo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />
  <script type="text/javascript" src="student_temp.js"></script>
  <link rel="stylesheet" href="student_temp.css" />

  <script>
    var photo = "";
    window.onload = function() {
      image_table_title = "更換大頭貼"
      load_image_table(image_table_title)

      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            photo = e.target.result;
            $('#blah').attr('src', e.target.result);
          }

          reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
      }

      $("#imgInp").change(function() {
        readURL(this);
      });

      $("#student_photo").attr("src", "https://www.w3schools.com/bootstrap4/img_avatar1.png");
    }



    function check() {
      var imgPath = document.getElementById("student_photo").src;
      var gmail = document.getElementById("email").value;
      var phone = document.getElementById("phone").value;
      var password = document.getElementById("pwd").value;
      var passwordcheck = document.getElementById("pwdcheck").value;
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
          stat: 'InitInfo'
        },
        cache: false,
        success: function(res) {
          console.log(res);
          if (res == "Success") {
            var d = new Date();
            d.setTime(d.getTime() + 1000 * 3600);
            var expires = "expires=" + d.toGMTString();
            document.cookie = "login=True; " + expires + '; path=/ ;';
            window.location.href = "form.php";
          } else if (res == "UpddateFail") {
            $("#error").html('更新錯誤，聯絡管理員');

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
                  error_msg += "新密碼為空<br>";
                  break;
                case "3":
                  $("#passwdchk-input").addClass("has-error has-feedback");
                  $("#passwdchk-input .form-control-feedback").css("display", "block");
                  error_msg += "新密碼確認為空<br>";
                  break;
                case "4":
                  $("#passwd-input").addClass("has-error has-feedback");
                  $("#passwd-input .form-control-feedback").css("display", "block");
                  $("#passwdchk-input").addClass("has-error has-feedback");
                  $("#passwdchk-input .form-control-feedback").css("display", "block");
                  error_msg += "新密碼與新密碼不相同<br>";
                  break;
                default:
                  break;
              }
            });
            document.getElementById("error").innerHTML = error_msg;
          }
        }
      });
      //Location.reload()
      return false;
    }
  </script>
  <style>
    .hid {
      display: none;
    }
  </style>
</head>

<body style="height:1200px;">
  <!-- ` <div id="student_data" class="row container mtop" style="width:100%;height: 900px;position:absolute;z-index:1;"> -->
  <div class="col-sm-3" style="width: 35%"></div>
  <div class="col-sm-6">
    <form>
      <div id="info" class="card" style="width:400px">
        <h2 class="bold">個人資訊設定</h2>
        <br>
        <img id="student_photo" class="card-img-top head-shot" name="student_photo" src="" alt="Card image" style="width:400px;">
        <!-- 匯入大頭貼 -->
        <div style="background:whitesmoke;text-align: right;margin-top:5px;margin-top:5px;">
          <button id="img_c" type="button" data-target="#myModal" data-toggle="modal" class=" btn btn-success mtop">更換大頭貼</button>
        </div>
        <!-- 匯入大頭貼區塊 -->

        <div class="card-body">
          <h3 id="name_studentId" class="bold card-title"><?php echo $nameandid; ?></h3>
          <label id="error" style="color:red;"></label>
          <h4 class="card-text">首次登入請填寫基本資料並更改密碼</h4>
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
          <div style="text-align: center;color:red">
            <h5>* 密碼必須小於50位</h5>
          </div>
          <div id="passwd-input" class="input-group col-sm-12">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="pwd" type="password" class="form-control" maxlength="50" name="pwd" placeholder="新密碼">
            <span style="display: none" class="glyphicon glyphicon-remove form-control-feedback"></span>
          </div>
          <br>
          <div id="passwdchk-input" class="input-group  col-sm-12">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input id="pwdcheck" type="password" class="form-control" maxlength="10" name="pwdcheck" placeholder="新密碼確認">
            <span style="display:  none" class="glyphicon glyphicon-remove form-control-feedback"></span>
          </div>
          <br>
          <button type="submit" onclick="return check()" class="btn btn-primary">確認</button>
        </div>
      </div>
    </form>
  </div>
  </div>
  <div class="col-sm-3">
  </div>
  <br>
</body>

</html>