<?php
    require_once('../class/Class_TeacherHeader.php');
    require_once('../class/Class_modify.php');
    require_once('../header.php');
    $modify=new modify($db);
   
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if(isset($_GET['k']))
      {
      $formType = _Default::StringDecryption($_GET['k']);
      $modify->select__question_table($formType);
     
       //$formType="108-1";
        if(preg_match("/[0-9]{3}-[0-9]/", $formType))
        {
          $year = preg_split('/-/',$formType)[0];
          $term = preg_split('/-/',$formType)[1];
          if($term==1)
          {
            $term="一";
          }
          if($term==2)
          {
            $term="二";
          }

          echo '<script type="text/javascript">'.
              'var formtype = "'.$formType.'";'.
              'var year = "'.$year.'";'.
              'var team = "'.$term.'";'.
              '</script>';
        }
       else
       {
         header('Location: adm.php');
       }
      
      }
      else
      {
        header('Location: adm.php');
      }
    }
    else
    {
      header('Location: adm.php');
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
  <script type="text/javascript" src="modify.js"></script>
  <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

  <style>
      .delete_color{
        background-color: #FF5151;
    }
    .mtop{
      margin-top: 1%;
    }
    .hid{
      display: none;
    }
  </style>
  <script>
    $(document).ready(function(){
      //延長cookie時間
      var date = new Date();
      date.setTime(date.getTime() + 1000*7200);
      document.cookie = "login=True; expires=" + date.toGMTString() + "; path=/";

      load_temp() //載入導航覽樣板
      load_student_form() //載入學生表單
     })
  </script>
</head>
<body>
  <div id="student_form" class="container" style="margin-top: 50px">
  </div>
<br>
<br>
</body>
</html>
