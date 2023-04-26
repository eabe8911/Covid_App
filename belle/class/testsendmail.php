<?php
require("Positive.php");
$positive = new Positive();
$UserInfo = [
    "cname" => "中文姓名",
    "fname" => "English",
    "uemail" => "eabe8911@gmail.com"
];
$positive->SendMail($UserInfo);
echo("send success");
?>