<?php

require_once("Class_Default.php");
require_once('../connectDB.php');

$query = "SELECT name FROM Account WHERE userId=:username AND admin=1";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $_SESSION['userid']); 
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<script type="text/javascript">'.
            'var teacher_name = "'.$row['name'].'";'.
            '</script>';
} else {
    echo '<script type="text/javascript">'.
            'var teacher_name = "無名";'.
            '</script>';
}

?>