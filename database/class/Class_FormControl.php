<?php
require_once("Class_Default.php");
require_once('../connectDB.php');

class formControl extends _Default
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

        echo '<script type="text/javascript">
        var table_count = ' . $stmt->rowCount() . ';
        var content = "";
        var Can_student = [];
        var Not_student = [];
        var org_Can_student = [];
        ';
        if ($stmt->rowCount() > 0) {
            for ($row = $stmt->fetch(PDO::FETCH_ASSOC), $i = 0; $row; $row = $stmt->fetch(PDO::FETCH_ASSOC), $i++) {
                $checkbox = $row['studentWrite'] ? "checked" : "";

                $query = 'SELECT Account.userId, Account.name FROM Teacher_ControlForm INNER JOIN Account ON Teacher_ControlForm.userId = Account.userId WHERE Teacher_ControlForm.formType="' . $row['formType'] . '"';
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
                $stmt1 = null;
                $query = 'SELECT userId,name FROM Account WHERE admin<>1 AND userId NOT IN (SELECT userId FROM Teacher_ControlForm WHERE formType="' . $row['formType'] . '")';
                $stmt1 = $this->db->prepare($query);
                $stmt1->execute();

                if ($stmt1->rowCount() > 0) {
                    echo 'Not_student[' . $i . '] = [';
                    while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                        echo '{studentId : "' . $row1['userId'] . '", name : "' . $row1['name'] . '"},
                        ';
                    }
                    echo '];
                    ';
                } else {
                    echo 'Not_student[' . $i . '] = [];
                    ';
                }
                echo 'org_Can_student = JSON.parse(JSON.stringify(Can_student));';
                $stmt1 = null;
                echo 'content += \'<div class="row box1 table_row" id="table_row' . $i . '" style="margin:0 0 -1px -1px;background:white;border-radius:5px;">\
                <div class="col-sm-12" onclick="close_other_li(' . $i . ')" data-toggle="collapse" href="#detail' . $i . '" style="cursor: pointer;">\
                    <div class="col-sm-1 formType">' . $row['formType'] . '</div>\
                    <div class="col-sm-2">人數:' . $row['People'] . '</div>\
                    <div class="col-sm-3">表單繳交數量:' . $row['sendCount'] . '</div>\
                    <div class="col-sm-3">銷案數量:' . $row['endCount'] . '</div>\
                    <div class="col-sm-3"><input type="checkbox" ' . $checkbox . ' style="margin:0px;width:18px;height:18px;vertical-align:middle;" onclick="updateWrite(this,\\\'' . $row['formType'] . '\\\',detail'.$i.', endCount' . $i . ')">&nbsp; &nbsp;可填寫</div>\
                    <div id="sendCount' . $i . '" class="hid">' . $row['sendCount'] . '</div>\
                    <div id="endCount' . $i . '" class="hid">' . $row['endCount'] . '</div>\
                </div>\
                <div id="detail' . $i . '" class="detail collapse col-sm-12">\
                    <div class="col-sm-1"></div>\
                    <div class="row col-sm-5">\
                        <div class="row">\
                            <div class="col-sm-8">可填寫表單學生</div>\
                            <div class="col-sm-4"><button onclick="selectAllBox(Can_box' . $i . ')" type="button" class="btn btn-link">全選</button></div>\
                        </div>\
                        <div id="Can_box' . $i . '" class="col-sm-12 detail_box" style="overflow:auto; border-bottom:0px; border-right:0px;border-left:0px;overflow-x: hidden;">\
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
                        <div class="row"><span class="d-inline-block" title="表單狀態為可填寫，無法使用此功能"><button type="button" class="btn btn-primary" onclick="set_CanFillStudent(' . $i . ')">\<\<</button></span></div>\
                        <div class="row" style="margin-top:10px"><span class="d-inline-block" title="表單狀態為可填寫，無法使用此功能"><button type="button" class="btn btn-primary" onclick="set_CanNotFillStudent(' . $i . ')">\>\></button></span></div>\
                        <div class="row" style="margin-top:20px">\
                            <span class="d-inline-block" title="表單狀態為可填寫，無法使用此功能">\
                                <button type="button" class="btn btn-success" onclick="saveForm(' . $i . ', \\\'' . $row['formType'] . '\\\')">儲存</button>\
                            </span>\
                        </div>\
                        <div class="row" style="margin-top:20px">\
                            <span class="d-inline-block" title="表單狀態為可填寫，無法使用此功能">\
                                <button type="button" class="btn btn-info" onclick="load_table(' . $i . ')">取消</button>\
                            </span>\
                        </div>\
                    </div>\
                    <div class="row col-sm-5" >\
                        <div class="row">\
                            <div class="col-sm-8">不可填寫表單學生</div>\
                            <div class="col-sm-4"><button onclick="selectAllBox(Not_box' . $i . ')" type="button" class="btn btn-link">全選</button></div>\
                        </div>\
                        <div id="Not_box' . $i . '" class="col-sm-12 detail_box" style="border-bottom:0px; border-right:0px;border-left:0px;overflow:auto; overflow-x: hidden;">\
                            <ul style="padding: 0;margin:0;">\'
                                for(var i = 0; i < Not_student[' . $i . '].length; i++){
                                    content += \'<li class="row-item">\
                                    <ul class="row">\
                                        <li class="col-xs-6 studentId">\'+ Not_student[' . $i . '][i].studentId +\'</li>\
                                        <li class="col-xs-6 name">\'+ Not_student[' . $i . '][i].name +\'</li>\
                                    </ul>\';
                                }
                content+=\'</ul>\
                        </div>\
                    </div>\
                    <div class="col-sm-1 mtop"></div>\
                    <div class="col-sm-10 mtop">\
                        <div style="margin:0 0 20px 2px;float:left;"><span class="d-inline-block " title="表單狀態為可填寫，無法使用此功能"><button onclick="editForm(\\\'' . $this->StringEncryption($row['formType']) . '\\\', sendCount' . $i . ')" type="button" class="btn btn-success glyphicon glyphicon-pencil" style="margin-top:2%;">&nbsp;表單修改</button></span></div>\
                        <div  style="margin:0 0 20px 2px;float:left;"><a href="adm.php?form='. $row['formType'] .'" type="button" class="btn btn-warning glyphicon glyphicon-zoom-in" style="margin-top:2%;">&nbsp;表單搜尋</a></div>\
                        <div style="margin:0 0 20px 2px;float:left;"><a onclick="post_pdf()" class="glyphicon glyphicon-print btn btn-info" style="margin-top:2%;">&nbsp;PDF列印</a></div>\
                    </div>\
                </div>\
                </div>\'
                ';
            }
        }
        echo '</script>';
    }

    public function updateWrite($formType, $bool)
    {
        $formType = parent::SQL_Injection_check($formType);
        $bool = parent::SQL_Injection_check($bool);

        $query = "UPDATE Teacher_Form_Type SET studentWrite=:Bool WHERE formType=:FormType";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Bool', $bool);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function updateFormControl($formType, $add_student, $rm_student)
    {
        $formType = parent::SQL_Injection_check($formType);
        if (count($add_student) > 0)
            $this->add_formControl($formType, $add_student);
        if (count($rm_student) > 0)
            $this->rm_formControl($formType, $rm_student);
    }

    private function add_formControl($formType, $add_student)
    {
        $str = "";
        foreach ($add_student as $data) {
            $format_ = preg_split('/-/', $formType);
            $studentId = $data['studentId'];
            $formId = $format_[0] . '0' . $format_[1] . $studentId;
            $str .= '(\'' . $formType . '\',\'' . $formId . '\',\'' . $studentId . '\'),';
        }

        $str = substr($str, 0, -1);

        $query = "INSERT INTO Teacher_ControlForm(formType, formId, userId) VALUES $str";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return true;
        else
            return false;
    }

    private function rm_formControl($formType, $rm_student)
    {
        $str = "(";
        foreach ($rm_student as $data) {
            $format_ = preg_split('/-/', $formType);
            $studentId = $data['studentId'];
            $formId = $format_[0] . '0' . $format_[1] . $studentId;
            $str .= '\'' . $formId . '\',';
        }

        $str = substr($str, 0, -1);
        $str .= ")";

        $query = "DELETE Student_Form,Student_Question
                    FROM (Teacher_ControlForm LEFT JOIN Student_Form ON Teacher_ControlForm.formId = Student_Form.formId)
                    LEFT JOIN Student_Question ON Teacher_ControlForm.formId = Student_Question.formId WHERE Teacher_ControlForm.formId IN $str";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $query = "DELETE FROM Teacher_ControlForm WHERE formId IN $str";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return true;
        return false;
    }

    public function add_formType($formType)
    {
        $formType = parent::SQL_Injection_check($formType);

        $query = "INSERT INTO Teacher_Form_Type(formType) VALUES (:FormType)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $str = "";
            for ($i = 0; $i < count(_Default::$default_Question); $i++) {
                $str .= '("' . $formType . '",' . $i . ',"' . _Default::$default_Question[$i] . '"),';
            }
            $str = substr($str, 0, -1);
            $query = "INSERT INTO Teacher_QuestionDesign(formType,questionId,questionName) VALUES " . $str;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            if ($stmt->rowCount() > 0)
                return true;
        }
        return false;
    }

    public function remove_formType($formType)
    {
        //first delete form data
        $query = "DELETE Student_Form,Student_Question
                    FROM (Teacher_ControlForm LEFT JOIN Student_Form ON Teacher_ControlForm.formId = Student_Form.formId)
                    LEFT JOIN Student_Question ON Teacher_ControlForm.formId = Student_Question.formId WHERE formType=:FormType";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();

        //second delete primary key of Teacher_ControlForm
        $query = "DELETE FROM Teacher_ControlForm WHERE formType =:FormType";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();

        //third delete question of Teacher_QuestionDesign
        $query = "DELETE FROM Teacher_QuestionDesign WHERE formType =:FormType";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();

        //last delete Teacher_Form_Type
        $query = "DELETE FROM Teacher_Form_Type WHERE formType =:FormType";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return true;
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formControl = new formControl($db);
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'updateWrite') {
            echo $formControl->updateWrite($_POST['formType'], $_POST['checked']);
        } else if ($_POST['action'] == 'saveForm') {

            if (!isset($_POST["Can_student"]))
                $_POST["Can_student"] = [];
            if (!isset($_POST["org_Can_student"]))
                $_POST["org_Can_student"] = [];

            $add_student = array_udiff($_POST["Can_student"], $_POST["org_Can_student"], function ($a, $b) {
                return strcmp($a['studentId'], $b['studentId']);
            });
            $rm_student = array_udiff($_POST["org_Can_student"], $_POST["Can_student"], function ($a, $b) {
                return strcmp($a['studentId'], $b['studentId']);
            });
            echo $formControl->updateFormControl($_POST['formType'], $add_student, $rm_student);
        } else if ($_POST['action'] == 'add_form') {
            $year = $_POST['year'];
            $term = $_POST['term'];
            $alert = "";
            if (!preg_match("/^\d+$/", $year))
                $alert = "格式錯誤, 請聯絡網站管理員";
            else {
                $formType = $year . '-' . $term;
                echo $formControl->add_formType($formType) ? "新增成功" : "新增失敗, 請聯絡網站管理員";
            }
        } else if ($_POST['action'] == 'formRemove') {
            echo $formControl->remove_formType($_POST['formType']) ? "刪除成功" : "刪除失敗, 請聯絡網站管理員";
        }
    }
}
