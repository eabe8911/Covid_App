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
$stmt="";
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



//搜尋功能
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"])){

	// $apdat = trim($_POST["apdat"]);

	$tdat = trim($_POST["tdat"]);
    // Check if sampleid1 is empty
	
    
	if(empty(trim($_POST["tdat"])))
	{
		$tdat_err = "請輸入報到日期\n";
		

	}
    $sql = "SELECT sampleid2 FROM covid_trans WHERE apdat LIKE ? and tdat is not null";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "$row";
      }
    }
    
    mysqli_free_result($result);
    mysqli_close($conn);
  

 
    
	// $sql = "SELECT sampleid2 FROM covid_trans WHERE apdat LIKE ? and tdat is not null";
	// 	if($stmt = mysqli_prepare($conn, $sql))
	// 	{
	// 		mysqli_stmt_bind_param($stmt, "s", $p1);
	// 		$p1 = $tdat;
	// 		mysqli_stmt_execute($stmt);
	// 		mysqli_stmt_bind_result($stmt, $total_appoint);
	// 		mysqli_stmt_fetch($stmt);
	// 		mysqli_stmt_close($stmt);
	// 	}
		


	}
    // mysqli_close($conn);
	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查詢已報到客戶資料</title>
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
        <h2>核對已報到繳費客戶</h2>
       
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
				<h4><?php echo $total_appoint; ?></h4><br>
               
                <br>
				<br>
			</div> 
        </form>
    </div>
</body>
</html>