<?php
require_once("Class_Default.php");
require_once('../connectDB.php');

class StudentFrom extends _Default
{

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function backwind($k)
    {
        return _Default::StringDecryption($k);
    }


    public function sendendStatusMail($Username, $formtype, $description,$endStatus)
    {
        if($endStatus==1)
        {
            $endStatus="銷案";
        }
        if($endStatus==0)
        {
            $endStatus="未銷案";
        }
        $Username = parent::SQL_Injection_check($Username);
        $year = preg_split('/-/', $formtype)[0];
        $team = preg_split('/-/', $formtype)[1];
        if ($team == 1) {
            $team = "一";
        }
        if ($team == 2) {
            $team = "二";
        }
        $query = "SELECT name,email FROM Account WHERE userId=:userid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userid', $Username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $url = "http://" . _Default::$hostip;

            $to = $row['email']; //收件者
            $subject = $endStatus."通知信"; //信件標題
            $msg = '<html><head><linkhref="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap"rel="stylesheet"><title>NFU賃居管理系統</title>
                    <style>body{margin:0;padding:0;}div{display:block;}.continer{height:100vh;text-align:center;}.content{font-family:"NotoSansTC",sans-serif;padding:30px;border:1px solid #dadce0;width:550px;height:380px;border-radius:8px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}a{display:inline-block;margin:8px;color:#4a686d;background-color:#f5faff;border-radius:5px;border:1px solid #dadce0;padding:10px 30px 10px 30px;text-decoration:none !important;font-size:20px;-webkit-transition:all 0.2s ease-in-out;-moz-transition:all 0.2s ease-in-out;-ms-transition:all 0.2s ease-in-out;-o-transition:all 0.2s ease-in-out;transition:all 0.2s ease-in-out;}a:-webkit-any-link{color:#4a686d;}a:hover{background-color:#4a686d;color:white;}</style></head><body><div class="continer"><div class="content"><img src="http://nfuaca.nfu.edu.tw/images/%E5%87%BA%E7%89%88%E7%B5%84/%E5%AD%B8%E6%A0%A1logo/nfuLogo_%E7%99%BD%E5%BA%95%E8%97%8D%E5%AD%97.jpg" alt="nfu_Logo">
                    <h1>' . $row['name'] . ' 您好：</h3><h2>' . $year . '學年度第' . $team . '學期<br>賃居校外學生輔導訪問記錄表已銷案<br>可以去NFU賃居管理系統確認<br>導師評語:"' . $description . '"<br></h2><a href="' . $url . '">NFU賃居管理系統</a><h3 style="color:red">請勿直接回覆本郵件，此信件為系統自動發送<br></h3></div></div></body></html>'; //信件內容
            $headers = "Content-type:text/html;charset=UTF-8" . "\r\n"; //設定特殊標頭

            if ($this->sendEmail($to, $subject, $msg, $headers))
                return "SUCCES";
            else
                return  "FAIlEMAIL";
        } else
            return "FAIlESELECT";
    }

    public function INSERT_teacher_description($userId, $formtype, $description, $endStatus)
    {
        $query = "SELECT studentWrite,endStatus,description FROM Teacher_Form_Type INNER JOIN Teacher_Controlform ON Teacher_Form_Type.formType=Teacher_Controlform.formType WHERE Teacher_Form_Type.formType = :formType AND userId =:userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':formType', $formtype);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            if($row['endStatus']==$endStatus)
            {
                if($description==$row['description'])
                {
                    return "fuckyou";
                }
            }
          
        }

        $query = "UPDATE  Teacher_ControlForm SET description = :description,endStatus = :endStatus
            Where formType = :formType AND userId = :userId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':endStatus', $endStatus);
        $stmt->bindParam(':formType', $formtype);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "fuck";
        } 
        else
        {
            return "ERROR";
        }
    }

    public function select_student_From($userId, $formtype)
    {
        $year = preg_split('/-/', $formtype)[0];
        $team = preg_split('/-/', $formtype)[1];

        $query = "SELECT questionName
        FROM Teacher_QuestionDesign 
        WHERE formType = :formType 
        ORDER BY questionId ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':formType', $formtype);
        $stmt->execute();
        $year_team = array($year, $team);
        $question = array();
        $answer = array();
        $img_path = array();
        $student_description = array();
        $teacher_description = "";
        $total = 0;

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($question, $row['questionName']);
                $total++;
            }
        }

        $query = "SELECT Student_Question.questionId,Student_Question.description,Student_Question.imagePATH
        FROM Teacher_ControlForm INNER JOIN Student_Question ON Teacher_ControlForm.formId=Student_Question.formId
        WHERE formType = :formType AND userId = :userId
        ORDER BY questionId ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':formType', $formtype);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $questionId = array();
        $img_path_copy = array();
        $description_copy = array();
        if ($stmt->rowCount() > 0) {
            $max = 0;
            //$row = $stmt->fetch(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($questionId, $row['questionId']);
                array_push($img_path_copy, $row['imagePATH']);
                array_push($description_copy, $row['description']);
                $max++;
            }
            $j = 0;
            for ($i = 0; $i < $total; $i++) {

                if ($max > $j) {
                    if ($i == $questionId[$j]) {
                        array_push($answer, 1);
                        array_push($img_path, $img_path_copy[$j]);
                        array_push($student_description, $description_copy[$j]);
                        $j++;
                    } else {
                        array_push($answer, 0);
                        array_push($img_path, "");
                        array_push($student_description, "");
                    }
                } else {
                    array_push($answer, 0);
                    array_push($img_path, "");
                    array_push($student_description, "");
                }
            }
        } else {
            for ($i = 0; $i < $total; $i++) {
                array_push($answer, 0);
                array_push($img_path, "");
                array_push($student_description, "");
            }
        }
        $query = "SELECT description
        FROM Teacher_ControlForm
        WHERE formType = :formType AND userId = :userId ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':formType', $formtype);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            $teacher_description = $row['description'];
        } else {
            $teacher_description = "";
        }

        $final = array("questiondesign" => $question, "answer" => $answer, "student_description" => $student_description, "imagePATH" => $img_path, "teacher_description" => $teacher_description, "year_team" => $year_team);
        $stmt = null;
        return json_encode($final);
        //echo '<script type="text/javascript">'.$question.$answer.$img_path.$student_description.$teacher_description.'</script>';

    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'getform') {
        $userId = $_POST['userId'];
        $formtype = $_POST['formType'];
        $StudentFrom = new StudentFrom($db);
        echo $StudentFrom->select_student_From($userId, $formtype);
    } elseif ($_POST['action'] == 'insrtform') {
        $userId = $_POST['userId'];
        $formtype = $_POST['formType'];
        $description = $_POST['description'];
        $endStatus = $_POST['endStatus'];
        $StudentFrom = new StudentFrom($db);
        echo $StudentFrom->INSERT_teacher_description($userId, $formtype, $description, $endStatus);
        // echo $StudentFrom->select_student_From($userId,$formtype,$formId,$name);
    } elseif ($_POST['action'] == 'endStatus_email') {
        $userId = $_POST['userId'];
        $formtype = $_POST['formType'];
        $description = $_POST['description'];
        $endStatus = $_POST['endStatus'];
        $StudentFrom = new StudentFrom($db);
        echo $StudentFrom->sendendStatusMail($userId, $formtype, $description,$endStatus);
        // echo $StudentFrom->select_student_From($userId,$formtype,$formId,$name);
    }
    //header('Location: teacher/adm.php');
}
