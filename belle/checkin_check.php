<?php
ini_set("display_errors", "off");
error_reporting(E_ALL);
session_start();
require_once("./class/Receipt.php");
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] <> true) {
	header("location: login.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["print"])) {
	$uuid = $_SESSION["uuid"];
	$userid = $_SESSION["userid"];
	$passportid = $_SESSION["passportid"];
	$xmappoint = $_SESSION["xmappoint"];
	$sampleid1 = $_SESSION["sampleid1"];
	$sampleid2 = $_SESSION["sampleid2"];
	$sampleid = '';
	$apdat = $_SESSION["apdat"];
	$tdat = $_SESSION["tdat"];
	// $receiptid = '001';

	//$_rpturgency = trim($csv[14]);
	$sample = substr($sampleid2, 1, 2);
	switch ($sample) {
		case 'QH':
		case 'QL':
			$sampleid = substr($sampleid2, 8);
			break;
		default:
			$sampleid = substr($sampleid2, 7);
			break;
	}

	$cname = $_SESSION["cname"];
	$fname = $_SESSION["fname"];
	$sendname = $_SESSION["sendname"];
	$lname = $_SESSION["lname"];
	$mobile = $_SESSION["mobile"];
	$sex = $_SESSION["sex"];
	$dob = $_SESSION["dob"];
	$testreason = $_SESSION["testreason"];
	$hicardno = $_SESSION["hicardno"];
	$twrpturgency = $_SESSION["twrpturgency"];
	$uemail = $_SESSION["email"];
	$mtpid = $_SESSION["mtpid"];
	$nationality = $_SESSION["nationality"];

	if (!empty($cname)) {
		$name = $_SESSION["cname"];
	} else {
		$name = $_SESSION["fname"];
	}


	if (!empty($sampleid2)) {
		if ($twrpturgency == 'hiurgent') {
			$inspection_type = "特急件";
		} elseif ($twrpturgency == 'urgent') {
			$inspection_type = "急件";
		} else {
			$inspection_type = "一般件";
		}
	}


}
?>
<!DOCTYPE html>
<html>

<head>
	<!-- <link rel="stylesheet" href="css/print_receipt.css"> -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>客戶報到</title>
	<link rel="stylesheet" crossorigin="anonymous"
		href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=">
	<link rel="stylesheet" crossorigin="anonymous"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
		integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=">
	<link rel="stylesheet" crossorigin="anonymous"
		href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.5/css/ui.jqgrid.min.css"
		integrity="sha256-3oIi71IMpsoA+8ctSTIM+6ScXYjyZEV06q6bbK6CjsM=">


</head>

<body>
	<br />
	<div  style=" border-radius: 10px;">
			<table style="width:50%; border:1px solid; border-radius:20px;">


				<tr style="font-size: x-large;">
					<th colspan=5 style="text-align: center ;border: 1px solid black; padding: 10px;">報告時效性</th>
					<!-- <td colspan=5 style="text-align: center ;"></td> -->
				</tr>
				<tr style="font-size: large;">
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="twrpturgency" value="hiurgent" /> 特急件 ($4500)</label></td>
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="twrpturgency" value="urgent" /> 急件 ($3500)</label></td>
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="twrpturgency" value="normal" /> 一般件 ($2500)</label></td>
				</tr>

			</table>
			<table style="width:50%; border:1px solid; border-radius:20px;">

				<tr style="font-size: x-large;">
					<th colspan=5 style="text-align: center ;border: 1px solid black; padding: 10px;">報告時效性</th>
				</tr>

				<tr style="font-size: large;">

					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="pay" value="cash" /> 現 金</label></td>
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="pay" value="creditcard" />刷 卡</label></td>
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="pay" value="cash" /> 匯 款</label></td>
					<td style="text-align: center;"><label><input type="radio" style=" width: 20px ; height: 20px;"
								name="pay" value="creditcard" />月 結</label></td>


				</tr>
				<tr style="font-size: large;">

					<td colspan=2 style="text-align: center;"><input type="button" class="btn btn-primary"
							value="確認報到" />
					</td>
					<td colspan=2 style="text-align: center;"><input type="button" class="btn btn-primary" value="取消">
					</td>

				</tr>

			</table>
	</div>

</body>

</html>