<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// 2207180900 李天鐸表單，修改讀取欄位 modified by olive


ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // header("location: welcome.php");
    // header("location: menu.php");
    //    exit;
} else {
    header("location: login.php");
}

require_once "php/log.php";
// Connect to local db



// upload files
if (!isset($_POST["submit_file"])) {
	return;
}

$getDate = date("Y-m-d");
$target_dir = "php/log/";
$target_file = $target_dir . $getDate.basename($_FILES["file"]["tmp_name"]);
move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
$file = $target_dir.$getDate.basename($_FILES["file"]["tmp_name"]);
$file_open = fopen($file, "r");
$myline = 0;
$count = 0;
try {
    $dbname = "libodb";
    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, $dbname);
    $payload = fgetcsv($file_open, 6000, ",");
    // print_r($payload);echo('<br>');
	$file_open = fopen($file, "r");
	while (($csv = fgetcsv($file_open, 6000, ","))) {
        if ($myline < 1) {
            $myline = $myline + 1;
            continue;
        }
        if(empty($csv[0])){
            continue;
        }
        //approval 同意攜帶證件
        $approval = "Y";
    
        //userid 身分證
        if (!empty(trim($csv[2]))) {
            $userid = strtoupper(str_replace(" ", "", $csv[2]));
        } else {
            $userid = "";
        }
            
        //大陸48小時採檢次數 //olive
        // if (str_replace(" ", "", $csv[10]) == "第一次採檢") {
        // 	$xmappoint = "第一次採檢";
        // }elseif(str_replace(" ", "", $csv[10]) == "第二次採檢") {
        // 	$xmappoint = "第二次採檢";
        // }else{
        // 	$xmappoint = "非大陸專案客戶，不適用";
        // }
     
        //passportid 護照
        $passportid = "";
    
        //mtpid 台胞證
        $mtpid = "";
        
        //日本報告國籍欄位
        $nationality = "";
    
        //apdat 預約日期
        $apdat =  date('Y-m-d', strtotime($csv[0]));
        //$tdat =  date('Y-m-d h:i:s', strtotime($csv[4]));
        //echo($tdat);die();
        //$rdat =  "1970-01-01 00:00:00";
        $sampleid2 = "";

        //如果身分證有資料
        if (trim($userid) <> "") {
            $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 and userid = ? and apdat=?";
                                                                            //and    xmappoint=?";//olive
        } else {
            $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 and userid = ? and apdat=?";
        }
            
        $stmt = mysqli_prepare($conn, $sql);
            // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $p2, $p3);//olive

        // Set parameters
        //$p1 = $uuid;
        //$p1 = $userid;
        // 061121 修正可能passport 沒填，檢查時有回傳的bug
        if (trim($userid) <> "") {
            $p2 = $userid;
        } elseif (trim($passportid) <> "") {
            $p2 = $passportid;
        }
        $p3 = $apdat;
        // $p4 = $xmappoint; //olive

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
            // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify passport

        if(mysqli_stmt_num_rows($stmt) <> 0){
            //echo("find double record!<br>");
            mysqli_stmt_bind_result($stmt, $uuid, $userid, $sampleid1, $sampleid2);
            //echo $uuid, $userid,$sampleid1,$sampleid2."<br>";
            while (mysqli_stmt_fetch($stmt)) {
                if (empty(trim($sampleid1)) && empty(trim($sampleid2))) {
                    $sql3 = "delete from covid_trans where uuid=?";
                    //echo $sql3."<br>";
                    $p10 = $uuid;
                    if ($stmt3 = mysqli_prepare($conn, $sql3)) {
                        mysqli_stmt_bind_param($stmt3, "i", $p10);
                        mysqli_stmt_execute($stmt3);
                        $sql_comment = $_SESSION["username"] . ": " . $sql3;
                        write_sql($sql_comment);
                    }
                    mysqli_stmt_close($stmt3);
                }
            }
        }

        $sfdat =  "1970-01-01";
        $efdat =  "1970-01-01";
        
        // sex 性別
        $sex = $csv[4];

        //cname 中文名
        $cname = trim($csv[3]);

        //fname first name , lname last name 英文名(新版合在一起，統一寫在fname)
        $fname = '';
        $lname = '';

        //dob 生日
        $dd = explode(" ", $csv[5]);
        $dob =  date('Y-m-d', strtotime($dd[0]));

        // mobile 手機
        $mobile = "0228377592";

        //uemail 顧客信箱
        $uemail = "heleln@td-lee.com";

        //ftest 快篩結果
        $ftest = "";

        //pcrtest PCR結果
        $pcrtest = "";

        //smsflag 簡訊flag
        $smsflag = "N";

        //email flag
        $emailflag = "N";

        //企業報名
        $type = "2";

        //sendname 統編
        $sendname = str_replace(" ", "", $csv[1]);

        //address1 廢欄位 
        $address1 = "";

        // telephone 市話
        $telephone = "0228377592";

        //address2 地址
        $address2 = "111士林區忠誠路二段56號";

        // //testtype 檢測類型
        $testtype = "1";

        //ctzn 台灣居民
        $ctzn = "1";

        //快篩編號
        $sampleid1 = "";
        //是否上傳cdc 此欄位目前沒用到
        $cdcflag = "N";

        //testreason 自費篩檢原因
        $testreason = "7";

        $mailrpt = "3";

        //residentpermit 是否有居留證
        $residentpermit = "N";

        //hicardno 健保卡號
        $hicardno = "";
        

        //hiflag 台灣健保
        $hiflag = 'Y';
        

        //nihrpt 陰性通報健保署
        $nihrpt = "Y";

        //mobilerpt 手機上傳健保署
        $mobilerpt = "Y";

        //健康存摺和雲端資料合併欄位，同意年限亦同
        //健康存摺利用
        $hbrpt = $cloudrpt = "Y";
        $hbrptyear = $cloudrptyear = "999";
        
            //vuser1 上傳結果的醫檢師 vuser2覆核結果的醫檢師
        $vuser1 = "";
        $vuser2 = "";

        //payflag 是否付款，廢欄位
        $payflag = "N";


        //fuser1 -> xmappoint 廈門預約 ,tuser1 -> xmapdat 廈門預約日期, tuser2 -> mtpid 台胞證號碼
        //cmobile 大陸手機, xmemail 廈門email
        $xmappoint = $xmapdat = $cmobile = $xmemail = "";
        
        //大陸48小時採檢次數 //olive
        // if (str_replace(" ", "", $csv[10]) == "第一次採檢") {
        // 		$xmappoint = "第一次採檢";
        // }elseif(str_replace(" ", "", $csv[10]) == "第二次採檢") {
        // 	echo($xmappoint."<br>");
        // 	$xmappoint = "第二次採檢";
        // }else{
        // 	$xmappoint = "非大陸專案客戶，不適用";
        // }

        //twrpturgency 在台檢測報告急迫性
        $twrpturgency = "normal"; 

        //xmrpturgency 鼻咽/咽喉
        $xmrpturgency = "1";
              
        $fpdfflag = $pcrpdfflag = $xlspcrtest2 = "";

        $sql2 = "INSERT INTO covid_trans (
        sfat, efat, userid, sex, cname, fname, lname, dob, mobile, uemail, apdat, ftest, pcrtest, smsflag, emailflag, type, telephone, address2,
        testtype, ctzn, passportid, cdcflag, sampleid2, residentpermit, mtpid, payflag, xmappoint, twrpturgency, sendname, hicardno, xmemail, 
        approval, address1, hiflag, fpdfflag, pcrpdfflag, nationality, xlspcrtest2,xmrpturgency
        ) VALUES (
        '$sfdat','$efdat','$userid','$sex','$cname', '$fname','$lname','$dob','$mobile','$uemail','$apdat','$ftest','$pcrtest','$smsflag','$emailflag','$type','$telephone','$address2',
        '$testtype','$ctzn','$passportid', '$cdcflag','$sampleid2','$residentpermit','$mtpid','$payflag','$xmappoint','$twrpturgency', '$sendname','$hicardno','$xmemail',
        '$approval', '$address1', '$hiflag','$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2',$xmrpturgency
        )";

        if (mysqli_query($conn, $sql2)) {
            //echo "New record created successfully";
            $count = $count + 1;
            $sql_comment = $_SESSION["username"].": ".$sql2;
            write_sql($sql_comment);
        } else {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            $sql_comment = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($sql_comment);
        }
        
    }
    mysqli_close($conn);
    echo "total uploaded rows:" . $count . "<br>";
} catch (Exception $th) {
    write_sql($sql_comment);
    echo("error=".$th->getMessage());
}
//echo "Uploaded.";
//echo "<br>"; 
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