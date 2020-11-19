<?php
//paid已繳交
//unpaid未繳交
//end已銷案
//notend未銷案
require_once("Class_Default.php");
require_once('../connectDB.php');

class adminManage extends _Default{
    public function __construct($db) {
        parent::__construct($db);
    }

    public function get_last_form($formType = -1){
        if ($formType != -1){
            $year = preg_split('/-/',$formType)[0];
            $term = preg_split('/-/',$formType)[1];

            echo '<script type="text/javascript">' . 
            'var Title = "'.$year.'年第'.$term.'學期學生表單";</script>';
            return $formType;
        }
        $query = "SELECT formType FROM Teacher_Form_Type ORDER BY formType DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $formType = $row['formType'];
            $year = preg_split('/-/',$formType)[0];
            $term = preg_split('/-/',$formType)[1];

            echo '<script type="text/javascript">' . 
            'var formType = "'.$formType.'";var Title = "'.$year.'年第'.$term.'學期學生表單";</script>';
            return $formType;
        } else {
            echo '<script type="text/javascript">' . 
            'var Title = "無搜尋到任何表單"; </script>';
            return "-1";
        }
    }

    public function select_all_form($formType){
        $formType = parent::SQL_Injection_check($formType);
        $query = "SELECT * FROM (Teacher_ControlForm LEFT JOIN Student_Form ON Teacher_ControlForm.formId = Student_Form.formId)
                                INNER JOIN Account ON Teacher_ControlForm.userId = Account.userId
                                WHERE formType=:FormType ORDER BY Teacher_ControlForm.userId ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':FormType', $formType);
        $stmt->execute();
        $i = 0;
        if ($stmt->rowCount() > 0){
            
            echo '<script type="text/javascript">' . 
                'var student_form_content = "";';
            for ($i=0,$row = $stmt->fetch(PDO::FETCH_ASSOC);$row;$i++,$row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $img = isset($row['imagePATH']) ? $row['imagePATH'] : "https://www.w3schools.com/bootstrap4/img_avatar1.png";
                $isPaid = $row['sendStatus'] ? " paid" : " unpaid";
                $isEnd = $row['endStatus'] ? " end" : " notend";
                $paidText = $row['sendStatus'] ? "表單已繳交" : " 表單未繳交";
                $endText = $row['endStatus'] ? "已銷案" : "未銷案";
                $color = $row['sendStatus'] ? "green" : "red";
                $send_icon = $row['sendStatus'] ? "glyphicon-ok-sign lg" : "glyphicon-remove-sign";

                /*if($i % 10 == 0)
                {
                    if ($i == 0){
                        echo 'student_form_content += \'<div class="page" id="page' . ($i / 10) . '">\';';
                    }else{
                        echo 'student_form_content += \'<div class="page hid" id="page' . ($i / 10) . '">\';';
                    }
                }*/
                echo 'student_form_content += \'<div class="list-group" style="margin:0 ;">' .
                '<a onclick="close_other_row(collapse'.$i.')" data-toggle="collapse" href="#collapse' . $i . '" class="row list-group-item'. $isPaid.''.$isEnd.'" style="height: 50px;line-height:30px;">' .
                    '<span class="col-sm-2">' .
                        '<img src="'. $img .'" style="border-radius:50%;width:35px;height:35px;">' .
                    '</span>' .
                    '<span class="col-sm-3">' .
                        '<label style="font-size: 16px;" id="student_name">' . $row['name'] . '</label>' .
                    '</span>' .
                    '<span class="col-sm-6">' .
                        '<label style="font-size: 16px;" id="student_Id">' . $row['userId'] . '</label>' .
                    '</span>' .
                    '<span class="col-sm-1 glyphicon '. $send_icon . '" style="font-size: 20px;color:' . $color . ';">' .
                    '</span>' .
                '</a>' .
                '<div id="collapse'. $i .'" class="panel-collapse collapse row">' .
                    '<ul class="list-group">' .
                        '<div class="col-sm-12 list-group-item" style="margin: 0px 0 0 0;border-bottom: 0px;border-top:0px;">' .
                            '<span class="col-sm-3" id="form_Tybe">' .
                                $row['formType'] .
                            '</span>' .
                            '<span class="col-sm-3">' .
                                $paidText .
                            '</span>' .
                            '<span class="col-sm-3" >' .
                                '班級:<font id="class_Name">'. $row['class'] . '</font>' .
                            '</span>' .
                            '<span class="col-sm-3" >' .
                                '聯絡電話:<font id="stu_phone">0978778888</font>' .
                            '</span>' .
                        '</div>' .
                        '<div class="col-sm-12 list-group-item" style="border-top: 0px;border-bottom:0px;">' .
                            '<span class="col-sm-3" >' .
                                '租屋地址:<font id="address">'.$row['address'] . '</font>' .
                            '</span>' .
                            '<span class="col-sm-3" id="end_Status_'. $i .'">' .
                                $endText .
                            '</span>' .
                            '<span class="col-sm-3" >' .
                                '屋主:<font id="landlordName">'. $row['landlordName'] . '</font>' .
                            '</span>' .
                            '<span class="col-sm-3" >' .
                                '房東電話:<font id="landlordPhone">'.  $row['landlordPhone'] . '</font>' .
                            '</span>' .
                        '</div>' .
                        '<div class="col-sm-12 list-group-item" style="border-top: 0px;text-align:center">' .
                            '<hr style="margin:0 0 10px 0;border-top:1px solid gray">' .
                            '<a onclick="get_form('.$i.')" class="btn btn-info">表單內容</a>' .
                        '</div>' .
                        '<div class="col-sm-3" style="display:none" id="date">' . $row['Date'] . '</div>' . 
                        '<div class="col-sm-3" style="display:none" id="visitDateTime">' . $row['visitDateTime'] . '</div>' .
                        '<div class="col-sm-3" style="display:none" id="sendStatus">' . $row['sendStatus'] . '</div>' . 
                        '<div class="col-sm-3" style="display:none" id="endStatus1">' . $row['endStatus'] . '</div>' .
                    '</ul>' .    
                '</div>' .
                '</div>\';';

                    /*if($i % 10 == 9)
                    {
                        echo 'student_form_content += \'</div>\';';
                    }
                    $i++;*/
            }

            /*echo 'student_form_content += \' </div><div class="col-sm-12" style="text-align:center"> <ul class="pagination"> <li class="page_li"><a onclick="change_page(\\\'pre\\\','. $i/10 .')" href="#">Previous</a></li>\';';
            for($i = 0; $i < $i/10;$i++)
            {
                echo  'student_form_content += \'<li class="page_li"><a onclick="change_page(' . $i . ',' . $i/10 . ')" href="#">' .$i .'</a></li>\';';
            }
            echo 'student_form_content += \' <li class="page_li"><a onclick="change_page(\\\'next\\\',' . $i/10 . ')" href="#">Next</a></li></ul></div> \';';*/
            echo 'student_form_content += \'</div>\';';

            echo '</script>';
        } else {
            
            echo '<script type="text/javascript">' . 
                'var student_form_content = "";' . 
                '</script>';
        }
    }

}
