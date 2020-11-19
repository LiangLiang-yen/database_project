<?php
  require_once('../class/Class_StudentHeader.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>History</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="student_temp.js"></script>
  <style>
    .mtop{
      margin-top: 1%;
    }
  </style>
  <script>
    window.onload = function(){
      load_temp()
    }
  </script>
</head>
<body>
  
<div class="container" style="margin-top: 50px">
    <h2>歷年表單 </h2>
    <div class="list-group">
      <a href="form.html" class="list-group-item row ">
        <div class="col-sm-2">108學年度第一學期</div>
        <div class="col-sm-2">未銷案</div>
      </a>
      <a href="#" class="list-group-item row">
        <div class="col-sm-2">107學年度第二學期</div>
        <div class="col-sm-2">已銷案</div>
      </a>
      <a href="#" class="list-group-item row">
        <div class="col-sm-2">107學年度第一學期</div>
        <div class="col-sm-2">已銷案</div>
      </a>
      <a href="#" class="list-group-item row ">
        <div class="col-sm-2">106學年度第二學期</div>
        <div class="col-sm-2">已銷案</div>
      </a>
      <a href="#" class="list-group-item row ">
        <div class="col-sm-2">106學年度第一學期</div>
        <div class="col-sm-2">已銷案</div>
      </a>
    </div>
</div>
<br>
<br>
</body>
</html>
