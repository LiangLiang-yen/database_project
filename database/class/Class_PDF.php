<?php

require_once("Class_Default.php");
require_once('../connectDB.php');

class pdf_import extends _Default
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function select_all_formType()
    {
        $query = 'SELECT Teacher_Form_Type.formType, 
                    Teacher_Form_Type.studentWrite, 
                    COUNT(Teacher_ControlForm.formType) AS People,
                    Count(case when (sendStatus=1) then 1 else null end) AS "sendCount",
                    Count(case when (endStatus=1) then 1 else null end) AS "endCount" 
                    FROM Teacher_Form_Type LEFT JOIN Teacher_ControlForm ON Teacher_Form_Type.formType = Teacher_ControlForm.formType GROUP BY Teacher_Form_Type.formType ORDER BY formType DESC';
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<script type="text/javascript">
                    var table_count = ' . $stmt->rowCount() . ';
                    var content = "";
                    var Can_student = [];
                    var Not_student = [];
                    ';
            for ($row = $stmt->fetch(PDO::FETCH_ASSOC), $i = 0; $row; $row = $stmt->fetch(PDO::FETCH_ASSOC), $i++) {

                $disableBtn = $row['sendCount'] == 0 ? 'disabled' : '';

                $stmt1 = null;
                $query = 'SELECT userId,name FROM Account WHERE userId IN (SELECT userId FROM Teacher_ControlForm WHERE sendStatus=1 AND formType="' . $row['formType'] . '")';
                $stmt1 = $this->db->prepare($query);
                $stmt1->execute();

                if ($stmt1->rowCount() > 0) {
                    echo 'Can_student[' . $i . '] = [';
                    while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                        echo '{studentId : "' . $row1['userId'] . '", name : "' . $row1['name'] . '"},
                        ';
                    }
                    echo '];
                    ';
                } else {
                    echo 'Can_student[' . $i . '] = [];
                    ';
                }

                echo 'Not_student['.$i.'] = [];
                ';

                $stmt1 = null;
                echo 'content += \'<div class="row box1 table_row" id="table_row' . $i . '" style="background:white;margin:0 0 -1px -1px;">\
                <div class="col-sm-11 form_head_data">\
                    <div class="col-sm-1 formType">' . $row['formType'] . '</div>\
                    <div class="col-sm-2">人數:' . $row['People'] . '</div>\
                    <div class="col-sm-3">表單繳交數量:' . $row['sendCount'] . '</div>\
                    <div class="col-sm-3">銷案數量:' . $row['endCount'] . '</div>\
                    <div class="col-sm-3"></div>\
                    <div id="sendCount' . $i . '" class="hid">' . $row['sendCount'] . '</div>\
                </div>\
                <div class="col-sm-1" style="padding:12px;">\
                    <a href="#" id="show_table' . $i . '" onclick="detail_show(detail' . $i . ',this)"  class="glyphicon glyphicon-triangle-bottom"></a>\
                </div>\
                <div id="detail' . $i . '" class="hid col-sm-12">\
                    <div class="col-sm-1"></div>\
                    <div class="row col-sm-5">\
                        <div class="row">\
                            <div class="col-sm-8">要列印的學生</div>\
                            <div class="col-sm-4"><button onclick="selectAllBox(Can_box' . $i . ')" type="button" class="btn btn-link">全選</button></div>\
                        </div>\
                        <div id="Can_box' . $i . '" class="col-sm-12 detail_box" style="overflow:auto;  overflow-x: hidden;">\
                            <ul style="padding: 0;margin:0;">\'
                                for(var i = 0; i < Can_student[' . $i . '].length; i++){
                                    content += \'<li class="row-item">\
                                    <ul class="row">\
                                        <li class="col-xs-6 studentId">\'+ Can_student[' . $i . '][i].studentId +\'</li>\
                                        <li class="col-xs-6 name">\'+ Can_student[' . $i . '][i].name +\'</li>\
                                    </ul>\';
                                }
                content+=\'</ul>\
                        </div>\
                    </div>\
                    <div class="col-sm-1" style="margin-top:10%">\
                        <div class="row"><button type="button" class="btn btn-primary" onclick="set_CanFillStudent(' . $i . ')">\<\<</button></div>\
                        <div class="row" style="margin-top:10px"><button type="button" class="btn btn-primary" onclick="set_CanNotFillStudent(' . $i . ')">\>\></button></div>\
                    </div>\
                    <div class="row col-sm-5" >\
                        <div class="row">\
                            <div class="col-sm-8">不要列印的學生</div>\
                            <div class="col-sm-4"><button onclick="selectAllBox(Not_box' . $i . ')" type="button" class="btn btn-link">全選</button></div>\
                        </div>\
                        <div id="Not_box' . $i . '" class="col-sm-12 detail_box" style="overflow:auto; overflow-x: hidden;">\
                           <ul style="padding: 0;margin:0;">\'
                content+=\'</ul>\
                        </div>\
                    </div>\
                    <div class="col-sm-12 mtop">\
                            <div class="col-sm-2" style="margin-bottom:1%"><a href="#" class="btn btn-success" onclick="post_pdf()">PDF表單列印</a></div>\
                    </div>\
                </div>\
                </div>\'
                ';
            }
            echo '</script>';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formControl = new formControl($db);
    if (isset($_POST['action'])) {
        
    }
}
