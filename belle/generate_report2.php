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

$conn = mysqli_connect("localhost","libo_user","xxx");
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
$stmt2=$stmt=$stmt3=$stmt4=$stmt5=$stmt6=$stmt7=$uuid=$apdat=$userid=$passportid=$cname=$lname=$fname=$mobile=$uemail=$sex=$dob=$ftest=$pcrtest=$vuser1=$testtype=$frptflag=$vuser2=$sendname=$total_sample=$positive_sample=$negative_sample=$empty_sample=$pdfflag=$reported=$unreported=$tested=$n_ftest=$n_pcrtest=$freported=$pcrreported=$hicardno="";

$tdat = date("Y-m-d");
if ($uuid==""){ 
$vuser1 = $vuser2 = " ";
$vuser1_err = $vuser2_err = $vuser_err = $sendname_err = $tdat_err = "";
$ftest_err=$pcrtest_err="";
}

//搜尋功能
//if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"]))
//{
//	
	//$tdat = trim($_POST["tdat"]);
    // Check if sampleid1 is empty
    
	//if(empty(trim($_POST["tdat"])))
	//{
//		$tdat_err = "請輸入報到日期\n";
	//}
	
   //060921 add limit 1 to ensure always select the last row of uuid 
    //if($tdat_err == "")
	//{
        // Prepare a select statement
		//$sql = "SELECT COUNT(*) FROM covid_trans WHERE 1=1 and tdat LIKE ? "; //20220322 olive del
		$sql = "SELECT COUNT(*) FROM covid_trans WHERE 1=1 and apdat=date(now()) and tdat LIKE ? ";
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
		$sql6 = "SELECT COUNT(*) FROM covid_trans WHERE 1=1
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
		$unreported = $n_ftest + $n_pcrtest - $freported - $pcrreported;
	//}
//}
	
	
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>檢驗狀態</title>
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
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
            <h3>請確認以下資料</h3>
			<div class="form-group" style='display:inline'>
                <label>總報到人數</label>
				<br>
                <input name="sampleno" value="<?php echo $total_sample; ?>" readonly tabindex=4>
				<br>
				<label>完成檢測人數</label>
				<br>
                <input name="tested" value="<?php echo $tested; ?>" readonly tabindex=5>
				<br>
				<label>需發快篩報告人數</label>
				<br>
                <input name="n_ftest" value="<?php echo $n_ftest; ?>" readonly tabindex=6>
				<br>
				<label>已產生快篩報告人數</label>
				<br>
                <input name="freported"  value="<?php echo $freported; ?>" readonly tabindex=7>
				<br>
				<label>需發PCR報告人數</label>
				<br>
                <input name="n_pcrtest"  value="<?php echo $n_pcrtest; ?>" readonly tabindex=8>
				<br>
				<label>已產生PCR報告人數</label>
				<br>
                <input name="pcrreported"  value="<?php echo $pcrreported; ?>" readonly tabindex=9>
				<br>
				<label>未產生報告人數</label>
				<br>
                <input name="unreported"  value="<?php echo $unreported; ?>" readonly tabindex=10>
			</div>          
        </form>
    </div>
</body>
</html>