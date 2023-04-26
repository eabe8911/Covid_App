<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// 2207161500 表單，修改讀取欄位 modified by olive


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
require("class/Positive.php");
require("./class/Sampleid2.php");

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
require 'param.php';
try {
    $dbname = "libodb";
    $conn = mysqli_connect("$hostname", "$username", "$password");
    mysqli_select_db($conn, $dbname);
    $payload = fgetcsv($file_open,10000, ",");
    // print_r($payload);echo('<br>');
	$file_open = fopen($file, "r");
	while (($csv = fgetcsv($file_open, 10000, ",")) !== false) {
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
			if (substr($csv[21],0,1) == "1") {
                $residentpermit = "Y";
                // echo("11");
            } else {
                 $residentpermit = "N";
                //  echo(substr($csv[18],0,2));
            }

        //userid 身分證
        if (!empty(trim($csv[20]))) {
            $userid = strtoupper(str_replace(" ", "", $csv[20]));
            // echo("(1)");
        } else {
            if($residentpermit == "Y") {
                $userid = str_replace("-", "", str_replace(" ", "", $csv[22]));
                // echo("(2)");
            }
            else{
                $userid = "";
                // echo("(3)");
            }	
        }
            
        //大陸48小時採檢次數 //olive
        if (substr($csv[12],0,1) == "1") {
        	$xmappoint = "第一次採檢";
        }else{
        	$xmappoint = "非大陸專案客戶，不適用";
        }
     
        //passportid 護照
			if (!empty(trim($csv[30]))) {
				if(str_replace(" ", "", $csv[30]) == 'NA'){
                    $passportid = "";
                }elseif(str_replace(" ", "", $csv[30]) == 'N/A'){
                    $passportid = "";
                }elseif(str_replace(" ", "", $csv[30]) == '無'){
                    $passportid = "";
                }else{
                    $passportid = strtoupper(str_replace(" ", "", $csv[30]));
                }
			} else {
				$passportid = "";
			}

            //mtpid 台胞證
			if (!empty(trim($csv[13]))) {
                $mtpid = strtoupper(str_replace(" ", "", $csv[13]));
            } else {
				$mtpid = "";
			}
			
			//日本報告國籍欄位
			if (!empty(trim($csv[15]))) {
				$nationality = strtoupper(str_replace(" ", "", $csv[15]));
			} else {
				$nationality = "";
			}
    
        //apdat 預約日期
        $apdat =  date('Y-m-d', strtotime($csv[17]));
    
        //如果身分證有資料
        if (trim($userid) != "") {
                $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 
            and userid=? and apdat=? and xmappoint=?";//olive

            $stmt = mysqli_prepare($conn, $sql);
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $p2, $p3, $p4);//olive


        } elseif (trim($passportid) != "") { //如果沒有身分證號，但是有護照號碼
            $sql = "SELECT uuid,passportid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
                and passportid=? and apdat=? and xmappoint=?";//olive
                $stmt = mysqli_prepare($conn, $sql);
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $p2, $p3, $p4);//olive
                
        } else {
            $sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1 and userid = ? and apdat=?";
            $stmt = mysqli_prepare($conn, $sql);
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $p2, $p3);//olive

        }
            

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
                    $p10 = $uuid;
                    if ($stmt3 = mysqli_prepare($conn, $sql3)) {
                        mysqli_stmt_bind_param($stmt3, "i", $p10);
                        mysqli_stmt_execute($stmt3);
                        $sql_comment = $_SESSION["username"] . ": " . $sql3;
                        write_sql($sql_comment);
                    }
                    mysqli_stmt_close($stmt3);
                }
            
                $i=0;
                if(!empty(trim($sampleid2))){
                    // $myline = $myline + 1;
                    
                    $i++;
                    break;
                }
                
            
            }
            if($i)continue;

        }

        //sfdat, efdat紀錄系統時間
        // $dd = explode(" ", $csv[1]);
        // $sfdat =  date('Y-m-d', strtotime($dd[0]));
        // $dd = explode(" ", $csv[2]);
        // $efdat =  date('Y-m-d', strtotime($dd[0]));
        $sfdat =  "1970-01-01";
        $efdat =  "1970-01-01";
        

        
        // sex 性別
        $sex = $csv[31];

        //cname 中文名
        //$cname = trim($csv[24]);
        if(str_replace(" ", "", $csv[28]) == 'NA'){
            $cname = "";
        }elseif(str_replace(" ", "", $csv[28]) == 'N/A'){
            $cname = "";
        }elseif(str_replace(" ", "", $csv[28]) == '無'){
            $cname = "";
        }else{
            $cname = str_replace("-", "", str_replace(" ", "", $csv[28]));
            $cname = str_replace("　", "", $csv[28]);
        }

        //fname first name , lname last name 英文名(新版合在一起，統一寫在fname)
        //$fname = trim($csv[26]);
        if(str_replace(" ", "", $csv[29]) == 'NA'){
            $fname = "";
        }elseif(str_replace(" ", "", $csv[29]) == 'N/A'){
            $fname = "";
        }elseif(str_replace(" ", "", $csv[29]) == '無'){
            $fname = "";
        }else{
            // $fname = str_replace("-", "", str_replace(" ", "", $csv[26]));
            // 英文名字不能把空白和-拿掉
            $fname = trim($csv[29]);
            // 把全形空白換成半形空白
            $fname = str_replace("　", " ", $fname);
        }
        //$lname = trim($csv[9]);
        $lname = "";

        //dob 生日
        $dd = explode(" ", $csv[32]);
        //echo $dd[0]; echo '<br>';
        $dob =  date('Y-m-d', strtotime($dd[0]));
        //$dob = strtotime($csv[10]);

        // mobile 手機
        $mobile = str_replace(" ", "", $csv[33]);

        //uemail 顧客信箱
        $uemail = str_replace(" ", "", $csv[35]);

        //ftest 快篩結果
        $ftest = "";

        //pcrtest PCR結果
        $pcrtest = "";

        //smsflag 簡訊flag
        $smsflag = "N";

        //email flag
        $emailflag = "N";

        //$type ="1"; type 報名方式 1:個人 or 2:團體
         if (substr($csv[6],0,1) == '1') {
             $type = 1;
        //     $dd = explode("(", $csv[9]);
        //     //echo $dd[0]; echo '<br>';
        //     $apdat =  date('Y-m-d', strtotime($dd[0]));
        //     $year = explode("-", $apdat)[0];
        //     $month = explode("-", $apdat)[1];
        //     $day = explode("-", $apdat)[2];
            
        //     //強制上午場 no = 01
        //     $no = "01";
        //     $sendname = $year[2] . $year[3] . $month . $day . $no;
         } else {
             $type = 2;
             $sendname = str_replace(" ", "", $csv[9]);
        }

        if (!empty(trim($csv[9]))) {
            $sendname = str_replace(" ", "", $csv[9]);
            echo($csv[9].$sendname.'<br>');
        } else{
            $dd = explode("(", $csv[17]);
            //echo $dd[0]; echo '<br>';
            $apdat =  date('Y-m-d', strtotime($dd[0]));
            $year = explode("-", $apdat)[0];
            $month = explode("-", $apdat)[1];
            $day = explode("-", $apdat)[2];
            
            //強制上午場 no = 01
            // $no = "01";
            // $sendname = $year[2] . $year[3] . $month . $day . $no;
            $sendname = "";        }

        //address1 廢欄位 
        $address1 = "";

        // telephone 市話
        $telephone = str_replace(" ", "", $csv[34]);

        //address2 地址
        $address2 = trim($csv[37]);

        //testtype 檢測類型
        $testtype = 2;

        //ctzn 台灣居民
        if (substr($csv[19],0,1) == '1') {
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
        // if (substr($csv[10],0,1) == '1') {
        //     $testreason = '1';
        // } elseif (substr($csv[10],0,1) == '2') {
        //     $testreason = '2';
        // } elseif (substr($csv[10],0,1) == '3') {
        //     $testreason = '3';
        // } elseif (substr($csv[10],0,1) == '4') {
        //     $testreason = '4';
        // } elseif (substr($csv[10],0,1) == '5') {
        //     $testreason = '5';
        // } elseif (substr($csv[10],0,1) == '6') {
        //     $testreason = '6';
        // } else {
            $testreason = '7';
        // }

        //mailrpt 郵寄報告 1:郵寄 2:現場領 3:email
        if (trim($csv[36]) == '需要，請幫我郵寄') {
            $mailrpt = "1";
        } elseif (trim($csv[36]) == '我會親自到場領取紙本報告') {
            $mailrpt = "2";
        } else {
            $mailrpt = "3";
        }

        //residentpermit 是否有居留證
        if (substr($csv[21],0,1) == '1') {
                $residentpermit = "Y";
                // echo("13");
            } else {
                $residentpermit = "N";
                // echo("14");
            }

        //hicardno 健保卡號
        $hicardno = "";
        if(str_replace(" ", "", $csv[23]) == 'NA'){
            $hicardno = "";
        }elseif(str_replace(" ", "", $csv[23]) == 'N/A'){
            $hicardno = "";
        }elseif(str_replace(" ", "", $csv[23]) == '無'){
            $hicardno = "";
        }else{
            $hicardno = str_replace("-", "", str_replace(" ", "", $csv[23]));
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
        if (substr($csv[24],0,1) == '1') {
            $nihrpt = "Y";
        } else {
            $nihrpt = "N";
        }

        //mobilerpt 手機上傳健保署
        if (substr($csv[25],0,1) == '1') {
            $mobilerpt = "Y";
        } else {
            $mobilerpt = "N";
        }

        //健康存摺和雲端資料合併欄位，同意年限亦同
        //健康存摺利用
        if (substr($csv[26],0,1) == '1') {
            $hbrpt = $cloudrpt = "Y";
            $hbrptyear = $cloudrptyear = $csv[27];
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
        if (substr($csv[39],0,1) == "1") {
            //$xmappoint = "Y";
            //預約日
            $dd = explode(" ", $csv[40]);
            //$dd = explode(" ", $csv[32]); //olive
            $xmapdat = date('Y-m-d', strtotime($dd[0]));
            $mtpid = str_replace(" ", "", $csv[13]);
            $cmobile = str_replace(" ", "", $csv[43]);
            if (str_replace(" ", "", $csv[44]) == '') {
                $xmemail = $uemail;
            } else {
                $xmemail = str_replace(" ", "", $csv[44]);
            }
        } else {
            //$xmappoint = "N";
            $xmapdat = $cmobile = $xmemail = '';
        }

        //twrpturgency 在台檢測報告急迫性
        if(substr($csv[16],0,1) == "3"){
            $twrpturgency = "hiurgent";
        }elseif(substr($csv[16],0,1) == "2"){
            $twrpturgency = "urgent";
        }elseif(substr($csv[16],0,1) == "1"){
            $twrpturgency = "normal";
        }

        //xmrpturgency 鼻咽/咽喉
        $xmrpturgency = "1";
        
        

        // $xmappoint =
        $fpdfflag = $pcrpdfflag = $xlspcrtest2 = '';

        $payload = [
            'userid'        => $userid,
            'passportid'    => $passportid,
            'mtpid'         => $mtpid
        ];

        $positive = new Positive();
        if($positive->QueryAddUser($payload, $apdat)){
            echo("此位為五日內陽性確診者!!!".var_dump($payload));
            $UserInfo = [
                "cname"  => $cname,
                "fname"  => $fname,
                "uemail" => $uemail
            ];
            $positive->SendMail($UserInfo);
            continue;
        }

        //匯入時自動產生採檢編號
        if ($apdat <> '') { //如果有預約日期     
            try {

                $sampleid2 = new newsampleid2();
                $newsampleid2 = $sampleid2->getnewsampleid2LastID($apdat);
    
                echo $newsampleid2;
                // die();
                $trandate = date("Y-m-d H:i:s");
    
                // TODO: receipt id add 1
                $payload = [
                    'uuid' => $uuid,
                    'sampleid2' => $newsampleid2,
                    'apdat' => $apdat,
                    'trandate' => $trandate
                ];
                var_dump($newsampleid2);
                die();
                if ($sampleid2->addnewsampleid2($payload) == false) {
                    throw new Exception($sampleid2->get_errorMessage(), 1);
                }
    
            } catch (Exception $th) {
                echo $th->getMessage();
            }
        }


        
        $sql2 = "INSERT INTO covid_trans (
        sfat, efat, userid, sex, cname, fname, lname, dob, mobile, uemail, apdat, ftest, pcrtest, smsflag, emailflag, type, telephone, address2,
        testtype, ctzn, passportid, cdcflag, residentpermit, mtpid, payflag, xmappoint, twrpturgency, sendname, hicardno, testreason, mobilerpt,xmrpturgency,
        mailrpt, hbrpt, hbrptyear, cloudrpt, cloudrptyear, nihrpt, xmemail, approval,  address1, hiflag, fpdfflag, pcrpdfflag, nationality, xlspcrtest2, sampleid2
        ) VALUES (
        '$sfdat','$efdat','$userid','$sex','$cname', '$fname','$lname','$dob','$mobile','$uemail','$apdat','$ftest','$pcrtest','$smsflag','$emailflag','$type','$telephone','$address2',
        '$testtype','$ctzn','$passportid', '$cdcflag','$residentpermit','$mtpid','$payflag','$xmappoint','$twrpturgency','$sendname', '$hicardno','$testreason','$mobilerpt',$xmrpturgency,
        '$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt','$xmemail','$approval', '$address1', '$hiflag','$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2', '$newsampleid2'
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