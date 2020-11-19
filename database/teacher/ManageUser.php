<?php
require_once('../class/Class_TeacherHeader.php');
require_once('../class/Class_ManageUser.php');
require_once('../header.php');
?>

<!-- 管理者管理使用者介面
  <head>
    導航覽樣式js檔
    <script type="text/javascript" src="student_temp.js"></script>
      
    <script>
      var student_names = [] 使用者名稱陣列
      var student_IDs = [] 使用者學號陣列
      var student_idCards = [] 使用者身分證字號
      var student_list_content 要載入html的內容

      function student_list_load() 載入學生清單

      window.onload() 頁面剛載入所進行的操作
      { 
        load_temp() 載入導航覽樣版 src="student_temp.js"
        student_list_load() 載入現有的學生資料至使用者資料列表 id="student_list"
      }


      function  add_user() 新增使用者
      
      function  del_user() 刪除使用者
      
      function  comf() 確認新增

      function  cancel() 取消新增使用者
    </script>
  </head>
	<body>
		
		<bt id="Add" onclick="add_user()"> 新增使用者按鈕
		<bt id="Del" onclick="del_user()"> 刪除使用者按鈕
    <bt id="Excel"> 匯入Excel按鈕
    <label id="sql_tag" > 顯示sql
    
    <div id="del_info" class="hid"> 包含3個input 存放姓名、學號、密

		<ul id="student_list"> 使用者資料列表
		</ul>		
  </body>
 -->

<!DOCTYPE html>
<html lang="en">

<head>
  <title>form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="xlsx.full.min.js"></script>
  <script type="text/javascript" src="teacher_temp.js"></script>
  <link rel=stylesheet type="text/css" href="teacher_temp.css">
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

  <style>
    .mtop {
      margin-top: 1%;
    }

    .hid {
      display: none;
    }

    .block {
      display: block;
    }
      
    .is-invalid{
      border-color: #dc3545;
      padding-right: calc(1.5em + .75rem);
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right calc(.375em + .1875rem) center;
      background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }
  </style>
  <script>
            /*
            FileReader共有4種讀取方法：
            1.readAsArrayBuffer(file)：將檔案讀取為ArrayBuffer。
            2.readAsBinaryString(file)：將檔案讀取為二進位制字串
            3.readAsDataURL(file)：將檔案讀取為Data URL
            4.readAsText(file, [encoding])：將檔案讀取為文字，encoding預設值為'UTF-8'
                         */
            var wb;//讀取完成的資料
            var rABS = false; //是否將檔案讀取為二進位制字串
            var student = [];
            function importf(obj) {//匯入
                if(!obj.files) {
                    return;
                }
                var f = obj.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var data = e.target.result;
                    if(rABS) {
                        wb = XLSX.read(btoa(fixdata(data)), {//手動轉化
                            type: 'base64'
                        });
                    } else {
                        wb = XLSX.read(data, {
                            type: 'binary'
                        });
                    }
                    //wb.SheetNames[0]是獲取Sheets中第一個Sheet的名字
                    //wb.Sheets[Sheet名]獲取第一個Sheet的資料
                    excel_content = JSON.stringify( XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]) );
                    excel_content = excel_content.split("{")
                    student = [];
                    var content = ""
                    for(var i = 0;i < excel_content.length;i++){
                      if(i+1 >= excel_content.length)
                        break;
                      row = excel_content[i+1].split(',')
                      var id = row[0].split(":")[1].split("\"").join("")
                      var name = row[1].split(":")[1].split("\"").join("")
                      var email = row[2].split(":")[1].split("\"").join("").replace("}","").replace("]","")
                      student[i] = {id : id, name : name, email : email}

                      content += '<div class="row" style="margin:1% 1% 0 1%;font-size:16px;"><div class="col-sm-2"></div><div class="col-sm-2">' + student[i].name + '</div>'
                      content += '<div class="col-sm-2">' + student[i].id + '</div>'
                      content += '<div class="col-sm-3">' + student[i].email + '</div></div>'
                    }
                    document.getElementById("show_excel").innerHTML = content
                };
                if(rABS) {
                    reader.readAsArrayBuffer(f);
                } else {
                    reader.readAsBinaryString(f);
                }
                console.log(wb)
            }

            function fixdata(data) { //檔案流轉BinaryString
                var o = "",
                    l = 0,
                    w = 10240;
                for(; l < data.byteLength / w; ++l) o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w, l * w + w)));
                o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w)));
                return o;
            }
            function sendExcelAddUser()
            {
              if (student.length > 0) {
                $.ajax({
                  type:'post',
                  url:'../class/Class_ManageUser.php',
                  data:{
                    action_name: "ExcelAddUser",
                    user_list: student
                  },
                  success:function(res){
                    if (res){
                      alert('新增成功');
                    } else {
                      alert('新增失敗, 請聯絡網頁管理員');
                    }
                    location.reload();
                  }
                })
              }
            }
  </script>
  <script type="text/javascript">
  var org = $("#notif").offset().top;
    function show_msg(text, color="green"){
      $("#sql_tag").css({color: color});
      $("#sql_tag").html(text);
      $("#notif").fadeIn(1000);
  
      setTimeout(function(){$("#notif").fadeOut(2000);},3000);
    }
    function addUser() {
      var name = document.getElementById("student_name").value;
      var userid = document.getElementById("student_ID").value;
      var email = document.getElementById("student_email").value;
      var action = "addUser";

      if (userid == null) {
        alert("學號不可為空白");
        return false;
      }
      if (!email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        alert("錯誤的Email格式!");
        return false;
      }

      if (userid && email) {
        $.ajax({
          type: "post",
          url: "../class/Class_ManageUser.php",
          data: {
            name: name,
            userid: userid,
            email: email,
            action_name: action
          },
          cache: false,
          success: function(res) {
            alert(res);
            location.reload();
            enable_all_option()
          }
        });
      }

      return false;
    }

    function deleteUser(user) {
      var action = "delUser";
      if (user) {
        $.ajax({
          type: "post",
          url: "../class/Class_ManageUser.php",
          data: {
            userid: user,
            action_name: action
          },
          cache: false,
          success: function(res) {
            alert(res.replace(/-/g, "\r"));
            location.reload();
          }
        });
      }

      return false;
    }

    function resetPass(user) {
      var action = "resetPasswd";
      if (user) {
        $.ajax({
          type: "post",
          url: "../class/Class_ManageUser.php",
          data: {
            userid: user,
            action_name: action
          },
          success: function(res) {
            show_msg(res);
          }
        })
      }
    }

    function sendmail(ids){
      var action = "sendMail";
      ids = ids.trim();
      ids = ids.split(' ');
      console.log(ids);
      
      //check Id is number
      for (var i = 0; i < ids.length; i++) 
        if(!ids[i].match(/^\d+$/)){
          alert('無法解析正確的寄送名單, 請聯絡網頁管理員')
          return false;
        }
      
      send = ids[0]; //post data
      for(var i = 1; i < ids.length; i++)
        send += "," + ids[i];

      if(send){
        $("#sql_tag").html("此動作需要耗費較久的時間, 請耐心等候...");
        $("#notif").fadeIn(1000);

        $.ajax({
          type:"post",
          url: "../class/Class_ManageUser.php",
          data: {
            sendIds: send,
            action_name: action
          },
          success: function(res){
            if(res == "")
              show_msg("寄送完成");
            else
              show_msg(res);

            var checkBoxs = document.getElementsByClassName("check_send_email");
            var bt_email = document.getElementById("email");
            for(var i = 0;i < checkBoxs.length;i++){
              checkBoxs[i].classList.add("hid")
            }
            document.getElementsByClassName("send_all_email")[0].classList.add("hid"); //隱藏 checked 全選按鈕
            document.getElementById("send_email_group").classList.add("hid");
            bt_email.classList.remove("hid");
            enable_all_option();
          }
        });
      }
      return false;
    }

    function excel_import() {
      var action = "delUser";
      return false;
    }
  </script>
  <script>
    
    window.onload = function() {
      load_temp();
      student_list_load();
      $("#user").html("<?php echo $_SESSION["userid"];?>");
      $(".page_li").eq(0).addClass("disabled");
      $(".page_li").eq(1).addClass("active");
    }

    function student_list_load() {
      <?php $manager = new ManageUser($db); $manager->student_list_load();?>
      var student_list = document.getElementById("student_list");
      student_list.innerHTML = student_list_content;
    }

    function add_user(bt) {
      disabled_other_option(bt)
      bt.setAttribute("disabled", "disabled")
      var list = document.getElementById("student_list")
      var li = document.createElement("li");
      li.setAttribute("class", "mtop list-group-item list-group-item-light")
      content = ""
      content += '<div class="row">\
                  <div class="col-sm-12">\
                    <form>\
                      <div class="input-group">\
                        <span  class="input-group-addon">姓名</span>\
                        <input id="student_name" type="text" class="form-control" maxlength="10" name="name" placeholder="Additional Info">\
                      </div>\
                      <div class="input-group mtop">\
                        <span class="input-group-addon">學號</span>\
                        <input id="student_ID" type="text" class="form-control" maxlength="8" name="userid" placeholder="Additional Info">\
                      </div>\
                      <div class="input-group mtop">\
                        <span class="input-group-addon">信箱</span>\
                        <input id="student_email" type="text" class="form-control" maxlength="50" name="pwd" placeholder="Additional Info">\
                      </div>\
                      <div class="mtop">\
                        <button id="add_user" type="submit" onclick="return addUser()" class="btn btn-info">新增</button>\
                        <button onclick="cancel()" type="button" class="btn btn-info">取消</button>\
                      </div>\
                    </form>\
                  </div>\
              </div>'
      li.innerHTML = content
      list.insertBefore(li,list.childNodes[0])
    }
    var Del_flag = -1; //判斷是第幾次按下刪除 
    function del_user(state) {
      deluser_bt = document.getElementById("Del")
      var delicon = document.getElementsByClassName("check_del_user")
      switch(state)
      {
        case 'Del':
          disabled_other_option(deluser_bt)
          document.getElementById("del_user_group").classList.remove("hid")
          document.getElementById("del_user_group").getElementsByTagName('button')[0].disabled=false
          document.getElementById("del_user_group").getElementsByTagName('button')[1].disabled=false
          deluser_bt.classList.add("hid")
          document.getElementsByClassName("del_all_user")[0].classList.remove("hid")
          for (var i = 0; i < delicon.length; i++) {
            delicon[i].classList.remove("hid")
          }
          break
          
        case 'confirm_del': //確認刪除使用者
          document.getElementById("check_all_deluser").checked = false
          var send_queue = "" //儲存想要刪除的學生名字
          var send_studentId = []//儲存要刪除的學生學號
          var students = document.getElementsByClassName("name")
          var studentIds = document.getElementsByClassName("userid")
          for(var i = 0;i < delicon.length;i++){
            if(delicon[i].checked){
              send_queue += students[i].innerHTML + "\n"
              send_studentId.push(studentIds[i].innerText)
            }
          }
          var result = send_queue.length > 0 ? confirm("確定刪除名單:\n" + send_queue) : alert("無選擇刪除對象")
            if(result){//確定刪除
              deleteUser(send_studentId); 
              student_list_load()
            }else{//取消刪除
              
            }
            send_flag = 0
          break

        case 'cancel_del': //取消刪除使用者
          document.getElementsByClassName("del_all_user")[0].classList.add("hid")
          document.getElementById("del_user_group").classList.add("hid")
          document.getElementById("del_user_group").getElementsByTagName('button')[0].disabled=true
          document.getElementById("del_user_group").getElementsByTagName('button')[1].disabled=true
          deluser_bt.classList.remove("hid")
          enable_all_option()
          for (var i = 0; i < delicon.length; i++) {
            delicon[i].classList.add("hid")
          }

          break
      }
    }

      function cancel()
      {
        enable_all_option()
        student_list_load() 
      }
      var send_flag = 0 // 紀錄 寄送帳號啟用通知信 按鈕是第幾次被按下
      
      //寄送帳號啟用通知信 按下觸發 
    function send_checkBox(state){
        bt_email = document.getElementById("email")
        var checkBoxs = document.getElementsByClassName("check_send_email")
        switch (state){
          //展開送出email
          case 'email':
            disabled_other_option(state)
            document.getElementsByClassName("send_all_email")[0].classList.remove("hid") //checked 全選按鈕
            document.getElementById("send_email_group").classList.remove("hid") //顯示按鈕組 
            document.getElementById("send_email_group").getElementsByTagName("button")[0].disabled=false //啟用 確認寄送按鈕
            document.getElementById("send_email_group").getElementsByTagName("button")[1].disabled=false //啟用 取消寄送按鈕
            for(var i = 0;i < checkBoxs.length;i++){
              checkBoxs[i].classList.remove("hid")
            }
            bt_email.classList.add("hid")
            break
          //確認寄送email
          case 'confirm_send':
            var send_queue = "" //儲存想要寄出的學生名字
            var send_studentId = ""//儲存要寄出的學生學號
            var students = document.getElementsByClassName("name")
            var studentIds = document.getElementsByClassName("userid")
            for(var i = 0;i < checkBoxs.length;i++){
              if(checkBoxs[i].checked){
                send_queue += students[i].innerHTML + "\n"
                send_studentId += studentIds[i].innerHTML + " "
              }
            }
            var result = send_queue.length > 0 ? confirm("確定寄出名單:\n" + send_queue) : alert("無選擇寄出對象")
            if(result){//確定寄出
              sendmail(send_studentId);
            }else{//取消寄出
              
            }
            send_flag = 0
            break
          //取消寄送email
          case 'cancel_send':
            for(var i = 0;i < checkBoxs.length;i++){
              checkBoxs[i].classList.add("hid")
            }
            document.getElementsByClassName("send_all_email")[0].classList.add("hid") //隱藏 checked 全選按鈕
            document.getElementById("send_email_group").classList.add("hid")
            bt_email.classList.remove("hid")
            enable_all_option()
            break
        }
    }
    var check_all_flag = 0 // 判斷送出email的全選按鈕checkbox是第幾次被案下

    //判斷送出email的全選按鈕checkbox是否被按下
    function check_all(check){ 
      if(check.id == "check_all_email")
      {
        var checkBoxs = document.getElementsByClassName("check_send_email")
        if(check.checked)
        {
          for(var i = 0;i < checkBoxs.length;i++){
            checkBoxs[i].checked = true
          }
        }else{
          for(var i = 0;i < checkBoxs.length;i++){
            checkBoxs[i].checked = false
          }
        }
      }else if(check.id == "check_all_deluser"){
        var checkBoxs = document.getElementsByClassName("check_del_user")
        if(check.checked)
        {
          for(var i = 0;i < checkBoxs.length;i++){
            checkBoxs[i].checked = true
          }
        }else{
          for(var i = 0;i < checkBoxs.length;i++){
            checkBoxs[i].checked = false
          }
        }
      }


    }

    //關閉其他功能按鈕 避免產生error
    function disabled_other_option(bt){
      options = document.getElementById("bt_options").getElementsByTagName("button")
      for(var i = 0;i < options.length;i++){
          options[i].id == bt.id ? 1 : options[i].disabled=true;
      }
    }

    function enable_all_option(){
      options = document.getElementById("bt_options").getElementsByTagName("button")
      for(var i = 0;i < options.length;i++){
          options[i].disabled=false;
      }
    }
    
    function changeMPwd()
    {
      $("#org_pwd").removeClass("is-invalid");
      $("#new_pwd").removeClass("is-invalid");
      $("#sec_pwd").removeClass("is-invalid");

      var org = $("#org_pwd").val();
      var new_ = $("#new_pwd").val();
      var sec = $("#sec_pwd").val();

      if(org == "")
      {
        $("#org_pwd").addClass("is-invalid");
        return false;
      }

      if(new_ == "")
      {
        $("#new_pwd").addClass("is-invalid");
        return false;
      }

      if(new_ != sec)
      {
        $("#sec_pwd").addClass("is-invalid");
        return false;
      }

      $.ajax({
          type:"post",
          url: "../class/Class_ManageUser.php",
          data: {
            new_pwd: new_,
            org_pwd: org,
            action_name: "changeMPwd"
          },
          success: function(res){
            if(res == 1){
              alert("密碼修改成功! 請重新登入");
              window.location.href = "../logout.php";
            }else if(res == 0){
              $("#org_pwd").addClass("is-invalid");
            }else{
              alert("Sql server error");
            }
          }
        });
        return false;
    }
    var pagenumber = 1;
    function change_page(li_active,page_number)
    {
      var pages = document.getElementsByClassName("page")
      var lis = document.getElementsByClassName("page_li")

      $(lis).removeClass("active disabled")

      if(page_number == "next")
      {
        if(pagenumber != lis.length - 2)
          pagenumber++
      }else if(page_number == "pre")
      {
        if(pagenumber != 1)
          pagenumber--
      }else{
        pagenumber = page_number
      }

      if(pagenumber == lis.length - 2)
        $(lis).last().addClass("disabled")
      else if(pagenumber <= 1)
        $(lis).first().addClass("disabled");
      for(var i = 0 ;i < pages.length;i++)
      {
        pages[i].classList.add("hid")
      }
      document.getElementById("page"+pagenumber).classList.remove("hid")
      $(lis[pagenumber]).addClass("active")
    }
  </script>
</head>

<body>
  <div class="container mtop" style="margin-top:100px;">
      <div style="margin-bottom: 6px;" id="bt_options">
          <h3>使用者管理</h3>
          <button id="Add" onclick="add_user(this)" type="button" class="btn btn-info mtop">增加使用者</button>
          <button id="Del" onclick="del_user('Del')" type="button" class="btn btn-Warning mtop">刪除使用者</button>
          <div class="btn-group mtop hid" id="del_user_group">
            <button type="submint" onclick="del_user('confirm_del')" class="btn btn-success ">確認刪除使用者</button>
            <button type="submint" onclick="del_user('cancel_del')" class="btn btn-danger ">取消刪除</button>
          </div>
          <button id="Import_Excel" type="file" data-target="#myModal" role="button" data-toggle="modal" class=" btn btn-success mtop">Excel 匯入</button>
          <button id="Excel" type="submit" onclick="location.href = '../帳戶匯入_範本_請勿修改表格欄位.xlsx';" class=" btn btn-info mtop">Excel 匯入範本</button>
          <button id="email" type="submit" onclick="send_checkBox('email')" class="btn btn-danger mtop">寄送帳號啟用通知信</button>
          
          <div class="btn-group mtop hid" id="send_email_group">
            <button type="submint" onclick="send_checkBox('confirm_send')" class="btn btn-success ">確認寄送</button>
            <button type="submint" onclick="send_checkBox('cancel_send')" class="btn btn-danger ">取消寄送</button>
          </div>
          <button type="submit" data-target="#loginModal" role="button" data-toggle="modal" class="btn btn-Warning mtop">修改管理者密碼</button>

          &nbsp;&nbsp;&nbsp;<span ><img id="spin" style="width: 35px;height:35px; display:none;" src="../images/Spin.gif"></span>
          &nbsp;&nbsp;&nbsp;<label  style="font-size: 16px;" class="hid send_all_email"><input id="check_all_email" onclick="check_all(this)" type="checkbox" style="width:20px;height:20px;vertical-align:middle;" value="">&nbsp;&nbsp;全選</label>
          &nbsp;&nbsp;&nbsp;<label  style="font-size: 16px;" class="hid del_all_user"><input id="check_all_deluser" onclick="check_all(this)" type="checkbox" style="width:20px;height:20px;vertical-align:middle;" value="">&nbsp;&nbsp;全選</label>
          &nbsp; <span id="notif" class="hid" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; position:absolute"><i style="color:green; font-size:10px; margin-right:5px" class="glyphicon glyphicon-info-sign" aria-hidden="true"></i><strong style="margin-bottom:0" id="sql_tag"></strong></span>
      </div>
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:100%"> 
          <div class="modal-dialog">
          <div class="modal-content" >
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel">匯入Excel檔案</h3>
            </div>
            <div class="modal-body row">
              <div class="col-sm-12">
                <input type="file" onchange="importf(this)" >
              </div>
              <div id="show_excel" class="col-sm-12" style="height: 600px;margin:1% 5% 1% 5%; width:90%;overflow:auto;border:solid 1px gray;">
              
              </div>
                
            </div>
            <div class="modal-footer">
              <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
              <button class="btn btn-primary" onclick="sendExcelAddUser()">確定匯入</button>
            </div>
          </div>
          </div>
      </div>
      <div id="loginModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">CangePassword</h4>
                </div>
                <div class="modal-body">
                    <label>管理者帳號</label>
                    <h5 id="user"></h1>
                    <label>原始密碼</label>
                    <input type="password" id="org_pwd" class="form-control" />
                    <label>新密碼</label>
                    <input type="password" id="new_pwd" class="form-control" />
                    <label>確認新密碼</label>
                    <input type="password" id="sec_pwd" class="form-control" />
                    <br />
                    <button onclick="return changeMPwd()" type="button" name="login_button" id="login_button" class="btn btn-warning">CangePassword</button>
                </div>
            </div>
        </div>
    </div>
          <ul id="student_list" class="list-group">
          </ul>
      </div>
  </div>
  <br>
  <br>
</body>

</html>