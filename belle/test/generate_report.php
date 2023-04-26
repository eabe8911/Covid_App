<?php
// automatically generate reports 20210906 YH

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
	session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	if (($_SESSION["division"] == 0) || ($_SESSION["division"] == 1)) {
	} else {
		echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
		echo '<script language="javascript">window.location.replace("menu.php");</script>';
	}
} else {
	header("location: login.php");
}

require_once('php/log.php');

// Connect to local db
$conn = mysqli_connect("localhost", "libo_user", "xxx");
mysqli_select_db($conn, "libodb");

// Define variables and initialize with empty values
//$stmt2=$stmt=$stmt3=$stmt4=$stmt5=$stmt6=$stmt7=$uuid=$apdat=$userid=$passportid=$cname=$lname=$fname=$mobile=$uemail=$sex=$dob=$ftest=$pcrtest=$vuser1=$testtype=$frptflag=$vuser2=$sendname=$total_sample=$positive_sample=$negative_sample=$empty_sample=$pdfflag=$reported=$unreported=$tested=$n_ftest=$n_pcrtest=$freported=$pcrreported="";
$save_result = '';
$nationality = $stmt = $testid = $uuid = $userid = $sex = $cname = $fname = $lname = $ename = $dob = $mobile = $uemail = $ftest = $pcrtest = $address2 = $passportid = $sampleid1 = $sampleid2 = $residentpermit = $tdat = $rdat = $sendname = $frptflag = $qrptflag = $residentpermitid = $vuser1 = $vuser2 = $testtype = '';
$testid_err = "";
$ftest_err = $pcrtest_err = $testtype = "";
$frptflag = "";

//產報告，先透過SQL搜尋，再傳變數給python產報告
// Processing save ftest pcrtest result
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Generate_Report"])) {
	$testid = trim($_POST["testid"]);
	system('python3 /var/www/html/belle/test/update_report.py ' . $testid);
	$sql_comment = $_SESSION["username"] . ": " . 'python3 /var/www/html/belle/test/update_report.py ' . $testid;
	write_sql($sql_comment, "BeanCode");

	// 要做出不同國籍的報告
	system('python3 /var/www/html/belle/test/write_pdf_report.py ' . $testid);
	$sql_comment = $_SESSION["username"] . ": " . 'python3 /var/www/html/belle/test/write_pdf_report.py' . $testid;
	write_sql($sql_comment, "BeanCode");
}


//搜尋功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Search"])) {

	$testid = trim($_POST["testid"]);
	// Check if testid is empty
	if (empty(trim($testid))) {
		$testid_err = "請輸入快篩ID或PCR ID\n";
	}

	//060921 add limit 1 to ensure always select the last row of uuid 
	if ($testid_err == "") {
		// Prepare a select statement
		$pattern = "/^Q[0-9]{9}|^QH[0-9]{9}|^F[0-9]{9}/";
		preg_match($pattern, $testid, $matches);
		if (empty($matches)) {
			$save_result = "快篩ID或PCR ID格式錯誤<br>快篩ID為F開頭，PCR ID為Q開頭";
		} else {
			if ($testid[0] == 'F') {
				$sql = "SELECT uuid, userid, sex, cname, fname, lname, dob, mobile, uemail, ftest, pcrtest,
							address2, passportid, sampleid1, sampleid2, residentpermit, tdat, rdat, sendname,
							frptflag, qrptflag, hicardno  ,vuser1,vuser2,testtype,nationality
						FROM covid_test WHERE 1=1 and sampleid1=? ";
			} else {
				$sql = "SELECT uuid, userid, sex, cname, fname, lname, dob, mobile, uemail, ftest, pcrtest,
							address2, passportid, sampleid1, sampleid2, residentpermit, tdat, rdat, sendname,
							frptflag, qrptflag, hicardno ,vuser1,vuser2,testtype,nationality
						FROM covid_test WHERE 1=1 and sampleid2=? ";
			}
			if ($stmt = mysqli_prepare($conn, $sql)) {
				mysqli_stmt_bind_param($stmt, "s", $p1);
				$p1 = $testid;
				if (mysqli_stmt_execute($stmt)) {
					mysqli_stmt_store_result($stmt);
					if (mysqli_stmt_num_rows($stmt) == 0) {
						$save_result = "查無此ID";
					} else {
						mysqli_stmt_bind_result(
							$stmt,
							$uuid,
							$userid,
							$sex,
							$cname,
							$fname,
							$lname,
							$dob,
							$mobile,
							$uemail,
							$ftest,
							$pcrtest,
							$address2,
							$passportid,
							$sampleid1,
							$sampleid2,
							$residentpermit,
							$tdat,
							$rdat,
							$sendname,
							$frptflag,
							$qrptflag,
							$hicardno,
							$vuser1,
							$vuser2,
							$testtype,
							$nationality,
							// $vuser1_select = $vuser1,
							// $vuser2_select = $vuser2
						);
						mysqli_stmt_fetch($stmt);
						$ename = $fname . " " . $lname;
						$vuser1_select = $vuser1;
						$vuser2_select = $vuser2;
						if (trim($residentpermit) == 'Y') {
							$residentpermitid = $hicardno;
						}
						if ($sampleid1 != "") {
							$sql_comment = $_SESSION["username"] . ": " . $sql . " " . $sampleid1;
						} else {
							$sql_comment = $_SESSION["username"] . ": " . $sql . " " . $sampleid2;
						}

						write_sql($sql_comment, "BeanCode");
					}
				}

				mysqli_stmt_close($stmt);
			}
		}
	}
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>重新製作報告</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/update_report.css">
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
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" value="" id="defaultCheck3">
					<label class="form-check-label" for="defaultCheck3" style="color:#003300;">
						<span class="ms-1 d-none d-sm-inline">重新製作報告</span>開關
					</label>
				</div>
				<div></div>
				<div>
					<a style="margin:1em;" class="nav-link px-0"><?php echo $save_result; ?> <span class="d-none d-sm-inline"></span></a>
				</div>
				<div></div>
				<div>
					<input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁"></input>
					<input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁"></input>
				</div>
			</div>
		</div>
		<div class="col py-3">
			<h2>重新製作報告</h2>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style='display:inline'>
				<div class="form-group">
					<div class="col-md-3">
						<label>快篩 ID 或 PCR ID</label>
						<input type="text" :focus name="testid" class="form-control <?php echo (!empty($testid_err)) ? 'is-invalid' : ''; ?>" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $testid; ?>">
						<span class="invalid-feedback"><?php echo $testid_err; ?></span>
						<br>
					</div>
					<input type="submit" name="Search" class="btn btn-success" value="搜尋">
					<input type="submit" name="Clear" class="btn btn-success" value="清除">
				</div>
				<br>
				<h3>請確認以下資料</h3>

				<div class="row g-3" id="read">
					<div class="col-md-3" hidden>
						<label>UUID</label>
						<input type="text" name="uuid" id="uuid" class="form-control " value="<?php echo $uuid; ?>">
					</div>

					<div class="col-md-3">
						<label>中文姓名</label>
						<input onchange="CnameCheck(this.value)" pattern="[\u4E00-\u9FFF]+" type="text" name="cname" id="cname" class="form-control " value="<?php echo $cname; ?>">
						<div id="PointMsgCname"></div>
					</div>

					<div class="col-md-3">
						<label>英文姓名</label>
						<input onchange="EnameCheck(this.value)" type="text" pattern="[a-zA-Z ,-]+" id="ename" name="ename" class="form-control " value="<?php echo $ename; ?>">
						<input hidden type="text" id="fname" name="fname" class="form-control " value="<?php echo $fname; ?>">
						<input hidden type="text" id="lname" name="lname" class="form-control " value="<?php echo $lname; ?>">
						<div id="PointMsgEname"></div>
					</div>

					<div class="col-md-3">
						<label>檢測類型</label>
						<input required onchange="TesttypeCheck(this.value)" pattern="[1-3]{1}" type="text" name="testtype" id="testtype" class="form-control " value="<?php echo $testtype; ?>">
						<div id="PointMsgTesttype"></div>
						<ul>
							<li>1: 快篩 only</li>
							<li>2: qPCR only</li>
							<li>3: 兩者都做</li>
						</ul>
					</div>

					<div></div>

					<div class="col-md-3">
						<label>性別</label>
						<input required onchange="GenderCheck(this.value)" type="text" name="sex" id="sex" class="form-control " value="<?php echo $sex; ?>">
						<p>僅限輸入<br> "男 / Male" ; "女 / Female" ; "NA"</p>
						<div id="PointMsgGender"></div>
					</div>

					<div class="col-md-3">
						<label>生日</label>
						<input required type="date" name="dob" id="dob" class="form-control " value="<?php echo $dob; ?>">
					</div>
					<div class="col-md-3">
						<label>手機號碼</label>
						<input required onchange="MobileCheck(this.value)" pattern='^[0-9]+$' type="text" name="mobile" id="mobile" class="form-control " value="<?php echo $mobile; ?>">
						<div id="PointMsgMobile"></div>
					</div>

					<div></div>

					<div class="col-md-6">
						<label>E-mail</label>
						<input onchange="EmailCheck(this.value)" type="email" type="text" name="uemail" id="uemail" class="form-control " value="<?php echo $uemail; ?>">
						<div id="PointMsgEmail"></div>
					</div>

					<div></div>

					<div class="col-md-3">
						<label>身分證號碼</label>
						<input onchange="IdCardNumberCheck(this.value)" type="text" name="userid" id="userid" class="form-control " value="<?php echo $userid; ?>">
						<div id="PointMsgIdCardNumber"></div>
					</div>
					<div class="col-md-3">
						<label>居留證號碼</label>
						<input type="text" name="residentpermitid" id="residentpermitid" class="form-control " value="<?php echo $residentpermitid; ?>">
					</div>
					<div class="col-md-3">
						<label>護照號碼</label>
						<input type="text" name="passportid" id="passportid" class="form-control " value="<?php echo $passportid; ?>">
					</div>

					<div></div>

					<div class="col-md-6">
						<label>住址</label>
						<input type="text" name="address2" id="address2" class="form-control " value="<?php echo $address2; ?>">
					</div>

					<div class="col-auto">
						<label>國籍 (須持日本入境檢驗證明才需輸入)</label>
						<input type="text" id="nationality" name="nationality" class="form-control " value="<?php echo $nationality; ?>">
					</div>

					<div></div>

					<div class="col-md-3">
						<label>快篩 ID</label>
						<input onchange="TesttypeCheck(this.value)" pattern="^F[0-9]{9}" type="text" name="sampleid1" id="sampleid1" class="form-control " value="<?php echo $sampleid1; ?>">
						<!-- <div id="PointMsgTesttypeCheck"></div> -->
					</div>

					<div class="col-md-3">
						<label>快篩 結果</label>
						<input type="text" name="ftest" id="ftest" class="form-control " value="<?php echo $ftest; ?>">

					</div>

					<div></div>

					<div class="col-md-3">
						<label>PCR ID</label>
						<input onchange="TesttypeCheck(this.value)" pattern="^Q[0-9]{9}|^QH[0-9]{9}" type="text" name="sampleid2" id="sampleid2" class="form-control " value="<?php echo $sampleid2; ?>">
						<div id="PointMsgTesttypeCheck"></div>
					</div>

					<div class="col-md-3">
						<label>PCR 結果</label>
						<input type="text" name="pcrtest" id="pcrtest" class="form-control " value="<?php echo $pcrtest; ?>">
						<!-- <select id="pcrtest_select" name="pcrtest" size="3" value="<?php echo $pcrtest; ?>" hidden>
							<option value="NA">未判定</option>
							<option value="positive">陽性 positive</option>
							<option value="negative">陰性 negative</option>
						</select> -->
					</div>
					<div id="PointMsg1"></div>

					<div></div>

					<div class="col-md-3">
						<label>檢測醫檢師</label>
						<input type="text" name="vuser1" id="vuser1" class="form-control " value="<?php echo $vuser1; ?>">
					</div>
					<div class="col-md-3">
						<label>覆核醫檢師</label>
						<input type="text" name="vuser2" id="vuser2" class="form-control " value="<?php echo $vuser2; ?>">
					</div>

					<div></div>

				</div>

				<p>按下 "產出報告" 後，請等待 30 秒、再查看重新製作的報告。</p>
				<div class="form-group" style='display:inline'>
					<input type="submit" id="Generate_Report" name="Generate_Report" class="btn btn-success" value="產出報告" disabled>
				</div>

			</form>

		</div>



	</div>
</body>
<script src="js/generate_report.js"></script>

</html>