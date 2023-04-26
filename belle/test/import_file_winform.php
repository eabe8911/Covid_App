<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// 2207161500 表單，修改讀取欄位 modified by olive
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

set_time_limit(300);

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
    $dbname = "testdb";
    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, $dbname);
    $payload = fgetcsv($file_open, 6000, ",");
    // print_r($payload);echo('<br>');
	$file_open = fopen($file, "r");
	while (($csv = fgetcsv($file_open, 6000, ",")) !== false) {
        if ($myline < 1) {
            $myline = $myline + 1;
            continue;
        }
        if(empty($csv[0])){
            continue;
        }
        //approval 同意攜帶證件
        $approval = 'Y';
    
        //residentpermit 是否有居留證
			if (str_replace(" ", "", $csv[18]) == '是/YES') {
                $residentpermit = "Y";
            } elseif (str_replace(" ", "", $csv[18]) == '否/NO') {
                $residentpermit = "N";
            } else {
                 $residentpermit = "";
            }

        //userid 身分證
        if (!empty(trim($csv[16]))) {
            $userid = strtoupper(str_replace(" ", "", $csv[16]));
        } else {
            if($residentpermit == "Y") {
                $userid = str_replace("-", "", str_replace(" ", "", $csv[19]));
            }
            else{
            $userid = "";
            }	
        }
            
        //大陸48小時採檢次數 //olive
        if (str_replace(" ", "", $csv[10]) == "第一次採檢") {
        	$xmappoint = "第一次採檢";
        }elseif(str_replace(" ", "", $csv[10]) == "第二次採檢") {
        	$xmappoint = "第二次採檢";
        }else{
        	$xmappoint = "非大陸專案客戶，不適用";
        }
     
        //passportid 護照
			if (!empty(trim($csv[25]))) {
				$passportid = strtoupper(str_replace(" ", "", $csv[25]));
			} else {
				$passportid = "";
			}

            //mtpid 台胞證
			if (!empty(trim($csv[11]))) {
                        	$mtpid = strtoupper(str_replace(" ", "", $csv[11]));
            } else {
				$mtpid = "";
			}
			
			//日本報告國籍欄位
			if (!empty(trim($csv[13]))) {
				$nationality = strtoupper(str_replace(" ", "", $csv[13]));
			} else {
				$nationality = "";
			}
    
        //apdat 預約日期
        $apdat =  date('Y-m-d', strtotime($csv[9]));
    
        //如果身分證有資料
        if (trim($userid) != "") {
            $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 and userid = ? and apdat=?
                                                                            and    xmappoint=?";//olive
        } elseif (trim($passportid) != "") { //如果沒有身分證號，但是有護照號碼
            $sql = "SELECT uuid,passportid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
                                                                            and    passportid = ? 
                                                                            and    apdat=?
                                                                            and    xmappoint=?";//olive
        } else {
            $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 and userid = ? and apdat=? and xmappoint=?";
        }
            
        $stmt = mysqli_prepare($conn, $sql);
            // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $p2, $p3, $p4);//olive

        // Set parameters
        //$p1 = $uuid;
        //$p1 = $userid;
        // 061121 修正可能passport 沒填，檢查時有回傳的bug
        if (trim($userid) != "") {
            $p2 = $userid;
        } elseif (trim($passportid) != "") {
            $p2 = $passportid;
        }
        $p3 = $apdat;
        $p4 = $xmappoint; //olive

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
            // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify passport

        if(mysqli_stmt_num_rows($stmt) <> 0){
            echo("find double record!<br>");
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

        //sfdat, efdat紀錄系統時間
        $dd = explode(" ", $csv[1]);
        //echo $dd[0]; echo '<br>';
        $sfdat =  date('Y-m-d', strtotime($dd[0]));
        //echo $sfdat; echo '<br>';
        $dd = explode(" ", $csv[2]);
        //echo $dd[0]; echo '<br>';
        $efdat =  date('Y-m-d', strtotime($dd[0]));
        // $efdat = strtotime($csv[2]);

        
        // sex 性別
        $sex = $csv[27];

        //cname 中文名
        $cname = trim($csv[24]);

        //fname first name , lname last name 英文名(新版合在一起，統一寫在fname)
        $fname = trim($csv[26]);
        //$lname = trim($csv[9]);
        $lname = "";

        //dob 生日
        $dd = explode(" ", $csv[28]);
        //echo $dd[0]; echo '<br>';
        $dob =  date('Y-m-d', strtotime($dd[0]));
        //$dob = strtotime($csv[10]);

        // mobile 手機
        $mobile = str_replace(" ", "", $csv[29]);

        //uemail 顧客信箱
        $uemail = str_replace(" ", "", $csv[31]);

        //ftest 快篩結果
        $ftest = "";

        //pcrtest PCR結果
        $pcrtest = "";

        //smsflag 簡訊flag
        $smsflag = "N";

        //email flag
        $emailflag = "N";

        //$type ="1"; type 報名方式 1:個人 or 2:團體
        if (trim($csv[5]) == '個人自費報名 / Individual Registration') {
            $type = 1;
            $dd = explode("(", $csv[9]);
            //echo $dd[0]; echo '<br>';
            $apdat =  date('Y-m-d', strtotime($dd[0]));
            $year = explode("-", $apdat)[0];
            $month = explode("-", $apdat)[1];
            $day = explode("-", $apdat)[2];
            
            //強制上午場 no = 01
            $no = "01";
            $sendname = $year[2] . $year[3] . $month . $day . $no;
        } else {
            $type = 2;
            $sendname = str_replace(" ", "", $csv[6]);
        }

        //address1 廢欄位 
        $address1 = "";

        // telephone 市話
        $telephone = str_replace(" ", "", $csv[30]);

        //address2 地址
        $address2 = trim($csv[33]);

        //testtype 檢測類型
        if (str_replace(" ", "", $csv[8]) == '抗原快篩/Ag_TC;') {
            $testtype = 1;
        } elseif (str_replace(" ", "", $csv[8]) == '核酸檢測/PCR;') {
            $testtype = 2;
        } else {
            $testtype = 3;
        }

        //ctzn 台灣居民
        if (str_replace(" ", "", $csv[12]) == '是/Yes') {
            $ctzn = 1;
        } else {
            $ctzn = 2;
        }
        //$ctzn = $csv[24];
        //$passportid = $csv[25];

        //快篩編號
        $sampleid1 = "";
        //是否上傳cdc 此欄位目前沒用到
        $cdcflag = "N";

        //testreason 自費篩檢原因
        if (trim($csv[7]) == '因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附(1)申請表；(2)申請原因相關文件， 如電子機票、購票證明或訂票紀錄等') {
            $testreason = '1';
        } elseif (trim($csv[7]) == '因工作因素須檢附檢驗證明之民眾。需檢附(1)申請表；(2)工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等') {
            $testreason = '2';
        } elseif (trim($csv[7]) == '短期商務人士。需檢附(1)申請表；(2)申請原因相關文件（如： 在臺行程表或防疫計畫書等') {
            $testreason = '3';
        } elseif (trim($csv[7]) == '出國求學須檢附檢驗證明之民眾。需檢附(1)申請表；(2)就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等') {
            $testreason = '4';
        } elseif (trim($csv[7]) == '外國或中國大陸、香港、澳門人士出境。需檢附(1)申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等') {
            $testreason = '5';
        } elseif (trim($csv[7]) == '相關出境適用對象之眷屬。需檢附(1)申請表；(2)身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等') {
            $testreason = '6';
        } else {
            $testreason = '7';
        }

        //mailrpt 郵寄報告 1:郵寄 2:現場領 3:email
        if (trim($csv[32]) == '需要，請幫我郵寄') {
            $mailrpt = "1";
        } elseif (trim($csv[32]) == '我會親自到場領取紙本報告') {
            $mailrpt = "2";
        } else {
            $mailrpt = "3";
        }

        //residentpermit 是否有居留證
        if (str_replace(" ", "", $csv[18]) == '是/YES') {
                $residentpermit = "Y";
            } elseif (str_replace(" ", "", $csv[18]) == '否/NO') {
                $residentpermit = "N";
            } else {
                $residentpermit = "";
            }

        //hicardno 健保卡號
        $hicardno = "";
        if ($ctzn == 1) {
            $hicardno = str_replace("-", "", str_replace(" ", "", $csv[17]));
        }
        // if ($residentpermit == "Y") {
        // 	$hicardno = str_replace("-", "", str_replace(" ", "", $csv[19]));
        // }

        //hiflag 台灣健保
        if (!empty($hicardno)) {
            $hiflag = 'Y';
        } else {
            $hiflag = 'N';
        }

        //nihrpt 陰性通報健保署
        if ($csv[20] == '同意 / YES') {
            $nihrpt = "Y";
        } else {
            $nihrpt = "N";
        }

        //mobilerpt 手機上傳健保署
        if ($csv[21] == '同意 / YES') {
            $mobilerpt = "Y";
        } else {
            $mobilerpt = "N";
        }

        //健康存摺和雲端資料合併欄位，同意年限亦同
        //健康存摺利用
        if ($csv[22] == '同意 / YES') {
            $hbrpt = $cloudrpt = "Y";
            $hbrptyear = $cloudrptyear = $csv[23];
        } else {
            $hbrpt = $cloudrpt = "N";
            $hbrptyear = $cloudrptyear = "0";
        }

        
        //vuser1 上傳結果的醫檢師 vuser2覆核結果的醫檢師
        $vuser1 = "";
        $vuser2 = "";

        //payflag 是否付款，廢欄位
        $payflag = "N";


        //fuser1 -> xmappoint 廈門預約 ,tuser1 -> xmapdat 廈門預約日期, tuser2 -> mtpid 台胞證號碼
        //cmobile 大陸手機, xmemail 廈門email
        if (str_replace(" ", "", $csv[34]) == "是/Yes") {
            //$xmappoint = "Y";
            //預約日
            $dd = explode(" ", $csv[35]);
            //$dd = explode(" ", $csv[32]); //olive
            $xmapdat = date('Y-m-d', strtotime($dd[0]));
            $mtpid = str_replace(" ", "", $csv[37]);
            $cmobile = str_replace(" ", "", $csv[38]);
            if (str_replace(" ", "", $csv[39]) == '') {
                $xmemail = $uemail;
            } else {
                $xmemail = str_replace(" ", "", $csv[39]);
            }
        } else {
            //$xmappoint = "N";
            $xmapdat = $cmobile = $xmemail = '';
        }
        
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
        $_rpturgency = trim($csv[14]);
        switch ($_rpturgency) {
            case '急件特別診':
                $twrpturgency = "hiurgent";
                break;
            case '急件':
                $twrpturgency = "urgent";
                break;
            case '一般件':
                $twrpturgency = "normal";
                break;
            default:
                $twrpturgency = "normal";
                break;
        }

       


        $fpdfflag = $pcrpdfflag = $xlspcrtest2 = '';

        $sql2 = "INSERT INTO covid_trans (
        sfat, efat, userid, sex, cname, fname, lname, dob, mobile, uemail, apdat, ftest, pcrtest, smsflag, emailflag, type, telephone, address2,
        testtype, ctzn, passportid, cdcflag, residentpermit, mtpid, payflag, xmappoint, twrpturgency, hicardno, testreason, mobilerpt,
        mailrpt, hbrpt, hbrptyear, cloudrpt, cloudrptyear, nihrpt, xmemail, approval,  address1, hiflag, fpdfflag, pcrpdfflag, nationality, xlspcrtest2
        ) VALUES (
        '$sfdat','$efdat','$userid','$sex','$cname', '$fname','$lname','$dob','$mobile','$uemail','$apdat','$ftest','$pcrtest','$smsflag','$emailflag','$type','$telephone','$address2',
        '$testtype','$ctzn','$passportid', '$cdcflag','$residentpermit','$mtpid','$payflag','$xmappoint','$twrpturgency', '$hicardno','$testreason','$mobilerpt',
        '$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt','$xmemail','$approval', '$address1', '$hiflag','$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2'
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