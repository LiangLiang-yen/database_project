<?php
//Class_student_form

require_once("Class_Default.php");
require_once('../connectDB.php');

class studentForm extends _Default
{
    private $userId;
    // private $formId;


    function __construct($db)
    {
        parent::__construct($db);
        $this->userId = $_SESSION['userid'];
    }

    private function DBexecute($query)
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function showFormList()
    {
        $formInfo = array();
        //SELECT * FROM `Teacher_ControlForm` INNER JOIN Teacher_Form_Type ON Teacher_ControlForm.formType=Teacher_Form_Type.formType WHERE Teacher_ControlForm.userId="40643102" ORDER BY `Teacher_ControlForm`.`formType` ASC
        //$query = "SELECT * FROM Teacher_ControlForm WHERE userId=$userId";
        $query = "SELECT * FROM Teacher_ControlForm INNER JOIN Teacher_Form_Type ON Teacher_ControlForm.formType=Teacher_Form_Type.formType WHERE Teacher_ControlForm.userId='$this->userId' ORDER BY Teacher_ControlForm.formType DESC";
        $stmt = $this->DBexecute($query);

        if ($stmt->rowCount() != 0) {
            array_push($formInfo, array('haveData' => 1));
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                array_push(
                    $formInfo,
                    array(
                        'formType' => $result['formType'],
                        'endStatus' => $result['endStatus'],
                        'sendStatus' => $result['sendStatus'],
                        'canWrite' => $result['studentWrite']
                    )
                );
                // $_SESSION['formId'] = $result['formId'];
            }
        } else {
            array_push($formInfo, array('haveData' => 0));
        }
        return json_encode($formInfo);
    }


    public function showFormQuestion($formType)
    {
        $formQuestion = array("formIsset" => "", "questionForm" => array(), "exceptQuestion" => array(), 'teacherDescription' => "");
        $exceptQuestion = array();

        $formId = str_replace('-', '0', $formType) . $this->userId;

        $teacherDescriptionQuery = "SELECT `description` FROM `Teacher_ControlForm` WHERE formId='$formId'";
        $stmt_t = $this->DBexecute($teacherDescriptionQuery);
        if ($stmt_t->rowCount() != 0) {
            while ($result_t = $stmt_t->fetch(\PDO::FETCH_ASSOC)) {
                $formQuestion['teacherDescription'] = $result_t['description'];
            }
        }

        $exceptQuestionquery = "SELECT * FROM `Student_Question` WHERE formId='$formId'";
        $stmt_ex = $this->DBexecute($exceptQuestionquery);
        if ($stmt_ex->rowCount() != 0) {

            while ($result_ex = $stmt_ex->fetch(\PDO::FETCH_ASSOC)) {
                if (empty($result_ex['imagePATH']))
                    $image = "";
                else
                    $image = $result_ex['imagePATH'];

                array_push(
                    $exceptQuestion,
                    array(
                        'num' => $result_ex['questionId'],
                        'description' => $result_ex['description'],
                        'imgPATH' => $image
                    )
                );
            }
        }

        $query = "SELECT * FROM Teacher_QuestionDesign WHERE formType='$formType'";
        $stmt = $this->DBexecute($query);
        if ($stmt->rowCount() != 0) {
            $formQuestion['formIsset'] = 1;
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                array_push(
                    $formQuestion['questionForm'],
                    array(
                        "questionId" => $result['questionId'],
                        'questionName' => $result['questionName']
                    )
                );
            }
            $formQuestion['exceptQuestion'] = $exceptQuestion;
        } else {
            $formQuestion['formIsset'] = 0;
        }
        return json_encode($formQuestion);
    }

    public function getStudentProfile($formType)
    {
        $formType_split = explode("-", $formType);
        if ($formType_split[1] == 1)
            $term = '一';
        else if ($formType_split[1] == 2)
            $term = '二';

        $formId = str_replace('-', '0', $formType) . $this->userId;

        $studentProfile = array();
        $address = "";
        $landlordName = "";
        $landlordPhone = "";
        $studentclass = "";
        $query = "SELECT `userId`,`name` FROM Account WHERE userId='$this->userId'";
        $stmt = $this->DBexecute($query);
        if ($stmt->rowCount() != 0) {
            array_push($studentProfile, array("findProfile" => 1));
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $query_inside = "SELECT `address`, `landlordName`, `landlordPhone`, `class` FROM `Student_Form` WHERE `formId`='$formId'";
                $this->errorlog("query_inside" . $query_inside);
                $stmt_inside = $this->DBexecute($query_inside);

                if ($stmt_inside->rowCount() != 0) {
                    while ($result_inside = $stmt_inside->fetch(\PDO::FETCH_ASSOC)) {
                        $address = $result_inside['address'];
                        $landlordName = $result_inside['landlordName'];
                        $landlordPhone = $result_inside['landlordPhone'];
                        $studentclass = $result_inside['class'];
                    }
                }
                array_push(
                    $studentProfile,
                    array(
                        "userId" => $result['userId'],
                        "name" => $result['name'],
                        'year' => $formType_split[0],
                        'term' => $term,
                        'studentclass' => $studentclass,
                        'landlordName' => $landlordName,
                        'landlordPhone' => $landlordPhone,
                        'address' => $address
                    )
                );
            }
        } else {
            array_push($studentProfile, array("findProfile" => 0));
        }
        return json_encode($studentProfile);
    }

    public function insertData($jsonStr)
    {
        $this->errorlog("test");
        $status = array("status" => '');
        $data = json_decode($jsonStr, 1);
        $landlordName = parent::SQL_Injection_check($data['landlordName'][0]);
        $landlordPhone = parent::SQL_Injection_check($data['landlordPhone'][0]);
        $address = parent::SQL_Injection_check($data['address']);
        $studentclass = parent::SQL_Injection_check($data['studentclass']);

        $formId = $data['formId'];

        $SYSdate = date("Y-m-d");

        $stmt_i_flag = TRUE;
        $stmt_u_flag = TRUE;
        $checkQuery = "SELECT `formId` FROM  Student_Form  WHERE `formId`='$formId'";
        //假設DB有基本資料，先刪除原本的問題，更新基本資料
        if ($this->DBexecute($checkQuery)->rowCount() != 0) {
            $this->deleteQuestion($formId); //刪除有異常問題
            $updateQuery = "UPDATE `Student_Form` SET `address`='$address',`landlordName`='$landlordName',`landlordPhone`='$landlordPhone',`class`='$studentclass',`Date`='$SYSdate' WHERE `formId`='$formId'";
            $this->errorlog("updateQuery:" . $updateQuery);
            $stmt_u = $this->DBexecute($updateQuery);
            if ($stmt_u->errorCode() != '00000') {
                $stmt_u_flag = FALSE;
            }
        } else {
            $query = "INSERT INTO Student_Form(`address`,`landlordName`,`landlordPhone`,`formId`,`class`,`date`) VALUES ('$address','$landlordName','$landlordPhone','$formId','$studentclass','$SYSdate')";
            $stmt_i = $this->DBexecute($query);
            if ($stmt_i->errorCode() != "00000") {
                $stmt_i_flag = FALSE;
                array_push($status, "insert Error" . $stmt_i->errorCode() . " errorInfo " . $stmt_i->errorInfo()[2], $stmt_i_flag);
            }
        }

        $insertQuestion_flag = TRUE;
        $insertQuestion_count = 0;
        $arrayLength = count($data['question']);
        if (!empty($data['question'][0])) {
            for ($i = 0; $i < $arrayLength; $i++) {
                $qId = $data['question'][$i]['num'];
                $description = $data['question'][$i]['description'];
                $imagePATH = $data['question'][$i]['image'];


                if (preg_match("#^data:image/\w+;base64,#i", $imagePATH)) {
                    list($type, $imagePATH) = explode(';', $imagePATH);
                    //unlink("../student_image/40643146_E3semVyzDm.png");
                    list(, $imagePATH)      = explode(',', $imagePATH);
                    $imgPathbase64 = base64_decode($imagePATH);
                    $imagePATH = '../exceptQuestion/' . $qId . '_' . $formId . '.jpeg';
                    //clearstatcache();
                    file_put_contents($imagePATH, $imgPathbase64);
                } else
                    $imagePATH=str_replace('http://' . _Default::$hostip, '../', $imagePATH);

                $query = "INSERT INTO Student_Question(`formId`,`questionId`,`description`,`imagePATH`) VALUES ('$formId','$qId','$description','$imagePATH')";
                array_push($status, "insertQuestion query: " . $query);
                $stmt_q = $this->DBexecute($query);
                if ($stmt_q->errorCode() == "00000")
                    $insertQuestion_count++;
                else
                    array_push($status, '[insertQuestion]SQL error:' . $i . ". " . $stmt_q->errorCode() .  " errorInfo " . $stmt_q->errorInfo()[2] . " arraylength:" . $arrayLength);
            }
            if ($insertQuestion_count != $arrayLength)
                $insertQuestion_flag = FALSE;
        }

        //array_push($status, "insetQcount:" . $insertQuestion_count . " arraylength:" . $arrayLength . " insertQflag:" . $insertQuestion_flag);

        if (($stmt_i_flag || $stmt_u_flag) && $insertQuestion_flag) {
            $query = "UPDATE `Teacher_ControlForm` SET `sendStatus`= 1 WHERE `formId`='$formId'";
            $this->DBexecute($query);
            $status['status'] = "success";
        } else {
            $status['status'] = "fail";
        }

        return json_encode($status);
    }

    private function deleteQuestion($formId)
    {
        $query = "DELETE FROM `Student_Question` WHERE `formId`='$formId'";
        $stmt = $this->DBexecute($query);
        if (!empty($stmt->errorCode()))
            $this->errorlog("deleteError" . $stmt->errorCode());
    }
    private function errorlog($message)
    {
        $message = "Hi this is errorlogTest";
        shell_exec("echo '$message' >> /tmp/error.log");
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $studentFormInfo = new studentForm($db);
    switch ($_POST['actionForm']) {
        case 'loadList':
            echo $studentFormInfo->showFormList();
            break;
        case 'loadQuestion':
            echo $studentFormInfo->showFormQuestion($_POST['formType']);
            break;
        case 'getProfile':
            echo $studentFormInfo->getStudentProfile($_POST['formType']);
            break;
        case 'insertData':
            echo $studentFormInfo->insertData($_POST['jsonStr']);
            break;
        default:

            break;
    }
}
