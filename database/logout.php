<?php
    require_once('class/Class_Default.php');

    setcookie("login", "", time()-86400, "/");
    unset($_SESSION['identity']);
    unset($_SESSION['pwd']);
    header('Location: '._Default::$hostPath.'index.php');
?>