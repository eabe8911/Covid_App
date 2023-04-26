<?php
// automatically generate reports 20210906 YH

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
 
// Connect to local db
require_once "param.php";

$conn = mysqli_connect($hostname, $username, $password);
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$stmt2=$stmt=$stmt3=$stmt4=$stmt5=$stmt6=$stmt7=$stmt8=$stmt9="";
$uuid=$userid=$passportid=$cname=$lname=$fname=$mobile=$uemail=$sex="";
$dob=$ftest=$pcrtest=$vuser1=$testtype=$frptflag=$vuser2=$sendname=$total_sample=$total_appoint="";
$positive_sample=$negative_sample=$empty_sample=$pdfflag=$reported=$unreported=$tested="";
$n_ftest=$n_pcrtest=$freported=$pcrreported=$emailnum=$hicardno="";

$apdat = date("Y-m-d");
$tdat = date("Y-m-d");

if ($uuid==""){ 
$vuser1 = $vuser2 = " ";
$vuser1_err = $vuser2_err = $vuser_err = $sendname_err = $tdat_err = "";
$ftest_err=$pcrtest_err="";
}

//產報告，先透過SQL搜尋，再傳變數給python產報告
// Processing save ftest pcrtest result
// if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Generate_Report"]))
// {
//     $tdat = trim($_POST["tdat"]);
//     $apdat = trim($_POST["apdat"]);
//     // Check if sampleid1 is empty
//     $sql4 = "SELECT uuid,userid,sex,cname,fname,lname,dob,mobile,uemail,ftest,pcrtest,type,telephone,address2,
// 					testtype,passportid,sampleid1,sampleid2,residentpermit,vuser2,
// 					tdat,rdat,sendname,frptflag,qrptflag,hicardno,fpdfflag,pcrpdfflag FROM covid_trans 
// 					WHERE 1=1 
// 					and tdat LIKE ? 
// 					and ((lower(frptflag)='c' and fpdfflag='') or (lower(qrptflag)='c' and pcrpdfflag=''))";
	
// 	if($stmt4 = mysqli_prepare($conn, $sql4))
// 	{
// 		mysqli_stmt_bind_param($stmt4, "s", $p1);
// 		$p1 = $tdat."%";
// 		mysqli_stmt_execute($stmt4);
// 		mysqli_stmt_bind_result($stmt4, $uuid,$userid,$sex,$cname,$fname,$lname,$dob,$mobile,$uemail,$ftest,$pcrtest,$type,$telephone,$address2,$testtype,$passportid,$sampleid1,$sampleid2,$residentpermit,$vuser2,$tdatime,$rdatime,$sendname,$frptflag,$qrptflag,$hicardno,$fpdfflag,$pcrpdfflag);
// 		$file = $tdat."_".date('ymdHi').".tsv";
// 		$g = fopen('/var/www/html/pdf_reports/'.$file, 'w');
// 		$line1 = str_replace(",", "\t", "uuid,userid,sex,cname,fname,lname,dob,mobile,uemail,ftest,pcrtest,type,telephone,address2,testtype,passportid,sampleid1,sampleid2,residentpermit,vuser2,tdat,rdat,sendname,frptflag,qrptflag,hicardno,fpdfflag,pcrpdfflag");
// 		fwrite($g, $line1."\n");
// 		while (mysqli_stmt_fetch($stmt4))
// 		{
// 			$Array = array($uuid,$userid,$sex,$cname,$fname,$lname,$dob,$mobile,$uemail,$ftest,$pcrtest,$type,$telephone,$address2,$testtype,$passportid,$sampleid1,$sampleid2,$residentpermit,$vuser2,$tdatime,$rdatime,$sendname,$frptflag,$qrptflag,$hicardno,$fpdfflag,$pcrpdfflag);
// 			$infos = str_replace("@@", "\t",implode('@@', $Array))."\n";
// 			fwrite($g,$infos);
			
// 		}
// 		fclose($g);
		
// 		system('python3 /var/www/html/generate_report.py /var/www/html/pdf_reports/'.$file);
// 		system('chmod 777 /var/www/html/pdf_reports/*.docx');
		
// 	}
// 	else
// 	{
// 		echo 'NO~';
// 	}
// 	mysqli_stmt_close($stmt4);
// }


//搜尋功能
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"])){

	$apdat = trim($_POST["apdat"]);

	$tdat = trim($_POST["tdat"]);
    // Check if sampleid1 is empty
	
    
	if(empty(trim($_POST["tdat"])))
	{
		$tdat_err = "請輸入報到日期\n";
		

	}

	$sql9 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1 and apdat LIKE ? ";
		if($stmt9 = mysqli_prepare($conn, $sql9))
		{
			mysqli_stmt_bind_param($stmt9, "s", $p1);
			$p1 = $tdat;
			mysqli_stmt_execute($stmt9);
			mysqli_stmt_bind_result($stmt9, $total_appoint);
			mysqli_stmt_fetch($stmt9);
			mysqli_stmt_close($stmt9);
			
		}
	
   //060921 add limit 1 to ensure always select the last row of uuid 
    if($tdat_err == "")
	{
        // Prepare a select statement
		$sql = "SELECT COUNT(*) FROM covid_trans WHERE 1=1 and tdat LIKE ? ";
		if($stmt = mysqli_prepare($conn, $sql))
		{
			mysqli_stmt_bind_param($stmt, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $total_sample);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			//echo $total_sample;
		}


		//完成檢測人數
		$sql2 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
												and tdat LIKE ? 
												and ((testtype = '2' and (lower(qrptflag) = 'c' or lower(qrptflag) = 'y')) or (testtype = '1' and (lower(frptflag) = 'c' or lower(frptflag) = 'y')) or (testtype = '3' and ((lower(qrptflag) = 'c' or lower(qrptflag) = 'y') and (lower(frptflag) = 'c' or lower(frptflag) = 'y'))))";
		if($stmt2 = mysqli_prepare($conn, $sql2))
		{
			mysqli_stmt_bind_param($stmt2, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt2);
			mysqli_stmt_bind_result($stmt2, $tested);
			mysqli_stmt_fetch($stmt2);
			mysqli_stmt_close($stmt2);
			//echo $total_sample;
		}
		//需發快篩報告數量
		$sql5 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
												and tdat LIKE ? 
												and (testtype = '1' or testtype = '3')";
		if($stmt5 = mysqli_prepare($conn, $sql5))
		{
			mysqli_stmt_bind_param($stmt5, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt5);
			mysqli_stmt_bind_result($stmt5, $n_ftest);
			mysqli_stmt_fetch($stmt5);
			mysqli_stmt_close($stmt5);
			//echo $total_sample;
		}
		//已產生快篩報告的數量
		$sql3 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
												and tdat LIKE ? 
												and ((testtype = '1' and lower(fpdfflag) = 'y') or (testtype = '3' and lower(fpdfflag) = 'y'))";
		if($stmt3 = mysqli_prepare($conn, $sql3))
		{
			mysqli_stmt_bind_param($stmt3, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt3);
			mysqli_stmt_bind_result($stmt3, $freported);
			mysqli_stmt_fetch($stmt3);
			mysqli_stmt_close($stmt3);
			//echo $total_sample;
		}
		//需發PCR報告數量
		$sql6 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1apdat
												and tdat LIKE ? 
												and (testtype = '2' or testtype = '3')";
		if($stmt6 = mysqli_prepare($conn, $sql6))
		{
			mysqli_stmt_bind_param($stmt6, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt6);
			mysqli_stmt_bind_result($stmt6, $n_pcrtest);
			mysqli_stmt_fetch($stmt6);
			mysqli_stmt_close($stmt6);
			//echo $total_sample;
		}
		//已產生PCR報告的數量
		$sql7 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
												and tdat LIKE ? 
												and ((testtype = '2' and lower(pcrpdfflag) = 'y') or (testtype = '3' and lower(pcrpdfflag) = 'y'))";
		if($stmt7 = mysqli_prepare($conn, $sql7))
		{
			mysqli_stmt_bind_param($stmt7, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt7);
			mysqli_stmt_bind_result($stmt7, $pcrreported);
			mysqli_stmt_fetch($stmt7);
			mysqli_stmt_close($stmt7);
			//echo $total_sample;
		}
		
		
		//未發報告的數量
		// $unreported = $n_ftest + $n_pcrtest - $freported - $pcrreported;

		//未發E-mail數量
		$sql8 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
				and tdat LIKE ? 
				and (twrpturgency = 'hiurgent' or twrpturgency = 'urgent')
				and ((testtype = '2' and lower(qrptflag) = 'c') or (testtype = '3' and lower(qrptflag) = 'c'))";
		if($stmt8 = mysqli_prepare($conn, $sql8))
		{
			mysqli_stmt_bind_param($stmt8, "s", $p1);
			$p1 = $tdat."%";
			mysqli_stmt_execute($stmt8);
			mysqli_stmt_bind_result($stmt8, $emailnum);
			mysqli_stmt_fetch($stmt8);
			mysqli_stmt_close($stmt8);
			//echo $total_sample;
		}

		// $sql9 = " SELECT COUNT(*) FROM covid_trans WHERE  apdat = ?  ";
		// if($stmt9 = mysqli_prepare($conn, $sql9))
		// {
		// 	mysqli_stmt_bind_param($stmt9, "s", $p1);
		// 	$p1 = $apdat;

		// 	mysqli_stmt_execute($stmt9);
		// 	mysqli_stmt_bind_result($stmt9, $total_apdat);
		// 	mysqli_stmt_fetch($stmt9);
		// 	mysqli_stmt_close($stmt9);
			
		// }	echo ($total_apdat);

		}
	}
	// if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"])){

	// 	$apdat = trim($_POST["apdat"]);
	
	// 	// $tdat = trim($_POST["tdat"]);
	// 	// Check if sampleid1 is empty
		
		
	// 	if(empty(trim($_POST["apdat"])))
	// 	{
	// 		$apdat_err = "請輸入報到日期\n";
			
	
	// 	}
	
		
		
	//    //060921 add limit 1 to ensure always select the last row of uuid 
	// 	if($apdat_err == "")
	// 	{
	// 		$sql9 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1 and apdat = ? ";
	// 		if($stmt9 = mysqli_prepare($conn, $sql9))
	// 		{
	// 			mysqli_stmt_bind_param($stmt9, "s", $p1);
	// 			$p1 = $apdat;
	// 			mysqli_stmt_execute($stmt9);
	// 			mysqli_stmt_bind_result($stmt9, $total_appoint);
	// 			mysqli_stmt_fetch($stmt9);
	// 			mysqli_stmt_close($stmt9);
				
	// 		}
	// 		}
	// 	}
	
	
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>檢體狀態</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/search_info.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            $("#nav").load("nav.html");
        });
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0" style="background-color:#ffffe6;">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <div></div>
                <div>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁">
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁">
                    <!-- <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版目錄"> -->
                </div>
            </div>
        </div>
    <div class="wrapper">
        <h2>檢測狀況</h2>
        <?php echo $total_appoint; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
            <div class="form-group">
            
				<label>報到日期 YYYY-MM-DD</label><br>
                <input type="text":focus name="tdat" value="<?php echo $tdat; ?>" tabindex=1><br>
                <span class="invalid-feedback"><?php echo $tdat_err; ?></span>
				<br>
				<input type="submit" name="Search" class="btn btn-primary" value="Search" tabindex=2>
                <input type="submit"  name="Clear" class="btn btn-primary" value="Clear"  tabindex=3>
            </div>
                <br>
            <h3>請確認以下資料</h3>
			<div class="form-group" style='display:inline'>
				<label>總預約人數</label><br>
                <input type="text" name="total_appoint"  value="<?php echo $total_appoint; ?>" readonly tabindex=11><br>
                <label>總報到人數</label><br>
                <input type="text" name="sampleno"  value="<?php echo $total_sample; ?>" readonly tabindex=4><br>
				<label>完成檢測人數</label><br>
                <input type="text" name="tested"  value="<?php echo $tested; ?>" readonly tabindex=5><br>
				<label>需發快篩報告人數</label><br>
                <input type="text" name="n_ftest"  value="<?php echo $n_ftest; ?>" readonly tabindex=6><br>
				<label>已產生快篩報告人數</label><br>
                <input type="text" name="freported"  value="<?php echo $freported; ?>" readonly tabindex=7><br>
				<label>需發PCR報告人數</label><br>
                <input type="text" name="n_pcrtest"  value="<?php echo $n_pcrtest; ?>" readonly tabindex=8><br>
				<label>已產生PCR報告人數</label><br>
                <input type="text" name="pcrreported"  value="<?php echo $pcrreported; ?>" readonly tabindex=9><br>
				<label>未產生報告人數</label><br>
                <input type="text" name="unreported"  value="<?php echo $unreported; ?>" readonly tabindex=10><br>
				<label>未寄出急件報告</label><br>
                <input type="text" name="unreported"  value="<?php echo $emailnum; ?>" readonly tabindex=10><br>
                <br>
				<br>
			</div> 
        </form>
    </div>
</body>
</html>