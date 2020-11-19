<?php
require_once('../class/Class_StudentHeader.php');
require_once("../class/Class_Student_form.php");
require_once("../header.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>表單列表</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdn.bootcss.com/html2canvas/0.5.0-alpha1/html2canvas.js"></script>

    <!--載入表單所需JS-->
    <script type="text/javascript" src="student_temp.js"></script>
    <script src="/pdf_temp/export_pdf.js"></script>
    <script type="text/javascript" src="form_temp.js"></script>
    <link rel="stylesheet" href="student_temp.css" />

    <link rel="icon" href="../images/NFU_Logo.svg .ico" type="image/x-icon" />

    <style>
        .mtop {
            margin-top: 1%;
        }

        .hid {
            display: none;
        }

        .pdf {
            position: fixed;
            left: 0;
            top: 0;
        }
    </style>

    <script>
        window.onload= function(){
            image_table_title = "上傳圖片"
            load_image_table(image_table_title,"personal_img")

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
            $(".personal_img").on('load', function () {
                $("#img_"+CquestionNum).attr('src', $(".personal_img")[0].src)
            })
            load_temp();
            load_table_list();
            load_descript();

        }
    </script>
</head>

<body>
    <!--id="student_form" 學生表單 -->
    <div id="pdf_container">
        <div class="container mtop " id="student_form" style="margin-top:100px;">
        </div>

    </div>
    <img class="personal_img hid">
    <br>
    <br>
</body>

</html>