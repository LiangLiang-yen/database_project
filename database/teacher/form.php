<?php
    require_once('../class/Class_TeacherHeader.php');
    require_once('../class/Class_SelectStudentForm.php');
    require_once('../header.php');
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
      if( isset($_GET['k'])) 
      {
        $StudentFrom = new StudentFrom($db);
        $formId=$StudentFrom->backwind($_GET['k']);
        $StudentFrom->select_student_From($formId);
      }
      else
      {
        header('Location: adm.php');
      }
    }
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
  <link rel=stylesheet type="text/css" href="teacher_temp.css">
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

  <script type="text/javascript" src="form_temp.js"></script>
  <style>
    .mtop {
      margin-top: 1%;
    }

    .hid {
      display: none;
    }
  </style>
  <script>
    $(document).ready(function() {
      load_temp() //載入導航覽樣板
      load_student_form() //載入學生表單

    })
    window.onload = function() {

    }
  </script>
</head>

<body>
  <div class="container mtop" style="margin-top: 100px">
    <div class="col-sm-1"></div>
    <div class="col-sm-10" style="text-align: right;">
      <button class="btn btn-info" onclick="btnDownloadPageBypfd2('#pdf_container')">pdf列印</button>
      <button onclick="load_student_form()" class="btn btn-info">儲存表單</button>
      <button class="btn btn-info">上一位</button>
      <button class="btn btn-info">下一位</button>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="container mtop" id="student_form" style="margin-top: 20px">

  </div>
  <div class="container mtop">
      <div class="col-sm-1"></div>
      <div class="col-sm-10" style="text-align: right;">
        <button class="btn btn-info" onclick="btnDownloadPageBypfd2('#pdf_container')">pdf列印</button>
        <button onclick="load_student_form()" class="btn btn-info">儲存表單</button>
        <button class="btn btn-info">上一位</button>
        <button class="btn btn-info">下一位</button>
      </div>
      <div class="col-sm-1"></div>
  </div>


  <br>
  <br>
</body>

</html>