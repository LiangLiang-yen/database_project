<?php
    require_once('../class/Class_TeacherHeader.php');
    require_once('../class/Class_FormControl.php');
    require_once('../header.php');
    $formControl = new formControl($db);
    $formControl->select_all_formType();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-quiv = 'cahe-control' content="no-cache">
  <meta http-quiv = 'pragram' content="no-cache">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

  <script type="text/javascript" src="teacher_temp.js"></script>
  <link rel=stylesheet type="text/css" href="teacher_temp.css">

  <style>
    .mtop{
      margin-top: 1%;
    }
    .bg-darkblue{
      background: darkblue;
    }
    .li_click{
      background:#708090;
      color: white;
    }
    .myMOUSE{ 
      cursor: pointer; 
    }
    .hid{
      display: none;
    }
  </style>

</head>
<style>
      .box1{
        border: gray 1px solid;
        line-height: 40px;
        text-align: center;
        font-size: 16px;
      }
      .box2{
        display: inline-block;
        height: 50px;
        margin: 0px 25px 0px 25px; 
      }
      .detail_box{
        height: 300px;
        margin:1% 0 1% 0;
        border: solid 1px black;
        padding: 1px;
        overflow:auto;
      }
      li{
        list-style-type:none;

      }
      body{
        -webkit-touch-callout: none; /* iOS Safari */
          -webkit-user-select: none; /* Safari */
          -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Firefox */
              -ms-user-select: none; /* Internet Explorer/Edge */
                  user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome and Opera */
      }
</style>
<script>
    // var table_data=[["108a",51,50,5,45,"資工三甲","True"],["108b",51,50,5,45,"資工三甲","False"]]
    // var Can_student = [[["40643101","陳信宏"],["40643102","陳信宏"],["40643103","陳信宏"],["40643104","陳信宏"],
    // ["40643105","陳信宏"],["40643106","陳信宏"],["40643107","陳信宏"],["40643108","陳信宏"],["40643109","陳信宏"]
    // ,["40643110","陳信宏"],["40643111","陳信宏"],["40643112","陳信宏"],["40643113","陳信宏"],["40643114","陳信宏"]],
    // [["40643101","陳信宏"],["40643102","陳信宏"],["40643103","陳信宏"],["40643104","陳信宏"],
    // ["40643105","陳信宏"],["40643106","陳信宏"],["40643107","陳信宏"],["40643108","陳信宏"],["40643109","陳信宏"]
    // ,["40643110","陳信宏"],["40643111","陳信宏"],["40643112","陳信宏"],["40643113","陳信宏"],["40643114","陳信宏"]]]
    //   var Not_student = [[["40643115","沒天良"],["40643116","沒天良"]],[["40643115","沒天良"],["40643116","沒天良"]]]
      function set_CanFillStudent(num){
        var Not_box = document.getElementById("Not_box"+num)
        var Not_li = Not_box.getElementsByClassName("row-item")
        for(var i =0;i < Not_li.length;i++)
        {
          if(Not_li[i].classList.contains("li_click")){
            var studentId = Not_li[i].getElementsByClassName("studentId")[0].innerHTML;
            for(var j=0 ; j<Not_student[num].length;j++){
              if(Not_student[num][j].studentId.indexOf(studentId) != -1){
                  change = Not_student[num].splice(j,1)
                  Can_student[num].push(change[0])
                  Can_student[num].sort(function(a,b){
                    return a.studentId > b.studentId ? 1 : -1;
                  })      
              }
            }
          }
        }
        refresh_box(num)
      }
      function set_CanNotFillStudent(num){
        var Can_box = document.getElementById("Can_box"+num)
        var Can_li = Can_box.getElementsByClassName("row-item")
        for(var i =0;i < Can_li.length;i++)
        {
          if(Can_li[i].classList.contains("li_click")){
            var studentId = Can_li[i].getElementsByClassName("studentId")[0].innerHTML;
            for(var j=0 ; j<Can_student[num].length;j++){
              if(Can_student[num][j].studentId.indexOf(studentId) != -1){
                    change = Can_student[num].splice(j,1)
                    Not_student[num].push(change[0])
                    Not_student[num].sort(function(a,b){
                      return a.studentId > b.studentId ? 1 : -1;
                    })         
              }
            }
          }
        }
        refresh_box(num)
      }
      function refresh_box(num){
        var group = "";
        group = '<ul style="padding: 0;margin:0;">';
        for(var i = 0; i < Can_student[num].length; i++){
          group += '<li class="row-item">\
                    <ul class="row">\
                    <li class="col-xs-6 studentId">'+ Can_student[num][i].studentId +'</li>\
                    <li class="col-xs-6 name">'+ Can_student[num][i].name +'</li>\
                    </ul>\
                    </li>'
        }
        group += '</ul>';
        document.getElementById("Can_box"+num).innerHTML = group;
        group = '<ul style="padding: 0;margin:0;">';
        for(var i = 0; i < Not_student[num].length; i++){
          group += '<li class="row-item">\
                    <ul class="row">\
                    <li class="col-xs-6 studentId">'+ Not_student[num][i].studentId +'</li>\
                    <li class="col-xs-6 name">'+ Not_student[num][i].name +'</li>\
                    </ul>\
                    </li>'
        }
        group += '</ul>';
        not_box = document.getElementById("Not_box"+num).innerHTML = group;
        set_click()
      }
    function set_click(){
      for(var j = 0;j < table_count;j++)
      {
        var detail = document.getElementById("detail" + j)
        var li = detail.getElementsByClassName("row-item")
        for(var i =0;i < li.length;i++)
        {
          li[i].classList.add("myMOUSE")
          li[i].onclick=function(){
            $(this).toggleClass("li_click");
          }
        }
      }
    }
    function updateWrite(ckb, formType = 0, detail){
      disable_all();
      var bool = ckb.checked ? 1 : 0;
      if(formType){
        $.ajax({
          type:'post',
          url: '../class/Class_FormControl.php',
          data:{
            action:'updateWrite',
            formType:formType,
            checked:bool
          },
          success:function(res){
            enable_all();
            if(res){
              alert('修改成功');
              detail_show(detail);
              detail.classList.remove("hid");
            }
            else{
              alert('修改失敗, 請聯絡網站管理員');
            }
            ckb.checked = !ckb.checked
          }
        })
      }
    }
    function saveForm(form_Num, formType){
      disable_all();
      $.ajax({
        type:'post',
        url:'../class/Class_FormControl.php',
        data:{
          action:'saveForm',
          formType:formType,
          Can_student:Can_student[form_Num],
          org_Can_student:org_Can_student[form_Num]
        },
        success:function(res){
          enable_all();
          alert('儲存成功');
          window.location.href = "?formNum="+form_Num;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("發生錯誤, 請聯絡網站管理員");
        },
      })
    }
    function disable_all(){
      $(':button').prop('disabled', true);
      $(':input').prop('disabled', true);
    }
    function enable_all(){
      $(':button').prop('disabled', false);
      $(':input').prop('disabled', false);
    }
    function selectAllBox(box){
      var flg=0;
      var row = box.getElementsByClassName('row-item');
      for(var i =0;i < row.length;i++){
        if(!$(row[i]).hasClass("li_click")){
          flg = 1;
          $(row[i]).addClass("li_click");
        }
      }
      if(!flg){
        for(var i =0;i < row.length;i++)
          $(row[i]).removeClass("li_click");
      }
    }
    function editForm(data, sendCount){
      if(parseInt(sendCount.innerHTML) == 0)
        window.location.href = "modify.php?k="+data;
      else
        alert("此表單已有繳交紀錄, 無法修改表單");
    }
    function detail_show(detail_box, a = 0){
      for(var i=0 ; i<table_count ;i++){
        if(detail_box.id.indexOf("detail"+i) != -1){
          if($("#table_row"+i+" div.col-sm-12 div:nth-child(5) input")[0].checked){
            $("#table_row"+i+" :button").prop('disabled', true);
          }else{
            $("#table_row"+i+" :button").tooltip('enable');
          }
          continue;
        }
        $("#detail"+i).addClass("hid");
        $("#show_table"+i).removeClass("glyphicon-triangle-top");
        $("#show_table"+i).addClass("glyphicon-triangle-bottom");
      }
      if(a != 0){
        $(a).toggleClass("glyphicon-triangle-top");
        $(a).toggleClass("glyphicon-triangle-bottom");
      }
      $(detail_box).toggleClass("hid");
    }
    function load_table(table_num = -1){
      document.getElementById("table_manage").innerHTML = content;
          set_click()
      if(table_num == -1){ 
        return;
      }

      document.getElementById("detail"+table_num).classList.remove("hid")
      document.getElementById("show_table"+table_num).classList.remove("glyphicon-triangle-bottom")
      document.getElementById("show_table"+table_num).classList.add("glyphicon-triangle-top")
    }
    //將選中的學生從可填寫改為不可填寫 或是將不可填寫改為可填寫 會將更改的內容存回Can_student 跟 Not_student 陣列 並且依照學號大小排序

    window.onload=function(){
      load_temp()
      load_table(<?php if(isset($_GET['formNum'])) echo $_GET['formNum'];?>)
    }


    //刪除表單的按鈕function()
    //按下時將所有表單background改成紅色
    //點選區塊取得學期存在delete_table變數 例如 108-1
    //詢問是否確定要刪除
    var drop_bt = 1 //移除表單按鈕是否被按過 -1代表被案下 1代表沒有
    function drop_table(bt){
      tables = document.getElementsByClassName("table_row")
      if(drop_bt)
      {
        disable_all();
        bt.disabled = false
        bt.innerHTML = "取消移除"
        for(var i = 0;i < tables.length;i++){
          tables[i].style.background = "#FF5151"
          tables[i].classList.add("myMOUSE")
          tables[i].onclick = function(){
            console.log(this)
            delete_table = this.getElementsByClassName("formType")[0].innerHTML
            var res = prompt('請再輸入一次你要刪除的表單名稱('+delete_table+')')
            if(res == delete_table){
              $.ajax({
                type:'post',
                url:'../class/Class_FormControl.php',
                data:{
                  action:'formRemove',
                  formType:delete_table
                },
                success:function(res){
                  alert(res);
                  location.reload();
                }
              })
            }else{
              alert('名稱錯誤, 沒有任何更動');
            }
          }
        }
      }else{
        enable_all();
        bt.innerHTML = "移除表單"
        for(var i = 0;i < tables.length;i++){
          tables[i].style.background = "white"
          tables[i].classList.remove("myMOUSE")
          tables[i].onclick = ""
        }
      }
      drop_bt = !drop_bt
    }
    function add_table(year, term){
      $.ajax({
        type:'post',
        url:'../class/Class_FormControl.php',
        data:{
          action:'add_form',
          year:year.value,
          term:term.value
        },
        success:function(res){
          alert(res);
          location.reload();
        }
      })
      return false;
    }

    function close_other_li(number){
      var boxs = document.getElementsByClassName("detail")
      for(var i = 0;i < boxs.length;i++)
      {
          console.log(boxs[i].id)
          if(boxs[i].id != ("detail" + number)){
              console.log("detail" + number)
              boxs[i].classList.remove("in")
          }
      }
    }

    function post_pdf(){
      student_checked =  $(".li_click")
      console.log(student_checked)
      var box = $(".li_click .studentId").closest(".detail_box")[0]
      index = box.id.split("_box")[1]
      var formtype = $(".table_row .formtype")[index]
      var post_content = "<input name='formtype' value='" + formtype.innerHTML + "'>";
      post_content += "<input name='student_id' value='"
      $(".li_click .studentId").each(function(i,n){
          post_content += n.innerHTML + " "
      })
      post_content += "'>"
      
      console.log(post_content)
      form = $("#student_id_form")
      form.append(post_content);
      form.submit()
      //window.open("","newWin",config='height=500px,width=1300px');

    }
</script>

<body>
<div class="container" style="margin-top: 100px">
      <h3>表單管理</h3>
      <div class="row" style="margin-bottom: 1%;">
        <div class="col-sm-3 dropdown">
          <button id="add_table_bt" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" style="margin-top:1%">新增表單</button>
          <form class="dropdown-menu" style="padding:1% 0 1% 0;width:400px;">
            <input type="hidden" name="action" value="add_form"/>
              <div class="col-sm-12">
                <div class="col-sm-6">
                  <label>年度：</label><input type="text" style="width: 60px;" name="year" placeholder="ex:108">
                </div>
                <div class="col-sm-6">
                  <label style="width:50px;">學期：</label>
                  <select name="term" style="width: 80px;">
                      <option>1</option>
                      <option>2</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <button onclick="return add_table(year,term)" type="submit" class="btn btn-sm btn-info" style="width: 100%">確定</button>
              </div>
          </form>
          <button onclick="drop_table(this)" type="button" class="btn btn-danger" style="margin-top:1%">移除表單</button>
        </div>
        <div class="col-sm-2"></div>
      </div>
      <div id="table_manage"></div>
    </div>
</div>
<form id="student_id_form" name="form" action="../pdf_temp/pdf_temp.php" method="post" target="newWin" style="display: none;">

</form>
<br>
<br>
</body>
</html>
