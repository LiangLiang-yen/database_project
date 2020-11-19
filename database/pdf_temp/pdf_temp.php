<html>

<head>
    <title>pdf列印標準格式</title>
    <meta charset="utf-8">
    <meta name="description" content="學生表單PDF列印介面">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdn.bootcss.com/html2canvas/0.5.0-alpha1/html2canvas.js"></script>
    <script src="html2pdf.js-master/dist/html2pdf.bundle.min.js"></script>
    <script src="pdf_temp.js"></script>
    <script src="export_pdf.js"></script>
    <meta http-quiv='cahe-control' content="no-cache">
    <meta http-quiv='pragram' content="no-cache">
</head>
<style>
    .mtop {
        margin-top: 5px;
    }

    font.form_head {
        font-size: 20px;
    }

    .hid {
        display: none;
    }

    .imgdiv {
        width: 400px;
        height: 200px;
        overflow: hidden;
    }

    img {
        max-width: 400px;
        max-height: 200px;
    }

    font {
        word-wrap: break-word;
    }

    .wordwrap {
        word-wrap: break-word;
    }
</style>
<script>
    var student_dic = new Array()
    var content = ""
    var formtype = "108-1"
    //取得資料庫有關學生的所有表單資料
    function call_db_data() {
        return form_data = <?php
                            $formType;
                            if (empty($_POST['formtype']) & empty($_POST['student_id'])) {
                            } else {
                                $student_id = trim($_POST['student_id']);
                                $formType = explode(" ", $_POST['formtype'])[0];
                                $student_id = explode(" ",$student_id);
                            }
                            require_once('../connectDB.php');
                            $student_data = array();
                            $test = array();
                            $query = 'SELECT * FROM teacher_controlform INNER JOIN student_form on teacher_controlform.formId = student_form.formId
                    INNER JOIN account on account.userId = teacher_controlform.userId
                    Where teacher_controlform.formType = "' . $formType . '"';
                            //$query = 'SELECT * FROM account Where admin=0'; 
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                $counter = 0;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    if (in_array($row["userId"], $student_id)) {
                                        $student_data[$counter] = array($row["formId"], $row["formType"], $row["landlordName"], $row["landlordPhone"], $row["address"], $row["class"], $row["userId"], $row["name"], $row["description"]);
                                        $counter++;
                                    }
                                }
                            }
                            $form = array();
                            $form_counter = 0;
                            foreach ($student_data as $value) {
                                $query = 'SELECT * FROM  student_question Where formId = "' . $value[0] . '"';
                                //$query = 'SELECT * FROM account Where admin=0'; 
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                $question = array();
                                if ($stmt->rowCount() > 0) {
                                    $counter = 0;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $question[$counter] = array($row["questionId"], $row["description"], $row["imagePATH"]);
                                        $counter++;
                                    }
                                }
                                $form[$form_counter] = array($value, $question);
                                $form_counter++;
                            }
                            $content = "";
                            $content .= "[";
                            for ($j = 0; $j < count($form); $j++) {
                                $content .= "[[";
                                for ($i = 1; $i < 8; $i++) {
                                    $content .=  "'" . $form[$j][0][$i] . "',";
                                }
                                $content .= "'" . $form[$j][0][8] . "'],";
                                $content .= "[";
                                foreach ($form[$j][1] as $question2arr) {
                                    $content .= "[";
                                    for ($i = 0; $i < 2; $i++) {
                                        $content .= "'" . $question2arr[$i] . "',";
                                    }
                                    $content .= "'" . $question2arr[2] . "'],";
                                }
                                if ($j == count($form) - 1) {
                                    $content .= "]]";
                                } else {
                                    $content .= "]],";
                                }
                            }
                            $content .= "]";
                            echo $content;
                            ?>
    }

    function questiondesign() {
        return questiontable = <?php
                                require_once('../connectDB.php');
                                $student_data = array();
                                $test = array();
                                $query = 'SELECT * FROM teacher_questiondesign Where teacher_questiondesign.formType = "' . $formType . '"';
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                if ($stmt->rowCount() > 0) {
                                    echo "[";
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "['" . $row['questionId'] . "','" . $row['questionName'] . "'],";
                                    }
                                    echo "]";
                                }
                                ?>
    }

    function generatePDF() {
        // Choose the element that our invoice is rendered in.
        const element = document.getElementById("pdf_container");
        // Choose the element and save the PDF for our user.
        html2pdf()
        .from(element)
        .save();
    }
    window.onload = function() {
        form_data = new Array()
        form_data = call_db_data()
        console.log(form_data)
        questionTable = questiondesign()
        console.log(questionTable)
        //load_download_icon()
        load_student_form(form_data, questionTable)
    }
</script>

<body style="word-break:break-all;" class="container">
    <div style="width: 800px;text-align:center;">
        <h1>PDF預覽</h1>
    </div>

    <button class="btn btn-info"  onclick="generatePDF()">PDF檔案下載</button>

    <div id="pdf_container" class="pdf_container1" style="width: 800px;">
        <div id="student_form" >

        </div>
    </div>

</body>

</html>