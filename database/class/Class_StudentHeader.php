<?php

require_once('Class_Default.php');
require_once('../connectDB.php');

$query = "SELECT userId,name,email,phoneNum,imagePATH FROM Account WHERE userId=:studentId";
$stmt = $db->prepare($query);
$stmt->bindParam(':studentId', $_SESSION['userid']); 
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<script type="text/javascript">'.
            'var student_name_ = "'.$row['name'].'";'.
            'var student_email = "'.$row['email'].'";'.
            'var student_phoneNum = "'.$row['phoneNum'].'";'.
            'var student_imagePATH = "'.$row['imagePATH'].'";'.
            'var student_studentId = "'.$row['userId'].'";'.
            '</script>';
} else {
    echo '<script type="text/javascript">'.
            'var student_name_ = "無;'.
            'var student_email = "無";'.
            'var student_phoneNum = "無";'.
            'var student_imagePATH = "無";'.
            'var student_studentId = "無";'.
            '</script>';
}

?>