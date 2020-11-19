<?php
    require_once('../class/Class_TeacherHeader.php');
    require_once('../class/Class_PDF.php');
    require_once('../header.php');
    $pdf_import = new pdf_import($db);
    $pdf_import->select_all_formType();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
</style>
<script>
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
    function detail_show(detail_box,bt){
      for(var i=0 ; i<table_count ;i++){
        if(detail_box.id.indexOf("detail"+i) != -1)
          continue;
        $("#detail"+i).addClass("hid");
        $("#show_table"+i).removeClass("glyphicon-triangle-top");
        $("#show_table"+i).addClass("glyphicon-triangle-bottom");
      }
      detail_box.classList.contains("hid") ? detail_box.classList.remove("hid") : detail_box.classList.add("hid")
      $(bt).toggleClass("glyphicon-triangle-top");
      $(bt).toggleClass("glyphicon-triangle-bottom");
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

    function post_pdf(){
      student_checked =  $(".li_click")
      var content = "<input name='student_data' value='";
      var box = $(".li_click .studentId").closest(".box1")[0]
      var formtype = box.getElementsByClassName("form_head_data")[0].getElementsByClassName("formType")[0].innerHTML
      content += formtype + ","
      $(".li_click .studentId").each(function(i,n){
        if(n.closest('div').id.split("_")[0] == "Can")
        {
          content += n.innerHTML + ","
        }  
      })
      content += "'>"
      form = $("#student_id_form")
      form.append(content);
      form.submit()
      //window.open("","newWin",config='height=500px,width=1300px');

    }


    window.onload=function(){
      load_temp()
      load_table()
    }
</script>

<body style="background-image: url('img/adm_bk.jpeg');">
<div style="width:100%; height:100%; position: absolute;left: 0;top: 0px;z-index: -1;background:#dcdcdc;opacity: 0.5"></div>
<div class="container" style="margin-top: 100px">
      <h3>PDF表單列印</h3>
      <div class="row" style="margin-bottom: 1%;">

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
