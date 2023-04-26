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

require_once ("php/log.php");

// Connect to local db
$conn = mysqli_connect("localhost","libo_user","xxx");
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$search_result=$save_result="";
$userid = $passportid = $mobile = $sampleid1 = $sampleid2 = "";
$userid1 = $passportid1 = $mobile1 =$hicardno1= "";
$id_err = $passport_err = $mobile_err = $sampleid_err = $input_err = $login_err = "";
$sql2=$stmt="";
$nationality=$twrpturgency=$per_type=$ename=$tdat=$apdat=$cname=$dob=$lname=$fname=$mobile=$sex=$uemail=$testtype=$sendname=$testreason=$hicardno=$mtpid="";

// Processing save ftest pcrtest result
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"]))
{

    if(empty($sampleid_err)){
        // save sampleid and report date
        $sql2 = "update covid_trans set sampleid1= ?,sampleid2=? ,
        passportid=?, userid=?, mobile=?, hicardno=?,
        apdat=?,testtype=?,cname=?,dob=?,sex=?,uemail=?,sendname=?,testreason=?,fname=?,lname=?,type=?,nationality=?,mtpid=?
        where uuid=?";
        if ($stmt = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt, "sssssssssssssssssssi", 
            $p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p19,$p20,$p21
        );
        
        // Set parameters
        $p1 = trim($_POST["sampleid1"]);
        $p2 = trim($_POST["sampleid2"]);

        $passportid_string =strval($_POST["passportid"]);
        $passportid_string1 =strval($_POST["passportid1"]);
        if ($passportid_string!= $passportid_string1) {
            $p3 = trim($passportid_string1);
        } else {
            $p3 = trim($passportid_string);
        }

        if ($_POST["userid"] != $_POST["userid1"]) {
            $p4 = trim($_POST["userid1"]);
        } else {
            $p4 = trim($_POST["userid"]);
        }

        $mobile_string =strval($_POST["mobile"]);
        $mobile_string1 =strval($_POST["mobile1"]);
        
        if ($mobile_string  != $mobile_string1) {
            $p5 = trim($mobile_string1);
        } else {
            $p5 = trim($mobile_string);
        }

        $hicardno_string =strval($_POST["hicardno"]);
        $hicardno_string1 =strval($_POST["hicardno1"]);
        if ((strlen($hicardno_string) != strlen($hicardno_string1)) || ($hicardno_string != $hicardno_string1)){
            $p6 = trim($hicardno_string1);
        }else{
            $p6 = trim($hicardno_string);
        }

        $p7 = trim($_POST["apdat"]);
        $p8 = trim($_POST["testtype"]);
        $p9 = trim($_POST["cname"]);
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

        if (trim($_POST["ename"])==($_POST["fname"]." ".$_POST["lname"])){
            $p15 = trim($_POST["fname"]);
            $p16 = trim($_POST["lname"]);
        }else{
            $p15 = trim($_POST["ename"]);
            $p16 = "";
        }
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

        $p21 = trim($_POST["uuid"]);

        $sql_comment=$_SESSION["username"].": update covid_trans set sampleid1= {$p1},sampleid2={$p2},
        passportid={$p3}, userid={$p4}, mobile={$p5}, hicardno={$p6},
        apdat={$p7},testtype={$p8},cname={$p9},dob={$p10},sex={$p11},uemail={$p12},sendname={$p13},
        testreason={$p14},fname={$p15},lname={$p16},type={$p17},nationality={$p19},mtpid={$p20}
        where uuid={$p21}";
        write_sql($sql_comment);

        }
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $save_result = '存檔成功';
            write_sql($save_result);
        } else {
            $save_result =  "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($save_result);
        }    
        // Close statement
		mysqli_stmt_close($stmt);
          
    }

} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkin"])){

    if(empty($sampleid_err)){
        // save sampleid and report date
        $sql2 = "update covid_trans set sampleid1= ?,sampleid2=? ,
        passportid=?, userid=?, mobile=?, hicardno=?,
        apdat=?,testtype=?,cname=?,dob=?,sex=?,uemail=?,sendname=?,testreason=?,fname=?,lname=?,
        tdat=?,type=?,nationality=?,mtpid=? where uuid=?";
        if ($stmt = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssi", 
            $p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p18,$p19,$p20,$p21
        );
        
        // Set parameters
        $p1 = trim($_POST["sampleid1"]);
        $p2 = trim($_POST["sampleid2"]);

        $passportid_string =strval($_POST["passportid"]);
        $passportid_string1 =strval($_POST["passportid1"]);
        if ($passportid_string!= $passportid_string1) {
            $p3 = trim($passportid_string1);
        } else {
            $p3 = trim($passportid_string);
        }

        if ($_POST["userid"] != $_POST["userid1"]) {
            $p4 = trim($_POST["userid1"]);
        } else {
            $p4 = trim($_POST["userid"]);
        }
        
        $mobile_string =strval($_POST["mobile"]);
        $mobile_string1 =strval($_POST["mobile1"]);
        if ($mobile_string  != $mobile_string1) {
            $p5 = trim($mobile_string1);
        } else {
            $p5 = trim($mobile_string);
        }

        $hicardno_string =strval($_POST["hicardno"]);
        $hicardno_string1 =strval($_POST["hicardno1"]);
        if ((strlen($hicardno_string) != strlen($hicardno_string1)) || ($hicardno_string != $hicardno_string1)){
            $p6 = trim($hicardno_string1);
        }else{
            $p6 = trim($hicardno_string);
        }

        $p7 = trim($_POST["apdat"]);
        $p8 = trim($_POST["testtype"]);
        $p9 = trim($_POST["cname"]);
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

        if (trim($_POST["ename"])==($_POST["fname"]." ".$_POST["lname"])){
            $p15 = trim($_POST["fname"]);
            $p16 = trim($_POST["lname"]);
        }else{
            $p15 = trim($_POST["ename"]);
            $p16 = "";
        }

        date_default_timezone_set("Asia/Taipei");
        $p17=(string)date("Y-m-d H:i:s");
        $p18 = trim($_POST["per_type"]);
        $p19 = trim($_POST["nationality"]);
        $p20 = trim($_POST["mtpid"]);
        $p21 = trim($_POST["uuid"]);

        $sql_comment=$_SESSION["username"].": update covid_trans set sampleid1={$p1},sampleid2={$p2} ,
        passportid={$p3}, userid={$p4}, mobile={$p5}, hicardno={$p6},
        apdat={$p7},testtype={$p8},cname={$p9},dob={$p10},sex={$p11},uemail={$p12},sendname={$p13},
        testreason={$p14},fname={$p15},lname={$p16},
        tdat={$p17},type={$p18},nationality={$p19},mtpid={$p20} where uuid={$p21}";
        write_sql($sql_comment);
        }
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $save_result = '報到成功';
            write_sql($save_result);
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
	// Check if ID, passport, and mobile are empty
	if (empty(trim($_POST["userid"])) && empty(trim($_POST["passportid"])) && empty(trim($_POST["mobile"])) && empty(trim($_POST["hicardno"]))){
		$input_err = "Please enter 'ID', 'Passport NO.', 'Mobile NO.' or 'NHI Card NO.'.";
	}
	else{
		if(empty(trim($_POST["userid"]))){
            //$id_err = "E";
            $userid="NA";
		}else{
			$userid = trim($_POST["userid"]);
		}
		if(empty(trim($_POST["passportid"]))){
            //$passport_err = "E";
            $passportid="NA";
		}else{
			$passportid = trim($_POST["passportid"]);
		}
		if(empty(trim($_POST["mobile"]))){
            //$mobile_err = "E";
            $mobile="NA";
		}else{
			$mobile = trim($_POST["mobile"]);
		}
		if(empty(trim($_POST["hicardno"]))){
            //$mobile_err = "E";
            $hicardno="NA";
		}else{
			$hicardno = trim($_POST["hicardno"]);
		}
	}

    // Validate credentials
    if(empty($input_err)){

        // Prepare a select statement, -20 is for testing data
        // 060921 Williek 
        // limit 1 rows only because someone might register many times
        //061121 testtype =2 means doing both test, so if testtype=1, sampleid2 needs to be empty
        $sql = "SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,sex,dob,apdat,tdat,sampleid1,sampleid2,testtype,sendname,testreason,hicardno,`type`,twrpturgency,nationality,mtpid
                FROM   covid_trans WHERE 1=1 
                and apdat >= (select curdate()-20 from dual) 
                and (userid = ? or passportid = ? or mobile = ? or hicardno = ?) 
                ORDER BY uuid desc
                limit 1";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_userid, $param_passport, $param_mobile, $param_hicardno);
            
            $param_userid = $userid;
            $param_passport = $passportid;
            $param_mobile = $mobile;
            $param_hicardno = $hicardno;

            $sql_comment=$_SESSION["username"].": SELECT uuid,userid, passportid,cname,lname,fname,mobile,uemail,sex,dob,apdat,tdat,sampleid1,sampleid2,testtype,sendname,testreason,hicardno,`type`,twrpturgency,nationality,mtpid
            FROM  covid_trans WHERE 1=1 
            and apdat >= (select curdate()-20 from dual) 
            and (userid = {$param_userid} or passportid = {$param_passport} or mobile = {$param_mobile} or hicardno = {$param_hicardno}) 
            ORDER BY uuid desc
            limit 1";
            write_sql($sql_comment);
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                $search_result = '查詢成功';
                write_sql($search_result);
                echo $tdat;

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt,$uuid, $userid, $passportid,$cname,$lname,$fname,$mobile,$uemail,$sex,$dob,$apdat,$tdat,$sampleid1,$sampleid2,$testtype,$sendname,$testreason,$hicardno,$per_type,$twrpturgency,$nationality,$mtpid);
                    if(mysqli_stmt_fetch($stmt)){
							
                            $_SESSION["userid"]=$userid;
                            $_SESSION["passportid"]=strval($passportid);
                            $_SESSION["cname"]=$cname;
                            $_SESSION["lname"]=$lname ;
                            $_SESSION["fname"]=$fname;
                            $ename=$fname.' '.$lname;
                            $_SESSION["mobile"]=strval($mobile);
                            $_SESSION["email"]= $uemail;
                            $_SESSION["sex"]=$sex ;
                            $_SESSION["dob"]=$dob ;
                            $_SESSION["apdat"]=$apdat;
                            $_SESSION["tdat"]=$tdat;
                            $_SESSION["sampleid1"]=$sampleid1;
                            $_SESSION["sampleid2"]=$sampleid2;
                            $_SESSION["testtype"]=$testtype;
                            $_SESSION["sendname"]=$sendname ;
							$_SESSION["testreason"]=$testreason ;
                            $_SESSION["hicardno"]=strval($hicardno);
                            $userid1 = $userid;
                            $passportid1=strval($passportid);
                            $mobile1=strval($mobile);
                            $hicardno1=strval($hicardno);
                            $_SESSION["type"]=$per_type;
                            $_SESSION["twrpturgency"]=$twrpturgency;
                            $_SESSION["nationality"]=$nationality;
                            $_SESSION["mtpid"]=$mtpid;

                    } else{
                        // Password is not valid, display a generic error message
                        //echo "Why?";
						$input_err = "SQL error, pls ask IT!";
                    }
                }elseif(mysqli_stmt_num_rows($stmt) > 1){
                    // Username doesn't exist, display a generic error message
                    //echo mysqli_stmt_num_rows($stmt)."<br>";
					
					mysqli_stmt_bind_result($stmt,$uuid, $userid, $passportid,$cname,$lname,$fname,$mobile,$uemail,$sex,$dob,$apdat,$tdat,$sampleid1,$sampleid2,$testtype,$sendname,$testreason,$hicardno,$per_type,$twrpturgency,$nationality,$mtpid);
					if (mysqli_stmt_fetch($stmt)){
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
					else{
						$input_err = "SQL error, pls ask IT!";
					}
                }else{
					$input_err = "No userid matched.";
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

// Close connection
mysqli_close($conn);

?>