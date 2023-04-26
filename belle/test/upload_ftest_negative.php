<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// inspection confirmed   061321 WillieK
// If verifier 2 confirm the fast result, it will put the flag C

ini_set("display_errors","on");
error_reporting(E_ALL);

session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //header("location: welcome.php");
    //header("location: menu.html");
//    exit;
}
else
{
    header("location: login.php");
}
 

require_once ("php/log.php");
// Connect to local db

$conn = mysqli_connect("localhost","libo_user","xxx");
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$stmt2=$stmt=$uuid=$apdat= $userid=$passportid=$cname=$lname=$fname=$mobile=$uemail=$sex=$dob=$ftest=$pcrtest=$vuser1=$testtype=$frptflag=$vuser2=$sendname=$total_sample=$positive_sample=$negative_sample=$empty_sample="";
$tdat = date("Y-m-d");
if ($uuid==""){ 
$vuser1 = $vuser2 = " ";
$vuser1_err = $vuser2_err = $vuser_err = $sendname_err = $tdat_err = "";
$ftest_err=$pcrtest_err="";
}

// Processing save ftest pcrtest result
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Upload"]))
{
    //echo $_POST["ftest"];
    //echo $_POST["pcrtest"]; 
    //echo $_POST["vuser1"];
    //echo $_POST["uuid"];
    
    
    if (empty(trim($_POST["vuser2"]))|| empty(trim($_POST["vuser2"])) || empty(trim($_POST["sendname"]))){
        echo "<h4>";
        echo "檢測人員ID 不能為空! 覆核人員ID 不能為空! 採檢批號 不能為空!";
        echo "</h4>";
        //$sampleid1 = $_POST["sampleid1"];
        //$sampleid2 = $_POST["sampleid2"];
    }
    else {
        //060921 add record the report date , willieK
        $sql5 = "update covid_test set vuser1= ?, vuser2= ?,frptflag='C', ftest='negative', rdat=(select NOW() from dual) where 1=1
																			and tdat LIKE ? 
																			and (testtype = '1' or testtype = '3') 
																			and (ftest='' or ftest is null or ftest='NA') 
																			and sendname=?";
        if ($stmt5 = mysqli_prepare($conn, $sql5)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt5, "ssss", $p1,$p2,$p3,$p4);
        
        $p1 = $_POST["vuser1"];
        $p2 = $_POST["vuser2"];
		$p3 = $_POST["tdat"]."%";
        $p4 = $_POST["sendname"];

        $sql_comment=$_SESSION["username"]."_version1: update covid_test set vuser1= {$p1}, vuser2={$p2},frptflag='C', ftest='negative', rdat=(select NOW() from dual) where 1=1
        and tdat LIKE {$p3} 
        and (testtype = '1' or testtype = '3') 
        and (ftest='' or ftest is null or ftest='NA') 
        and sendname={$p4}";
        write_sql($sql_comment);
        }
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt5)){
            //echo "<h1 style="background-color:hsla(9, 100%, 64%, 0.5);">";
            echo "<h4>";
            echo "登錄陰性結果成功!" ;
            echo "</h4>";
            $save_result = '登錄陰性結果成功!';
            write_sql($save_result);
        } else {
            echo "Error: " . $sql5 . "<br>" . mysqli_error($conn);
            $save_result = "Error: " . $sql5 . "<br>" . mysqli_error($conn);
            write_sql($save_result);
        }    
        mysqli_stmt_close($stmt5);
    }


    //$vuser1 =  $_POST["vuser1"];
    }


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"])){
	
	$vuser1 = trim($_POST["vuser1"]); 
    $vuser2 = trim($_POST["vuser2"]);
	$sendname = trim($_POST["sendname"]);
	$tdat = trim($_POST["tdat"]);
    // Check if sampleid1 is empty
    
	if(empty(trim($_POST["vuser1"])))
	{
		$vuser1_err = "請掃描檢測人員ID";
	}
	
	if(empty(trim($_POST["vuser2"])))
	{
		$vuser2_err = "請掃描覆核人員ID";
	}
	
	if(empty(trim($_POST["sendname"])))
	{
		$sendname_err = "請掃描採檢批號";
	}
	
	if(empty(trim($_POST["tdat"])))
	{
		$tdat_err = "請輸入報到日期\n";
	}
	
    
   //060921 add limit 1 to ensure always select the last row of uuid 
    if($vuser1_err == "" && $vuser2_err == "" && $sendname_err == "" && $tdat_err == "")
	{
        // Prepare a select statement
		$sql = "SELECT COUNT(*) FROM covid_test WHERE 1=1
												and tdat LIKE ? 
												and (testtype = '1' or testtype = '3') 
												and sendname=?";
		if($stmt = mysqli_prepare($conn, $sql))
		{
			mysqli_stmt_bind_param($stmt, "ss", $p1,$p2);
			$p1 = $tdat."%";
			$p2 = $sendname;
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $total_sample);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			//echo $total_sample;
		}
		
		$sql2 = "SELECT COUNT(*) FROM covid_test WHERE 1=1
												and tdat LIKE ? 
												and (testtype='1' or testtype = '3')
												and ftest='positive' 
												and sendname=?";
		if($stmt2 = mysqli_prepare($conn, $sql2))
		{
			mysqli_stmt_bind_param($stmt2, "ss", $p1,$p2);
			$p1 = $tdat."%";
			$p2 = $sendname;
			mysqli_stmt_execute($stmt2);
			mysqli_stmt_bind_result($stmt2, $positive_sample);
			mysqli_stmt_fetch($stmt2);
			mysqli_stmt_close($stmt2);
			//echo $total_sample;
		}
		
		$sql3 = "SELECT COUNT(*) FROM covid_test WHERE 1=1
												and tdat LIKE ? 
												and (testtype='1' or testtype = '3')
												and ftest='negative' 
												and sendname=?";
		if($stmt3 = mysqli_prepare($conn, $sql3))
		{
			mysqli_stmt_bind_param($stmt3, "ss", $p1,$p2);
			$p1 = $tdat."%";
			$p2 = $sendname;
			mysqli_stmt_execute($stmt3);
			mysqli_stmt_bind_result($stmt3, $negative_sample);
			mysqli_stmt_fetch($stmt3);
			mysqli_stmt_close($stmt3);
			//echo $total_sample;
		}
		
		
		$sql4 = "SELECT COUNT(*) FROM covid_test WHERE 1=1
												and tdat LIKE ? 
												and (testtype='1' or testtype = '3')
												and (ftest='' or ftest is null or ftest='NA') 
												and sendname=?";
		if($stmt4 = mysqli_prepare($conn, $sql4))
		{
			mysqli_stmt_bind_param($stmt4, "ss", $p1,$p2);
			$p1 = $tdat."%";
			$p2 = $sendname;
			mysqli_stmt_execute($stmt4);
			mysqli_stmt_bind_result($stmt4, $empty_sample);
			mysqli_stmt_fetch($stmt4);
			mysqli_stmt_close($stmt4);
			//echo $total_sample;
		}
	}
	
            // Attempt to execute the prepared statement
        //if (empty($vuser1_err)&&empty($vuser2_err)){
        //    if(mysqli_stmt_execute($stmt)){
        //        // Store result
        //        //echo "run";
        //        mysqli_stmt_store_result($stmt);
        //        
        //        // Check if username exists, if yes then verify password
        //        if(mysqli_stmt_num_rows($stmt) == 1){                    
        //            // Bind result variables
        //            mysqli_stmt_bind_result($stmt,$uuid,$apdat, $userid, $passportid,$cname,$lname,$fname,$mobile,$uemail,$sex,$dob,$ftest,$pcrtest,$vuser1,$sampleid1,$sampleid2,$testtype,$vuser2,$frptflag );
        //            if(mysqli_stmt_fetch($stmt)){
        //                //if(password_verify($password, $hashed_password)){
        //                    //if($password==$hashed_password){
        //                    // Password is correct, so start a new session
        //                    //session_start();
        //                    
        //                    // Store data in session variables
        //                    //$_SESSION["loggedin"] = true;
        //                    //$_SESSION["id"] = $id;"
        //                    //$_SESSION["username"] = $username;
		//
        //                    //echo "<br>";
        //                    //echo "身份證:"."$userid"."<br>";
        //                    //echo "護照號碼:"."$passportid"."<br>";
        //                    //echo "中文名:"."$cname"." ";
        //                    //echo "FirstName:"."$fname".",";
        //                    //echo "Last Name:"."$lname"."<br>";
        //                    //echo "生日:"."$dob"."<br>";
        //                    //echo "性別:"."$sex"."<br>";
        //                    //echo "手機:"."$mobile"."<br>";
        //                    //echo "Email:"."$uemail"."<br>";
		//
		//
		//
        //                                                   
        //                    // Redirect user to welcome page
        //                    //header("location: menu.html");
        //                    //$sampleid1=" ";
        //                    //$sampleid2=" ";
        //                    //echo $uuid;
        //                } else{
        //                    // Password is not valid, display a generic error message
        //                    $sampleid_err = "SQL error ,pls ask IT!."; 
        //                    //xxecho "die";
        //                }
        //                //$sampleid_err = "More than 1 sampleid matched!";
        //            }
        //        } else{
        //            // Username doesn't exist, display a generic error message
        //            $sampleid_err = "sampleid rows not eq 1.";
        //        }
        //    
		//
        //    // Close statement
            
			
        //}
    } 
    
    // Close connection
    mysqli_close($conn);

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>批量上傳快篩陰性結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>批量上傳快篩陰性結果</h2>
        <h3>
        <p>請刷檢測人員、覆核人員及採檢批號</p>
        </h3>
        <?php 
        if(!empty($vuser1_err)){
            echo '<div class="alert alert-danger">' . $vuser1_err . '</div>';
        }
		if(!empty($vuser2_err)){
            echo '<div class="alert alert-danger">' . $vuser2_err . '</div>';
        }
		if(!empty($sendname_err)){
            echo '<div class="alert alert-danger">' . $sendname_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
            <div class="form-group">
            
                <label>檢測人員ID</label>
                <input type="text":focus name="vuser1" class="form-control <?php echo (!empty($vuser1_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $vuser1; ?>" tabindex=1>
                <span class="invalid-feedback"><?php echo $vuser_err; ?></span>
               
                <label>覆核人員ID</label>
                <input type="text":focus name="vuser2" class="form-control <?php echo (!empty($vuser2_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $vuser2; ?>" tabindex=2>
                <span class="invalid-feedback"><?php echo $vuser_err; ?></span>
				
				<label>採檢批號</label>
                <input type="text":focus name="sendname" class="form-control <?php echo (!empty($sendname_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sendname; ?>" tabindex=3>
                <span class="invalid-feedback"><?php echo $sendname_err; ?></span>
                
				<label>報到日期 YYYY-MM-DD</label>
                <input type="text":focus name="tdat" class="form-control <?php echo (!empty($tdat_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $tdat; ?>" tabindex=3>
                <span class="invalid-feedback"><?php echo $tdat_err; ?></span>
				<br>
				<input type="submit" name="Search" class="btn btn-primary" value="Search" tabindex=4>
                <input type="submit"  name="Clear" class="btn btn-primary" value="Clear"  tabindex=5>
            </div>
                <br>
            <h3>請確認以下資料</h3>
			<div class="form-group" style='display:inline'>
                <label>總報到人數</label>
                <input type="text" name="sampleno" class="form-control " value="<?php echo $total_sample; ?>" readonly tabindex=5>
				<label>快篩陽性人數</label>
                <input type="text" name="positiveno" class="form-control " value="<?php echo $positive_sample; ?>" readonly tabindex=7>
				<label>快篩陰性人數</label>
                <input type="text" name="negativeno" class="form-control " value="<?php echo $negative_sample; ?>" readonly tabindex=8>
				<label>未登錄人數</label>
                <input type="text" name="emptyno" class="form-control " value="<?php echo $empty_sample; ?>" readonly tabindex=9>
                <br>
				<br>
			</div>
            
			<h4>按 Upload 以登錄快篩陰性資料</h4>
			<div class="form-group" style='display:inline'>
                
				<input type="submit" name="Upload" class="btn btn-primary" value="Upload" tabindex=10>
				
            </div>
            
                
            
            
           
        </form>
    </div>
</body>
</html>