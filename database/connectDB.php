<?php

$uri = explode("/",$_SERVER["REQUEST_URI"]);
if ($uri[count($uri)-1] == "connectDB.php")
    header('Location: index.php');


$user="dbuser";
$pwd="dbuser123";
$host="localhost";
$db_name="dbPrj";

$dsn="mysql:host=$host;dbname=$db_name";
try
{
    $db=new \PDO($dsn,$user,$pwd);
    $db->query("SET NAMES 'utf8'");
}
catch(\PDOException $e)
{
    $db=null;  //斷開連結
    exit;
}

/*
//使用方法參考PDO的函式

//下面是簡單的方法
//---------------------------

require_once('./connectDB.php'); //把寫好的connectDB.php,有點像是 include 進來,裡面已經先做好連線了  

$query="SELECT * FROM Fix_Step WHERE Fix_Item='輪胎破損' "; //先用一個變數存 SQL 語法
$stmt=$db->prepare($query); //使用預備陳述式 >>>>> mysqli_stmt_prepare()
$stmt->execute(); //執行查詢


//------綁定變數的寫法----------
$CityName='臺北市';

//這個方法適合用在使用者自行輸入的狀況下,為了要避免 SQL注入攻擊(SQL Injection) ,所以在執行前可以先做字串過濾,可使上網查一下相關的資料

$query="SELECT * FROM Weather_Info WHERE CityName=':cityName'"; 
$stmt=$db->prepare($query);
$stmt->bindParam(':cityname',$CityName); 
$stmt->execute();

//

//這個的話可以用在選項之類的,固定字串那種

$query="SELECT * FROM Weather_Info WHERE CityName='$CityName'"; //直接把變數寫進去 
$stmt=$db->prepare($query);
$stmt->execute();

//
-----------------------------


echo $stmt->rowCount().'<br>'; //回傳查詢結果數量

$result=$stmt->fetch(PDO::FETCH_OBJ);
echo $result->Fix_Item;
echo $result->Step;

$stmt=$db=null; //斷開DB連線

*/
?>
