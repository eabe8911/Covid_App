<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
	session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	if (!isset($_SESSION["division"]) || ($_SESSION["division"] > 1)) {
		echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
		echo '<script language="javascript">window.location.replace("menu.php");</script>';
	}
} else {
	header("location: login.php");
}
$user_name = $_SESSION["username"];
$recordcount = "0 / 0";

require_once 'php/checkin_modified_new.php';
require_once "param.php";



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>修改客戶資料</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<link rel="stylesheet" href="css/menu.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
		crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/checkin_modified.css">
	<script src="js/d3.min.js" charset="utf-8"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<script>
		$(function () {
			$("#dialog-confirm").dialog({
				autoOpen: false,
				show: {
					effect: "shake",
					duration: 800
				},
				hide: {
					effect: "fade",
					duration: 800
				}
			});

			$("#nav").load("nav.html");
			var pcrid_uuid = document.getElementById("pcrid_uuid");
			var tdat = document.getElementById("tdat");
			var clear = document.getElementById("clear");
			var checkin_x = document.getElementById("checkin_x");
			var result_msg = document.getElementById("result_msg").value;
			var user_name = document.getElementById("user_name").value;
			switch (result_msg) {
				case '1':   // 第一次進入
					button_checkin(false);
					button_save(false);
					pcrid_uuid.focus();
					break;
				case '2':   // 查詢成功
					//alert(user_name);
					// if(user_name == "cindyT") break;
					// if(tdat && tdat.value){
					//     //clear.focus();
					//     $( "#dialog-confirm" ).dialog({
					//         resizable: false,
					//         height: "auto",
					//         width: 400,
					//         modal: true,
					//         buttons: {
					//             "  再  次  報   到  ": function() {
					//                 checkin_x.click();
					//             $( this ).dialog( "close" );
					//             },
					//             "  修  改  客  戶  資  料  ": function() {
					//             $( this ).dialog( "close" );
					//             clear_focus();
					//             }
					//         }
					//         });
					//         $( "#dialog-confirm" ).dialog( "open" );
					// }else{
					//     checkin_x.focus();
					// }
					break;
				case '3':   // 查詢失敗
					//$("#checkin_x").attr('disabled', true);
					button_checkin(false);
					clear.focus();
					break;
				case '4':   // 報到成功
					//$("#checkin_x").attr('disabled', false);
					button_checkin(true);
					pcrid_uuid.focus();
					break;
				case '5':   // 儲存成功
					pcrid_uuid.focus();
					break;
				default:
					break;
			}


			document.getElementById("pcrid_uuid").addEventListener("keypress", function (event) {
				if (event.key === "Enter") {
					event.preventDefault();
					document.getElementById("search").click();
				}
			})
			document.getElementById("userid_passid").addEventListener("keypress", function (event) {
				if (event.key === "Enter") {
					event.preventDefault();
					document.getElementById("search").click();
				}
			})
			document.getElementById("mobile").addEventListener("keypress", function (event) {
				if (event.key === "Enter") {
					event.preventDefault();
					document.getElementById("search").click();
				}
			})

			if ($("#page").val() === "0") {
				button_record(false);
			} else {
				button_record(true);
			}
			selectElement('sex', "<?php echo ($sex); ?>");
			selectElement('twrpturgency', "<?php echo ($twrpturgency); ?>");

			selectElement('testreason', "<?php echo ($testreason); ?>");
			selectElement('testtype', "<?php echo ($testtype); ?>");
		});

		function selectElement(id, valueToSelect) {
			let element = document.getElementById(id);
			element.value = valueToSelect;
		};


		function clear_focus() {
			$("#defaultCheck2").click();
		};

		function button_checkin(status) {
			if (status === true) {
				$("#checkin_x").attr('disabled', false);
			} else {
				$("#checkin_x").attr('disabled', true);
			}
		};

		function button_save(status) {
			if (status === true) {
				$("#save").attr('disabled', false);
			} else {
				$("#save").attr('disabled', true);
			}
		};

		function button_record(status) {
			if (status === true) {
				$("#previous_record").attr('disabled', false);
				$("#next_record").attr('disabled', false);
			} else {
				$("#previous_record").attr('disabled', true);
				$("#next_record").attr('disabled', true);
			}
		};

	</script>
	<style>
		th,
		td {
			padding: 8px;
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>
	</br>


	<table style="width:98% ; margin:15px " align="center">
		<tr>
			<td colspan="1"></td>
			<td>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" id="defaultCheck2" name="defaultCheck2">
					<label class="form-check-label" for="defaultCheck2" style="color:#003300;">
						<span class="ms-1 d-none d-sm-inline">&ensp;&ensp;&ensp;修改資料</span>開關
					</label>
				</div>
			</td>


			<td style="text-align:right">
				<form action="../print_receipt.php" name="print_receipt_form" method="post" target="_blank">
					<input id="printreceipt" type="submit" name="printreceipt" class="btn btn-success"
						value="   列  印  收  據   ">

				</form>

			</td>
			<td style="text-align:left">
				<form action="../print_inspection.php" name="print_inspection_form" method="post" target="_blank">
					<input id="print" type="submit" name="print" class="btn btn-success" value="   列  印  採  檢  單   " />

				</form>
			</td>
			<form id="form_checkin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="modifiedform"
				method="post" style="display:inline;">
				<input type="hidden" id="uuid" name="uuid" value="<?php echo ($uuid) ?>">

				<td style="text-align:right">
					<input type="submit" id="save" name="save" class="btn btn-success" value=" 儲    存 " disabled>
				</td>

				<td>
					<input type="button" id="cancel" name="cancel" class="btn btn-success" value="取消預約" disabled>
				</td>

		</tr>
		<tr>
			<td style="text-align: right;">PCR ID : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="sampleid2" name="sampleid2" class="form-control"
					value="<?php echo $sampleid2; ?>" readonly>

			</td>
			<td style="text-align: right;">身分證字號 : &ensp;</td>
			<td style="text-align: left;"> <input onchange="IdCardNumberCheck(this.value)" type="text" id="userid"
					name="userid" class="form-control " value="<?php echo $userid; ?>" readonly> </td>

			<td style="text-align: right;">護照號碼 : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="passportid" name="passportid" class="form-control "
					value="<?php echo $passportid; ?>" readonly></td>

			<td style="text-align: right;">台胞證號 : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="mtpid" name="mtpid" class="form-control "
					value="<?php echo $mtpid; ?>" readonly> </td>

		</tr>
		<tr>
			<td style="text-align: right;">快篩 ID : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="sampleid1" name="sampleid1" class="form-control "
					value="<?php echo $sampleid1; ?>" readonly></td>
			<td style="text-align: right;">中文姓名 : &ensp;</td>
			<td style="text-align: left;"> <input onchange="CnameCheck(this.value)" pattern="[\u4E00-\u9FFF]+"
					type="text" id="cname" name="cname" class="form-control " value="<?php echo $cname; ?>" readonly>
			</td>

			<td style="text-align: right;">英文姓名 : &ensp;</td>
			<td style="text-align: left;"> <input onchange="EnameCheck(this.value)" type="text" pattern="[a-zA-Z ,-]+"
					id="fname" name="fname" class="form-control " value="<?php echo $fname; ?>" readonly></td>

			<td style="text-align: right;">手機號碼 : &ensp;</td>
			<td style="text-align: left;"> <input required onchange="MobileCheck(this.value)" pattern='^[0-9]+$'
					type="text" id="mobile" name="mobile" class="form-control" placeholder="0911111111"
					value="<?php echo $mobile; ?>" readonly></td>



		</tr>
		<tr>
			<td style="text-align: right;">預約日期 : &ensp;</td>
			<td style="text-align: left;"> <input type="date" id="apdat" class="form-control" name="apdat"
					placeholder="1990-01-01" value="<?php echo $apdat; ?>" readonly></td>

			<td style="text-align: right;">報到日期 : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="tdat" name="tdat" class="form-control"
					value="<?php echo $tdat; ?>" readonly></td>

			<td style="text-align: right;">報告時效 : &ensp;</td>
			<td style="text-align: left;">
				<select id="twrpturgency" name="twrpturgency" class="form-select" disabled>
					<?php foreach ($twrpturgency_opt as $value => $label): ?>
						<option value="<?php echo $value; ?>" <?php if ($twrpturgency == $value) {
							   echo ' selected="selected"';
						   } ?>><?php echo $label; ?></option>
					<?php endforeach ?>
				</select>

			</td>
			<td style="text-align: right;">付款方式 : &ensp;</td>
			<td style="text-align: left;">
				<select id="payflag" name="payflag" class="form-select" disabled>
					<?php foreach ($payflag_opt as $value => $label): ?>
						<option value="<?php echo $value; ?>" <?php if ($payflag == $value) {
							   echo ' selected="selected"';
						   } ?>><?php echo $label; ?></option>
					<?php endforeach ?>
				</select>

			</td>


		</tr>

		<tr>
			<td style="text-align: right;">生日 : &ensp;</td>
			<td style="text-align: left;"><input required type="date" id="dob" name="dob" class="form-control " readonly
					value="<?php echo $dob; ?>"></td>
			<td style="text-align: right;">性別 : &ensp;</td>
			<td style="text-align: left;">
				<div class="col-auto">

					<select id="sex" name="sex" class="form-select" disabled>
						<?php foreach ($gender_opt as $value => $label): ?>
							<option value="<?php echo $value; ?>" <?php if ($sex == $value) {
								   echo ' selected="selected"';
							   } ?>><?php echo $label; ?></option>
						<?php endforeach ?>
					</select>

				</div>
			</td>
			<td style="text-align: right;">國籍 : &ensp;</td>
			<td style="text-align: left;"><input type="text" id="nationality" name="nationality" class="form-control "
					value="<?php echo $nationality; ?>" readonly></td>
			<td style="text-align: right;">單位 : &ensp;</td>
			<td style="text-align: left;"><input type="text" id="unit" name="unit" class="form-control "
					value="<?php echo $unit; ?>" readonly></td>
		</tr>

		<tr>

			<td style="text-align: right;">健保卡號 : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="hicardno" name="hicardno" class="form-control "
					value="<?php echo $hicardno; ?>" readonly></td>
			<td colspan="1" style="text-align: right;">E-mail : &ensp;</td>
			<td style="text-align: left;"><input type="email" id="email" name="email" class="form-control "
					value="<?php echo $email; ?>" readonly></td>
		</tr>

		<tr>

			<td style="text-align: right;">統編抬頭 : &ensp;</td>
			<td style="text-align: left;"><input type="text" id="companytitle" name="companytitle" class="form-control "
					value="<?php echo $companytitle; ?>" readonly>
				<div id="PointMsgSendname"></div>
			</td>

			<td style="text-align: right;">統編號碼 : &ensp;</td>
			<td style="text-align: left;"> <input onchange="SendnameCheck(this.value)" pattern='[0-9]+' type="text"
					id="sendname" name="sendname" class="form-control " value="<?php echo $sendname; ?>" readonly>
				<div id="PointMsgSendname"></div>
			</td>

			<td style="text-align: right;">收據號碼 : &ensp;</td>
			<td style="text-align: left;"><input type="text" id="receiptid" name="receiptid" class="form-control "
					value="<?php echo $receiptid; ?>" readonly></td>


		</tr>
		<tr>
			<td style="text-align: right;">檢測類型 : &ensp;</td>
			<td style="text-align: left;">

				<select id="testtype" name="testtype" class="form-select" disabled>
					<?php foreach ($testtype_opt as $value => $label): ?>
						<option value="<?php echo $value; ?>" <?php if ($testtype == $value) {
							   echo ' selected="selected"';
						   } ?>><?php echo $label; ?></option>
					<?php endforeach ?>
				</select>

			</td>

			<td style="text-align: right;">備註 : &ensp;</td>
			<td style="text-align: left;"> <input type="text" id="memo" name="memo" class="form-control "
					value="<?php echo $memo; ?>" readonly>
			</td>

		</tr>

	</table>
	</form>

</body>
<script src="js/checkin_modified20230314.js"></script>


</html>