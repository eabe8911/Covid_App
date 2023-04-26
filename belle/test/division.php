<?php

// $text=$_FILES['file']['tmp_name'];
// $text=$text.$_POST["uploadvuser1"];
// echo $text;
print_r($_POST);
print_r($_FILES);
echo $_POST["uploadvuser1"];

// ini_set("display_errors", "on");
// error_reporting(E_ALL);

// if (!isset($_SESSION)) {
//     session_start();
// }

// date_default_timezone_set("Asia/Taipei");
// $today = getdate();
// date("Y-m-d H:i:s");  //日期格式化
// $year = date("Y", strtotime("first day of previous month"));
// $month = date("m", strtotime("first day of previous month"));
// // $year = $today["year"]; //年 
// // $month = $today["mon"]; //月
// $day = $today["mday"];  //日
// //上面得知就是在加上1911 year即可
// $date1 = new DateTime($year . "-" . $month . "-" . $day . " 00:00:00");

// $apdat = $date1->format("Y-m-d H:i:s");

// echo $apdat;

// print_r($_SESSION);
// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//     // $os = array('leslie','michael', 'yusheng', 'jennifer', 'yiting', 'jack', 'tony', 'shelly', 'sylvia', 'cindyT', 'gobby', 'kiki', 'celina', "olive", "belle", "admin");
//     if ($_SESSION["division"] == 0) {
//         echo "hi";
//         // print_r($_SESSION);
//     } else {
//         echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
//         echo '<script language="javascript">window.location.replace("menu.php");</script>';
//     }
// } else {
//     header("location: login.php");
// }


?>
