<?php

$uri = explode("/", $_SERVER["REQUEST_URI"]);
if ($uri[count($uri) - 1] == "Class_Default.php")
    header('Location: ../index.php');

session_start();

class _Default
{
    protected $db;

    /**
     * $mailFromName 寄信人顯示的名稱
     * $mailFrom 寄信人的信箱
     */
    public static $mailFromName = "NFU賃居管理系統";
    public static $mailFrom = "sendmailnfu@gmail.com";

    //ServerIP
    public static $hostip = "localhost/"; //Lab Server ip 140.130.34.84/projs_109/7/ db-nfu.nctu.me
    public static $hostPath = "/"; //  /projs_109/7/

    //表單預設問題
    public static $default_Question = array(
                                "環境整潔", 
                                "讀書情形", 
                                "與房東相處", 
                                "賃居地點偏僻", 
                                "交友情形", 
                                "交通及生活機能", 
                                "進出人員是否複雜?有無安全疑慮?", 
                                "有無滅火器及會不會使用", 
                                "電熱器(太陽能)", 
                                "有無緊急逃生道及是否暢通", 
                                "有無緊急照明燈", 
                                "有無煙霧探測器"
    );

    /**
     * 建構子
     * @param mixed $db database
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * SQL Injection文字過濾
     * @param mixed $var 要過濾的字串
     * @return String 過濾完的字串
     */
    protected static function SQL_Injection_check($var)
    {
        $replace = array(
            "'" => "&#39;",
            "\"" => "&#34;",
            "\\" => "&#92;"
        );
        return strtr($var, $replace);
    }

    /**
     * 字串隨機產生器
     * @param mixed $length 產生出字串的長度
     * @param mixed $characters 可自訂產生出來的字串裡面有的字元，預設為[0-9][a-z][A-Z]
     * @return String
     */
    public static function randomStringGenerate($length, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * StringToInt
     * call by Reference
     * @param mixed &$str String
     */
    protected static function StringToInt(&$str)
    {
        $str = (int) $str;
    }

    /**
     * 加密字串
     * double base64 crypt
     * @param mixed $str String
     * @return string
     */
    public static function StringEncryption($str)
    {
        $firRan = _Default::randomStringGenerate(10);
        $endRan = _Default::randomStringGenerate(10);
        $out = $firRan.$str.$endRan;
        return base64_encode(base64_encode($out));
    }

    /**
     * 解密字串 string
     * decrypt
     * @param mixed $str String
     * @return string
     */
    public static function StringDecryption($str)
    {
        $out = base64_decode(base64_decode($str));
        $length = strlen($out);
        $ori = substr($out, 10, $length-20);
        return $ori;
    }

    /**
     * 寄送信件
     * 寄件者統一使用專用帳號
     * 
     * @param mixed $to 收件者
     * @param mixed $subject 信件標題
     * @param mixed $msg 信件內容
     * @param mixed $headers 信件標頭, 可而外增加其他參數, 詳情請詢問彥良
     * @return bool 傳送狀態
     */
    protected static function sendEmail($to, $subject, $msg, $headers = "")
    {
        $headers .= "From:"._Default::$mailFromName."<"._Default::$mailFrom.">"; //寄件者
        $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
        if (mail("$to", "$subject", "$msg", "$headers"))
            return true;
        else
            return false;
    }

    /**
     * 解構子
     */
    public function __destruct()
    {
        $this->db = null; //斷開DB連線
    }
}
