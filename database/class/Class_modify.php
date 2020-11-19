<?php
require_once("Class_Default.php");
require_once('../connectDB.php');

class modify extends _Default
{

public function __construct($db) {
    parent::__construct($db);
}


function INSERT_question_table($question_table, $formtype)
{
    $query = "DELETE FROM Teacher_QuestionDesign WHERE formType=:formtype";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':formtype', $formtype);
    $stmt->execute();
    $str='';
    for($i =0;$i < count($question_table);$i++)
    {
        $description=$question_table[$i]["question"];
        $questionid=$i;
        $str .= '(\'' . $formtype. '\',\'' . $questionid . '\',\'' . $description . '\'),';
    }
    $str = substr($str, 0, -1);
    $query = "INSERT INTO Teacher_QuestionDesign(formType, questionId, questionName) VALUES $str";
    $stmt = $this->db->prepare($query);
    $stmt->execute();

    if ($stmt->rowCount() > 0)
        return true;
    else
        return false;
}

    function select__question_table($formtype)
    {
        $formtype = $this->SQL_Injection_check($formtype);
        $query = "SELECT questionName FROM Teacher_QuestionDesign WHERE formType =:formtype"; 
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':formtype', $formtype);
        $stmt->execute();
        if($stmt->rowCount() > 0)
        {
            $str="var question_table = [";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $str .= '{question : "' . $row['questionName']. '", anser : -1},';
            }
            $str = substr($str, 0, -1);
            $str.="];";
            $stmt=null;
            echo '<script type="text/javascript">'.$str.'</script>';
        }

    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $question_table=$_POST['question_table'];
    $formtype=$_POST['formtype'];
    $modify =new modify($db);
    echo  $modify->INSERT_question_table($question_table, $formtype);
}
?>