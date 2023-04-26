<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    //header("location: welcome.php");
    //header("location: menu.php");
    //    exit;
} else {
    header("location: login.php");
}


require_once '/usr/share/php/vendor/autoload.php';
require_once "/usr/share/php/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php";
require_once ("php/log.php");

function generate_array($data, $Length)
{
    foreach ($data as $item) {
        $arrayLength = count($item);
    }

    $key_array = array();

    // 把 key 變成 array
    for ($x = 1; $x < $arrayLength; $x++) {
        $item = str_replace("\n", "", trim($data[1][$x]));
        array_push($key_array, $item);
    }

    // 每筆資料生成array
    $element_array = array();
    for ($i = 2; $i < $Length; $i++) {
        for ($x = 1; $x < $arrayLength; $x++) {
            // 減一可能是欄位合併的緣故
            $element_array = array_merge($element_array, array($key_array[$x - 1] => $data[$i][$x]));
        }
    }

    return $element_array;
}

function generate_sql_element($array)
{

    //名字
    $english_pattern = "/[a-zA-Z ,]+/";
    $cheinese_pattern = "/\p{Han}+/u";

    preg_match($cheinese_pattern, $array["姓名"], $matches);
    if (!empty($matches)) {
        $cname = trim($matches[0]);
    } else {
        $cname = "";
    }

    preg_match($english_pattern, $array["姓名"], $matches);
    if (!empty($matches)) {
        $fname = trim($matches[0]);
        $lname = "";
    } else {
        $fname = $lname = "";
    }

    $address1 = trim($array["飯店"]) . " " . trim($array["房號"]);
    $address2 = trim($array["住址"]);

    if (trim($array["性別"]) == "女") {
        $sex = "女 / Female";
    } else if (trim($array["性別"]) == "男") {
        $sex = "男 / Male";
    } else {
        $sex = "NA";
    }

    if (trim($array["Email 發出電子報告用"]) != "") {
        $uemail = trim($array["Email 發出電子報告用"]);
        $mailrpt = "1";
    } else {
        $uemail = "";
        $mailrpt = "3";
    }
    // when is $mailrpt = "2";

    $nationality = "";
    // if (trim($array["國籍"]) != "TW") {
    //     $nationality=trim($array["國籍"]);
    // }else{
    //     $nationality="";
    // }

    if (trim($array["國籍"]) != "TW") {
        $residentpermit = "N";
        $ctzn = 2;
        $mobilerpt = "N";
        $hbrpt = $cloudrpt = "N";
        $hbrptyear = $cloudrptyear = "0";
    } else {
        $residentpermit = "";
        $ctzn = 1;
        $mobilerpt = "Y";
        $hbrpt = $cloudrpt = "Y";
        $hbrptyear = $cloudrptyear = "999";
    }
    // when is $residentpermit = "N";

    $mobile = str_replace(" ", "", str_replace("-", "", strval(trim($array["手機"]))));


    $item5 = explode("/", trim($array["身分證/護照"]));
    if (trim($array["國籍"]) == "TW") {
        $userid = str_replace(" ", "", trim($item5[0]));
        $pattern = "/[0-9a-zA-Z]+/";
        preg_match($pattern, $userid, $matches);
        if (!empty($matches)) {
            $userid = $matches[0];
        }
        $passportid = str_replace(" ", "", trim($item5[1]));
    } else {
        $userid = "";
        $passportid = str_replace(" ", "", trim($item5[0]));
    }

    if (trim($array["健保"]) == "是") {
        $hiflag = 'Y';
    } else {
        $hiflag = 'N';
    }


    // echo $item[1];//採檢日
    date_default_timezone_set("Asia/Taipei");
    $today = getdate();
    date("Y-m-d H:i:s");  //日期格式化
    $year = $today["year"]; //年 
    $month = $today["mon"]; //月
    $day = $today["mday"];  //日

    if (strlen($month) == '1') $month = '0' . $month;
    if (strlen($day) == '1') $day = '0' . $day;
    $today = $year . "-" . $month . "-" . $day;

    $date = new DateTime($today);
    $sfdat = $efdat = $date->format('Y-m-d');

    $year_chinese = $year - 1911;

    $item1_1 = explode("月", trim($array["採檢日"]));
    // print_r($item1_1);
    if (strlen(strval($item1_1[0])) == 2) {
        $item1_month = strval($item1_1[0]);
    } else {
        $item1_month = "0" . strval($item1_1[0]);
    }
    if (strlen(strval((str_replace("日", "", ($item1_1[1]))))) == 2) {
        $item1_day = strval((str_replace("日", "", ($item1_1[1]))));
    } else {
        $item1_day = "0" . strval((str_replace("日", "", ($item1_1[1]))));
    }

    //上面得知就是在加上1911 year即可
    $year_chinese_int = intval($year_chinese);
    $year_chinese_int = $year_chinese_int + 1911;

    $date1 = new DateTime($year_chinese_int . "-" . $item1_month . "-" . $item1_day);

    $apdat = $date1->format("Y-m-d");
    $sendname = strval(($year - 2000)) . $item1_month . $item1_day . "01";


    // echo $item[6];//民國出生年月日

    date_default_timezone_set("Asia/Taipei");
    $item6_1 = explode(".", trim($array["民國出生年月日"]));
    $item6_year = trim(strval($item6_1[0]));
    if (strlen(strval($item6_1[1])) == 2) {
        $item6_month = strval($item6_1[1]);
    } else {
        $item6_month = "0" . strval($item6_1[1]);
    }
    if (strlen(strval(strval(($item6_1[2])))) == 2) {
        $item6_day = strval(($item6_1[2]));
    } else {
        $item6_day = "0" . strval(($item6_1[2]));
    }

    $item6_year_int = intval($item6_year);
    $item6_year_int = $item6_year_int + 1911;
    $date6 = new DateTime($item6_year_int . "-" . $item6_month . "-" . $item6_day);

    $dob = $date6->format("Y-m-d");

    if (trim($array["檢測結果"]) == "(N) 陰性") {
        $ftest = "negative";
    } else if (trim($array["檢測結果"]) == "(P) 陽性") {
        $ftest = "positive";
    } else {
        $ftest = "";
    }


    $nihrpt = $pcrtest = $telephone = $sampleid1 = $sampleid2 = $xmapdat = $mtpid = $vuser1 = $vuser2 = $xmrpturgency = $hicardno = $cmobile = $xmemail = $fpdfflag = $pcrpdfflag = $xlspcrtest2 = "";
    $smsflag = $emailflag = $cdcflag = $payflag = $xmappoint = $hiflag = "N";
    $approval = "Y";
    $type = 1;
    $testtype = 2;
    $twrpturgency = "normal";
    $testreason = '7';
    $tdat = $rdat = $frptflag = $qrptflag = null;
    // null
    // $tdat=$rdat=$frptflag=$qrptflag=;
    // 這裡開始是讀到的每一筆資料結束
    // $item["Bardcode貼紙"]
    // $item["是否有 LINE"]

    return [
        $sfdat, $efdat, $userid, $sex, $cname, $fname, $lname, $dob, $mobile, $uemail, $apdat, $ftest, $pcrtest, $smsflag, $emailflag, $type, $telephone, $address2, $testtype, $ctzn, $passportid,
        $sampleid1, $cdcflag, $sampleid2, $residentpermit, $xmapdat, $mtpid,
        $vuser1, $vuser2, $payflag, $xmappoint, $twrpturgency, $xmrpturgency, $sendname,
        $hicardno, $testreason, $mobilerpt, $mailrpt, $hbrpt, $hbrptyear, $cloudrpt, $cloudrptyear, $nihrpt,
        $cmobile, $xmemail, $approval, $address1, $hiflag, $fpdfflag, $pcrpdfflag, $nationality, $xlspcrtest2, $tdat, $rdat, $frptflag, $qrptflag
    ];
}

if (isset($_POST["submit_excel"])) {
    if (trim($_POST["page"]) != "") {
        $page = intval(trim($_POST["page"])) - 1;
    } else {
        $page = 0;
    }

    $spreadsheet = IOFactory::load($_FILES["excel_file"]["tmp_name"]);

    $data = $spreadsheet
        ->getSheet($page) // 指定第一个工作表为当前
        ->toArray();  // 转为数组

    //取得data長度
    $dataLength = count($data);

    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $count = 0;

    for ($x = 0; $x < $dataLength; $x++) {

        $array = generate_array($data, $x);

        if (array_key_exists('姓名', $array) && $array['姓名'] != null) {

            [
                $sfdat, $efdat, $userid, $sex, $cname, $fname, $lname, $dob, $mobile, $uemail, $apdat,
                $ftest, $pcrtest, $smsflag, $emailflag, $type, $telephone, $address2, $testtype, $ctzn, $passportid,
                $sampleid1, $cdcflag, $sampleid2, $residentpermit, $xmapdat, $mtpid,
                $vuser1, $vuser2, $payflag, $xmappoint, $twrpturgency, $xmrpturgency, $sendname,
                $hicardno, $testreason, $mobilerpt, $mailrpt, $hbrpt, $hbrptyear, $cloudrpt, $cloudrptyear, $nihrpt,
                $cmobile, $xmemail, $approval, $address1, $hiflag, $fpdfflag, $pcrpdfflag, $nationality, $xlspcrtest2, $tdat, $rdat, $frptflag, $qrptflag
            ] = generate_sql_element($array);

            if (trim($userid) != "") {
                $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
                                                                                        and    userid = ? 
                                                                                        and    apdat=?";
            } elseif (trim($passportid) != "") {
                $sql = "SELECT uuid,passportid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
                                                                                        and    passportid = ? 
                                                                                        and    apdat=?";
            }
            // 3
            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $p2, $p3);

                // Set parameters

                if (trim($userid) != "") {
                    $p2 = $userid;
                } elseif (trim($passportid) != "") {
                    $p2 = $passportid;
                }
                $p3 = $apdat;

                // Attempt to execute the prepared statement
                //4
                if (mysqli_stmt_execute($stmt)) {
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Check if username exists, if yes then verify password
                    //5
                    if (mysqli_stmt_num_rows($stmt) == 0) {

                        // $array=generate_array($data,$x);
                        // [$sfdat,$efdat,$userid,$sex,$cname,$fname,$lname,$dob,$mobile,$uemail,$apdat,
                        // $ftest,$pcrtest,$smsflag,$emailflag,$type,$telephone,$address2,$testtype,$ctzn,$passportid,
                        // $sampleid1,$cdcflag,$sampleid2,$residentpermit,$xmapdat,$mtpid,
                        // $vuser1,$vuser2,$payflag,$xmappoint,$twrpturgency,$xmrpturgency,$sendname,
                        // $hicardno,$testreason,$mobilerpt,$mailrpt,$hbrpt,$hbrptyear,$cloudrpt,$cloudrptyear,$nihrpt,
                        // $cmobile,$xmemail,$approval,$address1,$hiflag,$fpdfflag,$pcrpdfflag,$nationality,$xlspcrtest2,$tdat,$rdat,$frptflag,$qrptflag] =generate_sql_element($array);

                        $sql2 = "INSERT INTO covid_trans 
                        VALUES (0,'$sfdat','$efdat','$userid','$sex','$cname',
                        '$fname','$lname','$dob','$mobile','$uemail','$apdat',
                        '$ftest','$pcrtest','$smsflag','$emailflag','$type',
                        '$telephone','$address2','$testtype','$ctzn','$passportid',
                        '$sampleid1','$cdcflag','$sampleid2','$residentpermit','$xmapdat','$mtpid',
                        '$vuser1','$vuser2','$payflag','$xmappoint',null,'$twrpturgency','$xmrpturgency',null,'$sendname',null,null,
                        '$hicardno','$testreason','$mobilerpt','$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt', 
                        '$cmobile','$xmemail','$approval', '$address1', '$hiflag', '$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2')";
                        // mysqli_stmt_close($stmt);
                        //echo $sql2;
                        if (mysqli_query($conn, $sql2)) {
                            //echo "New record created successfully";
                            $count = $count + 1;
                            $sql_comment = $_SESSION["username"] . ": " . $sql2;
                            write_sql($sql_comment);
                            
                            //echo "New record created successfully";
                            $count = $count + 1;
                        } else {
                            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
                            $sql_comment = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
                            write_sql($sql_comment);
                        }
                    }  //5// sql row count >0, if the data did not been used. The row can delete.
                    //6
                    else {
                        mysqli_stmt_bind_result($stmt, $uuid, $userid, $sampleid1, $sampleid2);
                        //echo $uuid, $userid,$sampleid1,$sampleid2."<br>";
                        while (mysqli_stmt_fetch($stmt)) {
                            if (empty(trim($sampleid1)) && empty(trim($sampleid2))) {
                                $sql3 = "delete from covid_trans where uuid=?";
                                if ($stmt3 = mysqli_prepare($conn, $sql3)) {
                                    mysqli_stmt_bind_param($stmt3, "i", $p10);
                                    $p10 = $uuid;
                                    mysqli_stmt_execute($stmt3);
                                    $sql_comment = $_SESSION["username"] . ": " . $sql3;
                                    write_sql($sql_comment);
                                }
                                mysqli_stmt_close($stmt3);
                            }
                        }

                        // $array=generate_array($data,$x);
                        // [$sfdat,$efdat,$userid,$sex,$cname,$fname,$lname,$dob,$mobile,$uemail,$apdat,
                        // $ftest,$pcrtest,$smsflag,$emailflag,$type,$telephone,$address2,$testtype,$ctzn,$passportid,
                        // $sampleid1,$cdcflag,$sampleid2,$residentpermit,$xmapdat,$mtpid,
                        // $vuser1,$vuser2,$payflag,$xmappoint,$twrpturgency,$xmrpturgency,$sendname,
                        // $hicardno,$testreason,$mobilerpt,$mailrpt,$hbrpt,$hbrptyear,$cloudrpt,$cloudrptyear,$nihrpt,
                        // $cmobile,$xmemail,$approval,$address1,$hiflag,$fpdfflag,$pcrpdfflag,$nationality,$xlspcrtest2,$tdat,$rdat,$frptflag,$qrptflag] =generate_sql_element($array);

                        $sql2 = "INSERT INTO covid_trans 
                        VALUES (0,'$sfdat','$efdat','$userid','$sex','$cname',
                        '$fname','$lname','$dob','$mobile','$uemail','$apdat',
                        '$ftest','$pcrtest','$smsflag','$emailflag','$type',
                        '$telephone','$address2','$testtype','$ctzn','$passportid',
                        '$sampleid1','$cdcflag','$sampleid2','$residentpermit','$xmapdat','$mtpid',
                        '$vuser1','$vuser2','$payflag','$xmappoint',null,'$twrpturgency','$xmrpturgency',null,'$sendname',null,null,
                        '$hicardno','$testreason','$mobilerpt','$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt', 
                        '$cmobile','$xmemail','$approval', '$address1', '$hiflag', '$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2')";
                        if (mysqli_query($conn, $sql2)) {
                            //echo "New record created successfully";
                            $count = $count + 1;
                            $sql_comment = $_SESSION["username"] . ": " . $sql2;
                            write_sql($sql_comment);
                        } else {
                            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
                            $sql_comment = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
                            write_sql($sql_comment);
                        }
                    } //6
                } //4
            } //3 

            echo "<br>第 $x 筆資料: ";
            foreach ($array as $key => $value) {
                // $text=$key ."->". $value
                echo "$key -> $value";
            }
        }
    } //2
    mysqli_close($conn);
    echo "<br>total uploaded rows:" . $count . "<br>";
} //1

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
</head>

<body>
    <div>
        <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁"></input>
        <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁"></input>
        <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版首頁"></input>

    </div>
</body>

</html>