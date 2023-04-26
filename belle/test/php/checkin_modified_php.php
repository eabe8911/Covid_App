<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
} else {
    header("location: login.php");
}

require_once "php/log.php";
require_once "param.php";
// 結果
$result_msg = '1';
// Connect to local db
$conn = mysqli_connect($hostname, $username, $password);
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$pcrid_uuid = "";
$userid_passid = "";
$uuid = "";
$search_result=$save_result="";
$userid = $passportid = $mobile = $sampleid1 = $sampleid2 = "";
$userid1 = $passportid1 = $mobile1 =$hicardno1= "";
$id_err = $passport_err = $mobile_err = $sampleid_err = $input_err = $login_err = "";
$sql2=$stmt="";
$nationality=$twrpturgency=$per_type=$ename=$tdat=$apdat=$cname=$dob=$lname="";
$fname=$mobile=$sex=$uemail=$testtype=$sendname=$testreason=$hicardno=$mtpid=$xmappoint="";
$page=$page_count=0;
// Processing save ftest pcrtest result
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {

    if (empty($sampleid_err)) {
        // save sampleid and report date
        $sql2 = "update covid_trans set sampleid1= ?,sampleid2=? ,
        passportid=?, userid=?, mobile=?, hicardno=?,
        apdat=?,testtype=?,cname=?,dob=?,sex=?,uemail=?,sendname=?,testreason=?,fname=?,lname=?,type=?,nationality=?,mtpid=?,twrpturgency=?,xmappoint=?
        where uuid=?";
        if ($stmt = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssi", 
            $p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p19,$p20,$p21,$p22,$p23
        );
        
        // Set parameters
        $p1 = trim($_POST["sampleid1"]);
        $p2 = trim($_POST["sampleid2"]);

        $p3 = trim($_POST["passportid1"]);
        // $passportid_string1 =trim($_POST["passportid1"]);
        // if ($passportid_string!= $passportid_string1) {
        //     $p3 = trim($passportid_string1);
        // } else {
        //     $p3 = trim($passportid_string);
        // }
        $p4 =trim($_POST["userid1"]);
        // if ($_POST["userid"] != $_POST["userid1"]) {
        //     $p4 = trim($_POST["userid1"]);
        // } else {
        //     $p4 = trim($_POST["userid"]);
        // }

        //$mobile_string =trim($_POST["mobile"]);
        //$mobile_string1 =trim($_POST["mobile1"]);
        $p5 = trim($_POST["mobile1"]);
        //if ($mobile_string  != $mobile_string1) {
        //    $p5 = trim($mobile_string1);
        //} else {
        //    $p5 = trim($mobile_string);
        //}

        $p6 =trim($_POST["hicardno1"]);
        // $hicardno_string1 =trim($_POST["hicardno1"]);
        // if ((strlen($hicardno_string) != strlen($hicardno_string1)) || ($hicardno_string != $hicardno_string1)){
        //     $p6 = trim($hicardno_string1);
        // }else{
        //     $p6 = trim($hicardno_string);
        // }

        $p7 = trim($_POST["apdat"]);
        $p8 = trim($_POST["testtype"]);
        $p9 = mb_convert_kana(trim($_POST["cname"]), 'a');  // 將全形空白轉成半形空白
        $p10 = trim($_POST["dob"]);
        $p11 = trim($_POST["sex"]);  
        $p12 = trim($_POST["email"]);
        if (trim($_POST["per_type"]==1)){
            date_default_timezone_set("Asia/Taipei");
            $sendname_after = date('ymd', strtotime(trim($_POST["apdat"])));
            $sendname_final=$sendname_after."01";
            $p13 =$sendname_final;
        }else{
            $p13 = trim($_POST["sendname"]);
        }
        $p14 = trim($_POST["testreason"]);

        // if (trim($_POST["ename"])==($_POST["fname"]." ".$_POST["lname"])){
        //     $p15 = trim($_POST["fname"]);
        //     $p16 = trim($_POST["lname"]);
        // }else{
            $p15 = trim($_POST["fname"]);
            // $p15 = trim($_POST["ename"]);
            $p16 = "";
        // }
        $p15 = mb_convert_kana($p15, 'a');  // 將全形空白轉成半形空白
        $p17 = trim($_POST["per_type"]);

        // if(!empty(trim($_POST["sampleid2"]))&&(trim($_POST["sampleid2"][0])=="Q")&&(trim($_POST["sampleid2"][1])=="H")){
        //     date_default_timezone_set("Asia/Taipei");
        //     $apdat=trim($_POST["apdat"]);
        //     // $mysqldate=date('Y-m-d H:i:s', strtotime($apdat."10:00:00"));
        //     $p18=$mysqldate;
        // }else 
        // if(!(empty(trim($_POST["tdat"])))){
        //     $p18=(trim($_POST["tdat"]));
        // }else{
        //     $p18=null;
        // }

        $p19 = trim($_POST["nationality"]);

        $p20 = trim($_POST["mtpid"]);

        $p21 = trim($_POST["twrpturgency"]);
        $p22 = trim($_POST["xmappoint"]);
        $p23 = trim($_POST["uuid"]);

        $sql_comment=$_SESSION["username"].": update covid_trans set sampleid1= {$p1},sampleid2={$p2},
        passportid={$p3}, userid={$p4}, mobile={$p5}, hicardno={$p6},
        apdat={$p7},testtype={$p8},cname={$p9},dob={$p10},sex={$p11},uemail={$p12},sendname={$p13},
        testreason={$p14},fname={$p15},lname={$p16},type={$p17},nationality={$p19},mtpid={$p20},twrpturgency={$p21},xmappoint={$p22}
        where uuid={$p23}";
        write_sql($sql_comment);

        }
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $save_result = '存檔成功';
            write_sql($save_result);
            $result_msg = '5';
        } else {
            $save_result =  "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($save_result);
        }    
        // Close statement
		mysqli_stmt_close($stmt);
          
    }

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkin"])) {
    // echo("checkin");die();

    if (empty($sampleid_err)) {
        // save sampleid and report date
        $sql2 = "update covid_trans set sampleid1= ?,sampleid2=? ,
            passportid=?, userid=?, mobile=?, hicardno=?,
            apdat=?,testtype=?,cname=?,dob=?,sex=?,uemail=?,sendname=?,testreason=?,
            fname=?,lname=?, tdat=?,type=?,nationality=?,mtpid=?,twrpturgency=? 
            where uuid=?";
        if ($stmt = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param(
                $stmt, "sssssssssssssssssssssi", $p1, $p2, $p3, $p4, $p5, $p6, $p7,
                $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15, $p16, $p17, $p18, $p19,
                $p20, $p21, $p22
            );
        
            // Set parameters
            $p1 = trim($_POST["sampleid1"]);
            $p2 = trim($_POST["sampleid2"]);

            // $passportid_string =strval($_POST["passportid"]);
            $p3 =strval($_POST["passportid1"]);
            // if ($passportid_string!= $passportid_string1) {
            //     $p3 = trim($passportid_string1);
            // } else {
            //     $p3 = trim($passportid_string);
            // }
            $p4 =strval($_POST["userid1"]);
            // if ($_POST["userid"] != $_POST["userid1"]) {
            //     $p4 = trim($_POST["userid1"]);
            // } else {
            //     $p4 = trim($_POST["userid"]);
            // }
            
            // $mobile_string =strval($_POST["mobile"]);
            $p5 =strval($_POST["mobile1"]);
            // if ($mobile_string  != $mobile_string1) {
            //     $p5 = trim($mobile_string1);
            // } else {
            //     $p5 = trim($mobile_string);
            // }

            // $hicardno_string =strval($_POST["hicardno"]);
            $p6 =strval($_POST["hicardno1"]);
            // if ((strlen($hicardno_string) != strlen($hicardno_string1)) || ($hicardno_string != $hicardno_string1)){
            //     $p6 = trim($hicardno_string1);
            // }else{
            //     $p6 = trim($hicardno_string);
            // }

            $p7 = trim($_POST["apdat"]);
            $p8 = trim($_POST["testtype"]);
            
            $p9 = trim($_POST["cname"]);
            // $p9 = mb_convert_kana($p9, 'a');  // 將全形轉成半形
            // $p9 = mb_convert_kana($p9, 's');  // 將全形空白轉成半形空白
            $p10 = trim($_POST["dob"]);
            $p11 = trim($_POST["sex"]);  
            $p12 = trim($_POST["email"]);
            if (trim($_POST["per_type"]==1)) {
                date_default_timezone_set("Asia/Taipei");
                $sendname_after = date('ymd', strtotime(trim($_POST["apdat"])));
                $sendname_final=$sendname_after."01";
                $p13 =$sendname_final;
            } else {
                $p13 = trim($_POST["sendname"]);
            }
            $p14 = trim($_POST["testreason"]);

            // if (trim($_POST["ename"])==($_POST["fname"]." ".$_POST["lname"])) {
            //     $p15 = trim($_POST["fname"]);
            //     $p16 = trim($_POST["lname"]);
            // } else {
                // $p15 = trim($_POST["ename"]);
                $p15 = trim($_POST["fname"]);
                $p16 = "";
            // }
            // $p15 = trim($_POST["fname"]);
            // $p15 = mb_convert_kana($p15, 'a');  // 將全形空白轉成半形空白
            // $p15 = mb_convert_kana($p15, 's');  // 將全形空白轉成半形空白

            // $p16 = "";
            date_default_timezone_set("Asia/Taipei");
            $p17=(string)date("Y-m-d H:i:s");
            $p18 = trim($_POST["per_type"]);
            $p19 = trim($_POST["nationality"]);
            $p20 = trim($_POST["mtpid"]);
            $p21 = trim($_POST["twrpturgency"]);
            $p22 = trim($_POST["uuid"]);

            $sql_comment = $_SESSION["username"] .
                ": update covid_trans set sampleid1={$p1}, sampleid2={$p2} ,
                passportid={$p3}, userid={$p4}, mobile={$p5}, hicardno={$p6},
                apdat={$p7},testtype={$p8},cname={$p9},dob={$p10},sex={$p11},
                uemail={$p12},sendname={$p13}, testreason={$p14},fname={$p15},
                lname={$p16}, tdat={$p17}, type={$p18}, nationality={$p19},
                mtpid={$p20}, twrpturgency={$p21}
                where uuid={$p22}
                ";
            write_sql($sql_comment);
        }
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $save_result = $p9.'<br>'.$p15.'<br>'.$p4.'<br>'.$p3.'<br>報到成功';
            write_sql($save_result);
            $result_msg = '4';
        } else {
            $save_result = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($save_result);
        }    
        // Close statement
		mysqli_stmt_close($stmt);
    }

} 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"] )){
    // echo($_POST["uuid"]);die();
	// Check if ID, passport, and mobile are empty
	if (empty(trim($_POST["pcrid_uuid"])) && empty(trim($_POST["userid_passid"]))){
		$input_err = "Please enter 'PCD ID', 'Passport NO.', 'User ID.'.";
	} else {
		if(empty(trim($_POST["pcrid_uuid"]))) {
            //$id_err = "E";
            $pcrid_uuid="NA";
		} else {
            if(strlen($_POST["pcrid_uuid"]) > 9){
                // PCR ID
                $sampleid2 = $_POST["pcrid_uuid"];
                $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
                sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
                hicardno,`type`,twrpturgency,nationality,mtpid
                FROM covid_trans WHERE sampleid2='" . $sampleid2 . "'";
            }else{
                // UUID
                $uuid = $_POST["pcrid_uuid"];
                $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
                sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
                hicardno,`type`,twrpturgency,nationality,mtpid
                FROM covid_trans WHERE uuid=" . $uuid;
            }
		}

		if(empty(trim($_POST["userid_passid"]))) {
            //$id_err = "E";
            $userid="NA";
		} else {
            if(strlen($_POST["userid_passid"]) == 10){
                // 身分證號
                $userid = trim($_POST["userid_passid"]);
                $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
                    sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
                    hicardno,`type`,twrpturgency,nationality,mtpid
                    FROM covid_trans WHERE userid='" . $userid . "' ORDER BY uuid desc";
            }else{
                // 護照號碼
                $passportid = trim($_POST["userid_passid"]);
                $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
                    sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
                    hicardno,`type`,twrpturgency,nationality,mtpid
                    FROM covid_trans WHERE passportid='" . $passportid . "' ORDER BY uuid desc";
            }
		}

		// if(empty(trim(isset($_POST["passportid"])))) {
        //     //$passport_err = "E";
        //     $passportid="NA";
		// } else {
		// 	$passportid = trim($_POST["passportid"]);
        //     $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
        //         sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
        //         hicardno,`type`,twrpturgency,nationality,mtpid
        //         FROM covid_trans WHERE passportid='".$passportid."' ORDER BY uuid desc";
		// }

		// if(empty(trim($_POST["mobile"]))) {
        //     //$mobile_err = "E";
        //     $mobile="NA";
		// } else {
		// 	$mobile = trim($_POST["mobile"]);
        //     $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,
        //         sex,dob,apdat,tdat,xmappoint,sampleid1,sampleid2,testtype,sendname,testreason,
        //         hicardno,`type`,twrpturgency,nationality,mtpid
        //         FROM covid_trans WHERE mobile='".$mobile."' ORDER BY uuid desc";
		// }
	}

    // Validate credentials
    if(empty($input_err)){        
        if($stmt = mysqli_prepare($conn, $sql)){
            $sql_comment=$_SESSION["username"].": ".$sql;
            write_sql($sql_comment);
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                $result = $stmt->get_result();
                while ($data = $result->fetch_assoc())
                {
                    $user_data[] = $data;
                }
                if(empty($user_data)){
                    $result_msg = '3';
                    $input_err = "No user data matched.";
                } else {    
                    $_SESSION["user_data"] = $user_data;
                    $_SESSION["data_point"] = 0;
                    // Proof that it's working
                    // echo "<pre>";
                    // var_dump($user_data);
                    // echo "</pre>";
                    // die();                
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    $search_result = '查詢成功';
                    write_sql($search_result);
                    echo $tdat;
    
                    if(count($user_data) == 0){                    
                        $result_msg = '3';
                        $input_err = "No user data matched.";
                    }else{
                        $page = count($user_data);
                        $page_count = 1;
                        $recordcount = $page_count . ' / ' . $page;      
                        $pcrid_uuid     = $user_data[0]["sampleid2"];
                        $userid_passid  = $user_data[0]["userid"];    
                        $uuid           = $user_data[0]["uuid"];
                        $userid         = $user_data[0]["userid"];
                        $passportid     = $user_data[0]["passportid"];
                        $cname          = $user_data[0]["cname"];
                        $lname          = $user_data[0]["lname"];
                        $fname          = $user_data[0]["fname"];
                        $mobile         = $user_data[0]["mobile"];
                        $uemail         = $user_data[0]["uemail"];
                        $sex            = $user_data[0]["sex"];
                        $dob            = $user_data[0]["dob"];
                        $apdat          = $user_data[0]["apdat"];
                        $tdat           = $user_data[0]["tdat"];
                        $xmappoint      = $user_data[0]["xmappoint"];
                        $sampleid1      = $user_data[0]["sampleid1"];
                        $sampleid2      = $user_data[0]["sampleid2"];
                        $testtype       = $user_data[0]["testtype"];
                        $sendname       = $user_data[0]["sendname"];
                        $testreason     = $user_data[0]["testreason"];
                        $hicardno       = $user_data[0]["hicardno"];
                        $per_type       = $user_data[0]["type"];
                        $twrpturgency   = $user_data[0]["twrpturgency"];
                        $nationality    = $user_data[0]["nationality"];
                        $mtpid          = $user_data[0]["mtpid"];
                        $userid1        = $userid;
                        $passportid1    = $passportid;
                        $mobile1        = $mobile;
                        $hicardno1      = $hicardno;
                        $result_msg = '2';
                        $_SESSION["uuid"]=$uuid;
                        $_SESSION["userid"]=$userid;
                        $_SESSION["passportid"]=strval($passportid);
                        $_SESSION["cname"]=$cname;
                        $_SESSION["lname"]=$lname ;
                        $_SESSION["fname"]=$fname;
                        $ename=$fname.' '.$lname;
                        $_SESSION["mobile"]=strval($mobile) ;
                        $_SESSION["email"]= $uemail;
                        $_SESSION["sex"]=$sex ;
                        $_SESSION["dob"]=$dob ;
                        $_SESSION["apdat"]=$apdat;
                        $_SESSION["tdat"]=$tdat;
                        $_SESSION["xmappoint"]=$xmappoint;
                        $_SESSION["sampleid1"]=$sampleid1;
                        $_SESSION["sampleid2"]=$sampleid2;
                        $_SESSION["testtype"]=$testtype;
                        $_SESSION["sendname"]=$sendname ;
                        $_SESSION["testreason"]=$testreason ;
                        $_SESSION["hicardno"]=strval($hicardno) ;
                        $userid1 = $userid;
                        $passportid1=strval($passportid);
                        $mobile1=strval($mobile);
                        $hicardno1=strval($hicardno);
                        $_SESSION["type"]=$per_type;
                        $_SESSION["twrpturgency"]=$twrpturgency;
                        $_SESSION["nationality"]=$nationality;
                        $_SESSION["mtpid"]=$mtpid;
    
                    }
                }
			}
			// Close statement
			mysqli_stmt_close($stmt);
		}else{
            $search_result= "Oops! Something went wrong.<br> Please try again later.";
            write_sql($search_result);
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["previous_record"])) {
    // echo("previous");die();
    if(isset($_SESSION["user_data"]) && !empty($_SESSION["user_data"])){
        $user_data = $_SESSION["user_data"];
        $data_point = $_SESSION["data_point"];
        $current_point = $data_point - 1;
        if($current_point< 0) {
            $current_point = $_SESSION["data_point"] = 0;
        }else{
            $_SESSION["data_point"] = $current_point;
        }
        $page = count($user_data);
        $page_count = $current_point + 1;
        $recordcount = $page_count . ' / ' . $page;          
        $pcrid_uuid     = $user_data[$current_point]["sampleid2"];
        $userid_passid  = $user_data[$current_point]["userid"];    
        $uuid           = $user_data[$current_point]["uuid"];
        $userid         = $user_data[$current_point]["userid"];
        $passportid     = $user_data[$current_point]["passportid"];
        $cname          = $user_data[$current_point]["cname"];
        $lname          = $user_data[$current_point]["lname"];
        $fname          = $user_data[$current_point]["fname"];
        $mobile         = $user_data[$current_point]["mobile"];
        $uemail         = $user_data[$current_point]["uemail"];
        $sex            = $user_data[$current_point]["sex"];
        $dob            = $user_data[$current_point]["dob"];
        $apdat          = $user_data[$current_point]["apdat"];
        $tdat           = $user_data[$current_point]["tdat"];
        $xmappoint      = $user_data[$current_point]["xmappoint"];
        $sampleid1      = $user_data[$current_point]["sampleid1"];
        $sampleid2      = $user_data[$current_point]["sampleid2"];
        $testtype       = $user_data[$current_point]["testtype"];
        $sendname       = $user_data[$current_point]["sendname"];
        $testreason     = $user_data[$current_point]["testreason"];
        $hicardno       = $user_data[$current_point]["hicardno"];
        $per_type       = $user_data[$current_point]["type"];
        $twrpturgency   = $user_data[$current_point]["twrpturgency"];
        $nationality    = $user_data[$current_point]["nationality"];
        $mtpid          = $user_data[$current_point]["mtpid"];
        $userid1        = $userid;
        $passportid1    = $passportid;
        $mobile1        = $mobile;
        $hicardno1      = $hicardno;
        $_SESSION["uuid"]=$uuid;
        $_SESSION["userid"]=$userid;
        $_SESSION["passportid"]=strval($passportid);
        $_SESSION["cname"]=$cname;
        $_SESSION["lname"]=$lname ;
        $_SESSION["fname"]=$fname;
        $ename=$fname.' '.$lname;
        $_SESSION["mobile"]=strval($mobile) ;
        $_SESSION["email"]= $uemail;
        $_SESSION["sex"]=$sex ;
        $_SESSION["dob"]=$dob ;
        $_SESSION["apdat"]=$apdat;
        $_SESSION["tdat"]=$tdat;
        $_SESSION["xmappoint"]=$xmappoint;
        $_SESSION["sampleid1"]=$sampleid1;
        $_SESSION["sampleid2"]=$sampleid2;
        $_SESSION["testtype"]=$testtype;
        $_SESSION["sendname"]=$sendname ;
        $_SESSION["testreason"]=$testreason ;
        $_SESSION["hicardno"]=strval($hicardno) ;
        $userid1 = $userid;
        $passportid1=strval($passportid);
        $mobile1=strval($mobile);
        $hicardno1=strval($hicardno);
        $_SESSION["type"]=$per_type;
        $_SESSION["twrpturgency"]=$twrpturgency;
        $_SESSION["nationality"]=$nationality;
        $_SESSION["mtpid"]=$mtpid;

    }
    $result_msg = '6';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["next_record"])) {
    // echo("next");die();
    if(isset($_SESSION["user_data"]) && !empty($_SESSION["user_data"])){
        $user_data = $_SESSION["user_data"];
        $data_point = $_SESSION["data_point"];
        
        $current_point = $data_point + 1;
        if($current_point == count($user_data)) {
            $current_point = $_SESSION["data_point"];
        }else{
            $_SESSION["data_point"] = $current_point;
        }
        $page = count($user_data);
        $page_count = $current_point + 1;
        $recordcount = $page_count . ' / ' . $page;          
        $pcrid_uuid     = $user_data[$current_point]["sampleid2"];
        $userid_passid  = $user_data[$current_point]["userid"];    
        $uuid           = $user_data[$current_point]["uuid"];
        $userid         = $user_data[$current_point]["userid"];
        $passportid     = $user_data[$current_point]["passportid"];
        $cname          = $user_data[$current_point]["cname"];
        $lname          = $user_data[$current_point]["lname"];
        $fname          = $user_data[$current_point]["fname"];
        $mobile         = $user_data[$current_point]["mobile"];
        $uemail         = $user_data[$current_point]["uemail"];
        $sex            = $user_data[$current_point]["sex"];
        $dob            = $user_data[$current_point]["dob"];
        $apdat          = $user_data[$current_point]["apdat"];
        $tdat           = $user_data[$current_point]["tdat"];
        $xmappoint      = $user_data[$current_point]["xmappoint"];
        $sampleid1      = $user_data[$current_point]["sampleid1"];
        $sampleid2      = $user_data[$current_point]["sampleid2"];
        $testtype       = $user_data[$current_point]["testtype"];
        $sendname       = $user_data[$current_point]["sendname"];
        $testreason     = $user_data[$current_point]["testreason"];
        $hicardno       = $user_data[$current_point]["hicardno"];
        $per_type       = $user_data[$current_point]["type"];
        $twrpturgency   = $user_data[$current_point]["twrpturgency"];
        $nationality    = $user_data[$current_point]["nationality"];
        $mtpid          = $user_data[$current_point]["mtpid"];
        $userid1        = $userid;
        $passportid1    = $passportid;
        $mobile1        = $mobile;
        $hicardno1      = $hicardno;
        $_SESSION["uuid"]=$uuid;
        $_SESSION["userid"]=$userid;
        $_SESSION["passportid"]=strval($passportid);
        $_SESSION["cname"]=$cname;
        $_SESSION["lname"]=$lname ;
        $_SESSION["fname"]=$fname;
        $ename=$fname.' '.$lname;
        $_SESSION["mobile"]=strval($mobile) ;
        $_SESSION["email"]= $uemail;
        $_SESSION["sex"]=$sex ;
        $_SESSION["dob"]=$dob ;
        $_SESSION["apdat"]=$apdat;
        $_SESSION["tdat"]=$tdat;
        $_SESSION["xmappoint"]=$xmappoint;
        $_SESSION["sampleid1"]=$sampleid1;
        $_SESSION["sampleid2"]=$sampleid2;
        $_SESSION["testtype"]=$testtype;
        $_SESSION["sendname"]=$sendname ;
        $_SESSION["testreason"]=$testreason ;
        $_SESSION["hicardno"]=strval($hicardno) ;
        $userid1 = $userid;
        $passportid1=strval($passportid);
        $mobile1=strval($mobile);
        $hicardno1=strval($hicardno);
        $_SESSION["type"]=$per_type;
        $_SESSION["twrpturgency"]=$twrpturgency;
        $_SESSION["nationality"]=$nationality;
        $_SESSION["mtpid"]=$mtpid;

    }
    $result_msg = '7';
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["clear"])){
    // echo("Clear");die();
    $_SESSION["user_data"] = "";
    $_SESSION["data_point"] = "";
}
// Close connection
mysqli_close($conn);

?>