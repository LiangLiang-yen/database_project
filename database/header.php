<?php
/**
 * 檢查登入狀態，以及確認身分
 * use : require_once("../header.php"); 
 *       相對路徑請依照資料表所在位置進行修改
 */

    //檢查欄位是否有缺少
    if (isset($_COOKIE["login"]) && isset($_SESSION['userid']) && isset($_SESSION['identity'])){

        //解析REQUEST_URI的字串,回傳array
        $uri = explode("/",$_SERVER["REQUEST_URI"]);

        //uri array的數量
        if (count($uri) >= 3){
            
            //辨別身分是否正確
            if ($uri[count($uri)-2] == $_SESSION['identity'])
                //進入到其他頁面的話，會重置cookie到30分鐘
                setcookie("login", "True", time()+1800, "/");
            else
                redirect();
        }else
            redirect();
    } else {
        redirect();
    }

    //導回登入畫面
    function redirect(){
        setcookie("login", "", time()-86400, "/");
        unset($_SESSION['identity']);
        unset($_SESSION['pwd']);
        header('Location: '._Default::$hostPath.'index.php');
    }

/**
 * SET COOKIE
 * setcookie("變數名稱", "VALUE", time()+3600 minute); 1hr = 3600s
 * 
 * GET COOKIE
 * $_COOKIE["變數名稱"];
 * 
 * DEL COOKIE 
 * setcookie("變數名稱", "", time()-3600);
 * 注意!除了時間要減掉以外，VALUE一定要設為空值，否則刪不掉
 * 
 * SET SESSION
 * $_SESSION['變數名稱'] = VALUE;
 * 
 * GET SESSION
 * $_SESSION["變數名稱"];
 * 
 * DEL SESSION
 * 1.unset($_SESSION['變數名稱']);
 * 2.session_destroy();
*/
?>

