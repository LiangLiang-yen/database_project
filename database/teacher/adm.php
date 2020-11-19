<?php
require_once('../class/Class_TeacherHeader.php');
require_once('../class/Class_AdminManage.php');
require_once('../header.php');
$manager = new adminManage($db);
if (isset($_GET['form']))
  $formType = $manager->get_last_form($_GET['form']);
else
  $formType = $manager->get_last_form();
$manager->select_all_form($formType);
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
  <script type="text/javascript" src="teacher_temp.js"></script>
  <script type="text/javascript" src="adminSearch.js"></script>
  <link rel=stylesheet type="text/css" href="teacher_temp.css">
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />
  <script src="form_temp.js"></script>

  <style>
    .hid {
      display: none;
    }

    .mtop {
      margin-top: 1%;
    }

    .bg-darkblue {
      background: darkblue;
    }

    #loader-2 span {
      display: inline-block;
      width: 20px;
      height: 20px;
      border-radius: 100%;
      background-color: #3498db;
      margin: 35px 5px;
    }

    #loader-2 span:nth-child(1) {
      animation: bounce 1s ease-in-out infinite;
    }

    #loader-2 span:nth-child(2) {
      animation: bounce 1s ease-in-out 0.33s infinite;
    }

    #loader-2 span:nth-child(3) {
      animation: bounce 1s ease-in-out 0.66s infinite;
    }

    @media (min-width: 576px) {
      .modal-dialog-centered {
        min-height: calc(100% - (1.75rem * 2));
      }
    }

    .modal-dialog-centered {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      min-height: calc(100% - (.5rem * 2));
    }

    @keyframes bounce {

      0%,
      75%,
      100% {
        -webkit-transform: translateY(0);
        -ms-transform: translateY(0);
        -o-transform: translateY(0);
        transform: translateY(0);
      }

      25% {
        -webkit-transform: translateY(-20px);
        -ms-transform: translateY(-20px);
        -o-transform: translateY(-20px);
        transform: translateY(-20px);
      }
    }

    .modal-content{
        -webkit-touch-callout: none; /* iOS Safari */
          -webkit-user-select: none; /* Safari */
           -khtml-user-select: none; /* Konqueror HTML */
             -moz-user-select: none; /* Firefox */
              -ms-user-select: none; /* Internet Explorer/Edge */
                  user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome and Opera */
      }

    /* 滑鼠指定時改變顏色 */
  </style>
  <script>
    function get_name_or_id() {
      console.log(document.getElementById("name_or_id").value)
    }
    //按下依學生按鈕時觸發
    function namesearch_pannel() {
      //landlordName
      //address
      showAll();
      var table = document.getElementById("searchTable");
      var str = "";
      str += '<div class = "mtop col-sm-3">'
      str += '<div class="input-group col-sm-12">'
      str += '<span class="input-group-addon" style="padding:0;"><select onchange="get_name_or_id()" id="name_or_id" style="height:32px;width:100px;" class="form-control"><option>學號</option><option>姓名</option></select></span>'
      str += '<input id="searchitem" type="text" class="form-control" name="msg" placeholder="">'
      str += '</div></div>'
      str += '<div class="mtop col-sm-1"><button onclick="name_id_search()" type="button" class="mtop btn btn-info">搜尋</button></div>'
      str += '<div class="mtop col-sm-4">'
      str += '<button onclick="showAll()" type="button" class="btn btn-info">全部學生</button> '
      str += '<button onclick="addrsearch(1)" type="button" class="btn btn-info cond">包括同住者</button>'
      str += '</div>'
      table.innerHTML = str;
      $("#student_form div div").each(function(index, element) {
        if ($(element).hasClass("result_col")) {
          $(element).css("height", "100px")
          $(element).css("padding-top", "45px")
        }
      });

      $("#student_form div div div").each(function(index, element) {
        $(element).attr("id") == "landinfo" ? $(element).removeClass("hid") : 1;
        //$('#address').length > 0 ? $(element).removeClass("hid") : $(element).addClass("hid");
      });
      get_name_or_id()
    }

    function close_other_row(panel) {
      var panels = $("#student_form .panel-collapse")
      for (var i = 0; i < panels.length; i++) {
        if (panels[i].id != panel[0].id)
          panels[i].classList.remove("in")
      }
    }

    //依照地址搜尋
    function addrsearch_pannel() {
      showAll();
      var table = document.getElementById("searchTable");
      var str = "";
      str += '<div class = "mtop col-sm-3">'
      str += '<div class="input-group col-sm-12">'
      str += '<span class="input-group-addon">地址</span>'
      str += '<input id="searchitem" type="text" class="form-control" name="msg" placeholder="">'
      str += '</div></div>'
      str += '<div class="mtop col-sm-1"><button onclick="addrsearch()" type="button" class="mtop btn btn-info">搜尋</button></div>'
      str += '<div class="mtop col-sm-4"><button onclick="showAll()" type="button" class="btn btn-info">全部</button></div>'
      table.innerHTML = str;
      $("#student_form div div").each(function(index, element) {
        if ($(element).hasClass("result_col")) {
          $(element).css("height", "100px")
          $(element).css("padding-top", "45px")
        }
      });
      $("#student_form div div div").each(function(index, element) {
        $(element).attr("id") == "landinfo" ? $(element).removeClass("hid") : 1;
        //$('#address').length > 0 ? $(element).removeClass("hid") : $(element).addClass("hid");
      });
    }
    //依照房東名搜尋
    function hostsearch_pannel() {
      showAll();
      var table = document.getElementById("searchTable");
      var str = "";
      str += '<div class = "mtop col-sm-3">'
      str += '<div class="input-group col-sm-12">'
      str += '<span class="input-group-addon">房東姓名</span>'
      str += '<input id="searchitem" type="text" class="form-control" name="msg" placeholder="">'
      str += '</div></div>'
      str += '<div class="mtop col-sm-1"><button onclick="hostsearch()" type="button" class="mtop btn btn-info">搜尋</button></div>'
      str += '<div class="mtop col-sm-4"><button onclick="showAll()" type="button" class="btn btn-info">全部</button></div>'
      table.innerHTML = str;
      $("#student_form div div").each(function(index, element) {
        if ($(element).hasClass("result_col")) {
          $(element).css("height", "100px")
          $(element).css("padding-top", "45px")
        }
      });
      $("#student_form div div div").each(function(index, element) {
        $(element).attr("id") == "landinfo" ? $(element).removeClass("hid") : 1;
        //$('#address').length > 0 ? $(element).removeClass("hid") : $(element).addClass("hid");
      });
    }
    //依照繳交時間查訪
    function senddatesearch_pannel() {
      showAll();
      $("#student_form div div").each(function(index, element) {
        if ($(element).hasClass("result_col")) {
          $(element).css("height", "50px")
          $(element).css("padding-top", "15px")
        }
      });
      $("#student_form div div div").each(function(index, element) {
        $(element).attr("id") == "landinfo" ? $(element).addClass("hid") : 1
        //$('#address').length > 0 ? $(element).removeClass("hid") : $(element).addClass("hid");
      });
      var table = document.getElementById("searchTable");
      var str = "";
      str += '<div class="mtop col-sm-3">'
      str += '<input type="date" id="from-date" class="form-control" placeholder="Search" name="search">'
      str += '</div>'
      str += '<div class="mtop col-sm-3">'
      str += '<input type="date" id="to-date" class="form-control" placeholder="Search" name="search">'
      str += '</div>'
      str += '<div class="mtop col-sm-1"><button onclick="sendsearch()" type="button" class="mtop btn btn-info">搜尋</button></div>'
      str += '<div class="mtop col-sm-5">'
      str += '<button onclick="uncond(this)" id="uncond" type="button" class="btn btn-primary">全部</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_paid">已繳交</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_unpaid">未繳交</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_end">已銷案</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_notend">未銷案</button> '
      str += '</div>'
      table.innerHTML = str;
    }
    //依照查訪時間查訪
    function visitdatesearch_pannel() {
      showAll();
      $("#student_form div div").each(function(index, element) {
        if ($(element).hasClass("result_col")) {
          $(element).css("height", "50px")
          $(element).css("padding-top", "15px")
        }
      });
      $("#student_form div div div").each(function(index, element) {
        $(element).attr("id") == "landinfo" ? $(element).addClass("hid") : 1
        //$('#address').length > 0 ? $(element).removeClass("hid") : $(element).addClass("hid");
      });
      var table = document.getElementById("searchTable");
      var str = "";
      str += '<div class="mtop col-sm-3">'
      str += '<input type="date" id="from-date" class="form-control" placeholder="Search" name="search">'
      str += '</div>'
      str += '<div class="mtop col-sm-3">'
      str += '<input type="date" id="to-date" class="form-control" placeholder="Search" name="search">'
      str += '</div>'
      str += '<div class="mtop col-sm-1"><button onclick="visitsearch()" type="button" class="mtop btn btn-info">搜尋</button></div>'
      str += '<div class="mtop col-sm-5">'
      str += '<button onclick="uncond(this)" id="uncond" type="button" class="btn btn-primary">全部</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_paid">已繳交</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_unpaid">未繳交</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_end">已銷案</button> '
      str += '<button onclick="cond(this)" type="button" class="btn btn-info cond" id="btn_notend">未銷案</button> '
      str += '</div>'
      table.innerHTML = str;
    }
    window.onload = function() {
      load_temp();

      var ch = document.getElementsByClassName("ch")
      senddatesearch_pannel()
      for (var i = 0; i < ch.length; i++) {
        ch[i].onclick = function() {
          for (var i = 0; i < ch.length; i++) {
            ch[i].className = "ch"
          }
          this.className = "active ch"
        }
      }
      document.getElementById("title").innerHTML = Title;
      loadData();

    }

    function loadData() {
      var data = document.getElementById("all_data");
      data.innerHTML = student_form_content;
      reloadDataPage();
    }


    var userId;
    var formType;
    var student_number_temp = 0;
    var now;


    function get_form(i) {
      var j = 0;
      var student_number_list = [];
      for (j = 0; j < $("#all_data #sendStatus").length; j++) {
        if ($("#all_data #sendStatus")[j].innerHTML == "1") {
          student_number_list.push(j)
        }
      }
      if (isNaN(i)) {
        if (i == "next") {
          student_number_temp + 1 < student_number_list.length ? student_number_temp += 1 : alert("你他媽點三小");
        } else if (i == "pre") {
          student_number_temp - 1 >= 0 ? student_number_temp -= 1 : alert("你他媽點三小");
        } else {
          console.log("worng!!")
        }
      } else {
        console.log(student_number_list.indexOf(i));
        student_number_temp = student_number_list.indexOf(i)
      }
      if (student_number_temp != "-1") {
        $("#loadingModal").modal();
        var counter = student_number_list[student_number_temp]
        now = counter;
        var row = $("#all_data").children().eq(counter)
        if ($("#all_data #sendStatus")[counter].innerHTML == "1") {
          //學生姓名
          var name = $("#all_data #student_name")[counter].innerHTML;
          //學生ID
          userId = $("#all_data #student_Id")[counter].innerHTML;
          //學生電話
          var student_phone = $("#all_data #stu_phone")[counter].innerHTML;
          //班級
          var student_class = $("#all_data #class_Name")[counter].innerHTML;
          //租屋地點
          var addr = $("#all_data #address")[counter].innerHTML;
          //房東姓名
          var host_name = $("#all_data #landlordName")[counter].innerHTML;
          //房東電話
          var host_phone = $("#all_data #landlordPhone")[counter].innerHTML;
          //學年-學期
          formType = $("#all_data #form_Tybe")[counter].innerHTML;
          //是否銷案
          var endStatus = $("#all_data #endStatus1")[counter].innerHTML;

          if (endStatus == "1") {
            endStatus = true;
          } else {
            endStatus = false;
          }

          var student_data = [host_name, host_phone, addr, student_class, userId, name];
          $.ajax({
            type: 'post',
            url: '../class/Class_SelectStudentForm.php',
            data: {
              action: 'getform',
              formType: formType,
              userId: userId
            },
            dataType: 'json',
            success: function(res) {
              load_student_form(res, student_data, student_number_temp, endStatus)
              $("#loadingModal").modal('hide');
              //redirect
              window.location.href = "#form_";

              //head_content(student_data,res.year_team);
              //body_cntent(res.answer,res.questiondesign,res.imagePATH,res.student_description,res.teacher_description)
            }
          })


        }
      } else {
        alert('同學尚未填寫表單');
      }
    }


    function insert_description() {
      var description = $("#teacher_comment")[0].value;;
      if ($("#endStatus")[0].checked) {
        endStatus = 1;
      } else {
        endStatus = 0;
      }
      if (description != "") {
        $("#loadingModal").modal();
        $.ajax({
          type: 'post',
          url: '../class/Class_SelectStudentForm.php',
          data: {
            action: 'insrtform',
            userId: userId,
            formType: formType,
            description: description,
            endStatus: endStatus,
            endStatus_key: $("#endStatus")[0].checked
          },
          success: function(res) {
            if (res == "fuck") {
              $.ajax({
                type: 'post',
                url: '../class/Class_SelectStudentForm.php',
                data: {
                  action: 'endStatus_email',
                  formType: formType,
                  userId: userId,
                  endStatus: endStatus,
                  description: description
                },
                success: function(res) {
                  $("#loadingModal").modal('hide');
                  if (res == "SUCCES") {
                    if (endStatus == 1) {
                      $("#end_Status_"+now).text("已銷案");
                    }
                    if (endStatus == 0) {
                      $("#end_Status_"+now).text("未銷案");
                    }
                    alert("帳號:" + userId + "寄信成功");
                  } else if (res == "FAIlEMAIL") {
                    alert("寄信失敗, 請聯絡網頁管理員");
                  } else if (res == "FAIlESELECT") {
                    alert("查詢失敗, 請聯絡網頁管理員");
                  }
                }
              })
            } else if (res == "fuckyou") {
              alert("尚未更改不需儲存");
              $("#loadingModal").modal('hide');
            } else if (res == "GODEAD") {
              $("#all_data #end_Status")[now].innerText = "未銷案";
              alert("尚未將可填寫關閉,請將可填寫關閉,即可銷案");
              $("#loadingModal").modal('hide');
            } else if (res == "ERROR") {
              alert("更新失敗, 請聯絡網頁管理員");
              $("#loadingModal").modal('hide');
            }
          }
        })
      } else {
        alert("尚未輸入");
      }
      reloadDataPage();
    }


    //切換分頁
    var pagenumber = 1;

    function change_page(number, max) {
      page_Li = document.getElementsByClassName("page_li")
      $(page_Li).removeClass("active disabled")
      //console.log(Math.round(max))

      if (number == "next") {
        if (pagenumber != Math.round(max))
          pagenumber++
      } else if (number == "pre") {
        if (pagenumber != 1)
          pagenumber--
      } else {
        pagenumber = number
      }

      if (pagenumber >= Math.round(max))
        $(".page_li").last().addClass("disabled");
      else if (pagenumber <= 1)
        $(".page_li").first().addClass("disabled");

      var pages = document.getElementsByClassName("page")
      for (var i = 0; i < pages.length; i++) {
        pages[i].classList.add("hid")
      }
      document.getElementById("page" + pagenumber).classList.remove("hid")
      $(page_Li[pagenumber]).addClass("active")
    }

    function post_pdf(){
      number = ["一","二"]
      t = document.getElementById("form_title").innerHTML.split(" ")[3] == number[0] ? 1 : 2
      year = document.getElementById("form_title").innerHTML.split(" ")[1]
      var post_content = "<input name='formtype' value='" + year + "-" + t + "'>";
      post_content += "<input name='student_id' value='"
      post_content += document.getElementById("form_student_id").value
      post_content += "'>"
      console.log(post_content)
      form = $("#student_id_form")
      form.append(post_content);
      form.submit()
      //window.open("","newWin",config='height=500px,width=1300px');

    }
  </script>
</head>

<body>
  <form id="student_id_form" name="form" action="../pdf_temp/pdf_temp.php" method="post" target="newWin" style="display: none;">

  </form>

  <div class="container" style="margin-top: 100px;padding:0;">

    <div class="col-sm-8">
      <h2 id="title" style="color:bl;">NULL</h2>
    </div>
    <div class="col-sm-4" style="text-align: right;">
      <button style="margin-top:20px;" onclick="ChoicePdf()" class="glyphicon glyphicon-print btn btn-info">&nbsp;篩選結果PDF列印</button>
    </div>
    <div class="col-sm-12" style="padding:0;">
      <ul class="nav nav-tabs">
        <li class="active ch"><a style="font-size: 16px;color:black;" href="#" onclick="senddatesearch_pannel()">依繳交時間</a></li>
        <li class="ch"><a style="font-size: 16px;color:black" href="#" onclick="visitdatesearch_pannel()">依查訪時間</a></li>
        <li class="ch"><a style="font-size: 16px;color:black" href="#" onclick="namesearch_pannel()">依學生</a></li>
        <li class="ch"><a style="font-size: 16px;color:black" href="#" onclick="addrsearch_pannel()">依地址</a></li>
        <li class="ch"><a style="font-size: 16px;color:black" href="#" onclick="hostsearch_pannel()">房東名</a></li>
      </ul>
    </div>
    <br>
    <div class="row" id="searchTable">
    </div>
  </div>

  <div class="container">
    <div class="list-group mtop" id="student_form"></div>
  </div>
  <div class="hid" id="all_data"></div>
  <div class="container" id="form_" style="margin-top:50px;"></div>
  <div class="modal fade" id="loadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Loading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="margin:auto">
        <div class="modal-body">
          <div class="col-md-12 bg" style="text-align: center; width:100% ">
            <div class="loader" id="loader-2">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <br>
</body>

</html>