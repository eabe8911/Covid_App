<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
	session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	// if (!isset($_SESSION["division"]) || ($_SESSION["division"] > 1)) {
	// 	echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
	// 	echo '<script language="javascript">window.location.replace("menu.php");</script>';
	// }
} else {
	header("location: login.php");
}

$user_name = $_SESSION["username"];
$recordcount = "0 / 0";


require_once 'php/checkin_new.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>客戶報到</title>
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
	<link rel="stylesheet" href="sweetalert2/dist/sweetalert2.min.css">
	<script src="sweetalert2/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/tonytomov/jqGrid@4.6.0/css/ui.jqgrid.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/tonytomov/jqGrid@4.6.0/js/jquery.jqGrid.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>
	<script>
		$(function () {
			$("#nav").load("nav.html");
			// var pcrid_uuid = document.getElementById("pcrid_uuid");
			// var tdat = document.getElementById("tdat");
			// var clear = document.getElementById("clear");
			var checkin_x = document.getElementById("checkin_x");
			var result_msg = document.getElementById("result_msg").value;
			// var user_name = document.getElementById("user_name").value;

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

			switch (result_msg) {
				case '1':   // 第一次進入
					button_checkin(false);
					// button_save(false);
					pcrid_uuid.focus();
					break;
				case '2':   // 查詢成功
					//alert(user_name);
					// if(user_name == "cindyT") break;
					if (tdat && tdat.value) {
						//clear.focus();
						$("#dialog-confirm").dialog({
							resizable: false,
							height: "auto",
							width: 400,
							modal: true,
							buttons: {
								"  再  次  報   到  ": function () {
									checkin_x.click();
									$(this).dialog("close");
								},
								"  修  改  客  戶  資  料  ": function () {
									$(this).dialog("close");
									clear_focus();
								}
							}
						});
						$("#dialog-confirm").dialog("open");
					} else {
						checkin_x.focus();
					}
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
				case '5':  // 報到失敗
					// $( "#dialog-positive" ).dialog({
					//     resizable: false,
					//     height: "auto",
					//     width: 400,
					//     modal: true,
					//     buttons: {
					//         "  我 知 道 了 ": function() {
					//         $( this ).dialog( "close" );
					//         clear_focus();
					//         }
					//     }
					//     });
					//     $( "#dialog-positive" ).dialog( "open" );
					break;
				default:
					break;
			}
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
				$("#checkin_x").attr('disabled', true);
			} else {
				$("#checkin_x").attr('disabled', false);
			}
		};
		function button_save(status) {
			if (status === true) {
				$("#save").attr('disabled', false);
			} else {
				$("#save").attr('disabled', true);
			}
		};
		function handleDoubleClick() {
			window.location.href = "/appoint-back/belle/checkin_check.php";
			// window.location.reload();
		};
	</script>
	<style>
		// show table boder and padding = 5px
		table {
			border-collapse: collapse;
		}

		th,
		td {
			border: 0px solid black;
			padding: 5px;
			text-align: left;
		}
	</style>
	<script src="js/checkin_modified20230314.js"></script>
</head>

<body>

	<!-- <div id="dialog-confirm" title="是否再次報到？">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>已經有報到時間，是否要再次報到？</p>
	</div>
 -->

	<nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>

	<?php
	if (!empty($input_err)) {
		echo '<center><div class="alert alert-danger"><h3>' . $input_err . '</h3></div></center>';
	}
	?>

	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7.1.0/dist/promise.min.js"></script>
	<script>
		function showAlert() {
			Swal.fire({
				title: '<strong>請選擇付款方式</strong>',
				// icon: 'info',
				html: '',
				input: 'radio',
				inputOptions: {
					'2': '現金',
					'3': '刷卡',
					'4': '月結',
					'5': '匯款'
				},

				inputValidator: (payflag) => {
					if (!payflag) {
						return '請選擇付款方式'
					}
				},
				showCloseButton: true, // 預設顯示在右上角的關閉按鈕

				confirmButtonText: '確認報到', //　按鈕顯示文字
				confirmButtonColor: '#3085d6', // 修改按鈕色碼

				showCancelButton: true, // 取消按鈕

				// 自訂按鈕 class
				// customClass: {
				// 	confirmButton: 'btn btn-success',
				// 	cancelButton: 'btn btn-danger'
				// },
				// buttonsStyling: false, // 是否使用sweetalert按鈕樣式（預設為true）

			}).then((result) => {
				if (result.value) {
					// Do something with the selected option
					console.log(result.value);
					var uuid = document.getElementById('uuid').value;
					// Send the selected option to the PHP script
					$.ajax({
						type: 'POST',
						url: 'updatecheckin.php',

						data: { option: result.value, uuid: uuid },
						success: function (response) {

							// Handle the response from the PHP script
							const obj = JSON.parse(response);
							var PayFlagName = getPayFlagName(obj.payflag);
							document.getElementById('payflag').value = name;
							document.getElementById('tdat').value = obj.tdat;
						},


						error: function (error) {
							// Handle the AJAX error
							console.log(error);
						}
					});
				}
			});
		}

		function getPayFlagName(payflag) {
			switch (payflag) {
				case 'N':
					name = "未付款";
					break;
				case '2':
					name = "現金";
					break;
				case '3':
					name = "刷卡";
					break;
				case '4':
					name = "月結";
					break;
				case '5':
					name = "匯款";
					break;
				default:
					name = "未付款";
					break;
			}
			return name;
		}
	</script>

	<form id="form_checkin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="modifiedform"
		method="post" style="display:inline;">
		<input type="hidden" id="result_msg" name="result_msg" value="<?php echo $result_msg; ?>">
		<input type="hidden" id="uuid" name="uuid" value="<?php echo $uuid; ?>">

		<br>

		<table align="center" width="1300">
			<tr>
				<td colspan="2">
					<table>
						<tr>

							<td style="text-align: right">採檢編號 : </td>
							<td style="text-align: left;"><input type="text" id="pcrid_uuid" name="pcrid_uuid"
									class="form-control" value="<?php echo $pcrid_uuid; ?>">
							</td>
							<td> <input type="submit" id="search" name="search" class="btn btn-success"
									value=" 搜    尋 ">
							</td>
							<!-- <td> <input type="submit" id="checkin_x" name="checkin_x" class="btn btn-success"
									value=" 報    到 "></td> -->
							<td> <input type="button" id="checkin_x" name="checkin_x" class="btn btn-success"
									onclick="showAlert()" value=" 報    到 "></td>
							<td>
								<div class="form-check form-switch">
									<!-- <input class="form-check-input" type="checkbox" id="defaultCash" name="defaultCash">
									<label class="form-check-label" for="defaultCash" style="color:#003300">
										<span class="ms-1 d-none d-sm-inline">&ensp;現金收款</span>
									</label> -->
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<!-- <input class="form-check-input" type="checkbox" id="defaultCard" name="defaultCard">
									<label class="form-check-label" for="defaultCard" style="color:#003300">
										<span class="ms-1 d-none d-sm-inline">&ensp;刷卡收款</span>
									</label> -->
								</div>
							</td>

						</tr>

					</table>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:top; width: 20%;">
					<table id="jqGrid"></table>
					<div id="jqGridPager"></div>
				</td>
				<td style="vertical-align:top; width: 80%;">
					<table style="width:98% ; margin:15px " align="center">
						<tr>
							<td style="text-align: right;">PCR ID</td>
							<td style="text-align: left;"> <input type="text" id="sampleid2" name="sampleid2"
									class="form-control" value="<?php echo $sampleid2; ?>" readonly>
							</td>
							<td style="text-align: right;">身分證字號</td>
							<td style="text-align: left;"> <input onchange="IdCardNumberCheck(this.value)" type="text"
									id="userid" name="userid" class="form-control " value="<?php echo $userid; ?>"
									readonly>
							</td>
							<td style="text-align: right;">護照號碼</td>
							<td style="text-align: left;"> <input type="text" id="passportid" name="passportid"
									class="form-control " value="<?php echo $passportid; ?>" readonly></td>
						</tr>
						<tr>
							<td style="text-align: right;">快篩 ID</td>
							<td style="text-align: left;"> <input type="text" id="sampleid1" name="sampleid1"
									class="form-control " value="<?php echo $sampleid1; ?>" readonly></td>
							<td style="text-align: right;">中文姓名</td>
							<td style="text-align: left;"> <input onchange="CnameCheck(this.value)"
									pattern="[\u4E00-\u9FFF]+" type="text" id="cname" name="cname" class="form-control "
									value="<?php echo $cname; ?>" readonly>
							</td>
							<td style="text-align: right;">英文姓名</td>
							<td style="text-align: left;"> <input onchange="EnameCheck(this.value)" type="text"
									pattern="[a-zA-Z ,-]+" id="fname" name="fname" class="form-control "
									value="<?php echo $fname; ?>" readonly></td>
						<tr>
							<td style="text-align: right;">預約日期</td>
							<td style="text-align: left;"> <input type="date" id="apdat" class="form-control"
									name="apdat" placeholder="1990-01-01" value="<?php echo $apdat; ?>" readonly></td>
							<td style="text-align: right;">報到日期</td>
							<td style="text-align: left;"> <input type="text" id="tdat" name="tdat" class="form-control"
									value="<?php echo $tdat; ?>" readonly></td>
							<td style="text-align: right;">報告時效</td>
							<td style="text-align: left;"><input type="text" id="twrpturgency" name="twrpturgency"
									class="form-control " value="<?php echo $twrpturgency; ?>" readonly></td>

						</tr>
			</tr>
			<tr>
				<!-- <td style="text-align: right;">生日</td>
							<td style="text-align: left;"><input required type="date" id="dob" name="dob"
									class="form-control " readonly value="<?php echo $dob; ?>"></td>
							<td style="text-align: right;">性別</td>
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
							</td> -->
				<td style="text-align: right;">付款方式</td>
				<td style="text-align: left;"><input type="text" id="payflag" name="payflag" class="form-control "
						value="<?php echo $payflag_name; ?>" readonly></td>
				<td style="text-align: right;">國籍</td>
				<td style="text-align: left;"><input type="text" id="nationality" name="nationality"
						class="form-control " value="<?php echo $nationality; ?>" readonly></td>
				<!-- <td style="text-align: right;">單位</td>
							<td style="text-align: left;"><input type="text" id="unit" name="unit" class="form-control "
									value="<?php echo $unit; ?>" readonly></td> -->
				<td style="text-align: right;">台胞證號</td>
				<td style="text-align: left;"> <input type="text" id="mtpid" name="mtpid" class="form-control "
						value="<?php echo $mtpid; ?>" readonly> </td>
						
			</tr>
			<tr>
				<!-- <td style="text-align: right;">健保卡號 : </td>
							<td style="text-align: left;"> <input type="text" id="hicardno" name="hicardno"
									class="form-control " value="<?php echo $hicardno; ?>" readonly></td> -->
				<td style="text-align: right;">手機號碼</td>
				<td style="text-align: left;"> <input required onchange="MobileCheck(this.value)" pattern='^[0-9]+$'
						type="text" id="mobile" name="mobile" class="form-control" placeholder="0911111111"
						value="<?php echo $mobile; ?>" readonly></td>
				<td style="text-align: right;">E-mail</td>
				<td style="text-align: left;"><input type="email" id="email" name="email" class="form-control "
						value="<?php echo $email; ?>" readonly></td>
				<td style="text-align: right;">收據號碼</td>
				<td style="text-align: left;"><input type="text" id="receiptid" name="receiptid" class="form-control "
						value="<?php echo $receiptid; ?>" readonly></td>
			</tr>
			<tr>
				<!-- <td style="text-align: right;">檢測類型</td>
							<td style="text-align: left;">
								<select id="testtype" name="testtype" class="form-select" disabled>
									<?php foreach ($testtype_opt as $value => $label): ?>
										<option value="<?php echo $value; ?>" <?php if ($testtype == $value) {
											   echo ' selected="selected"';
										   } ?>><?php echo $label; ?></option>
									<?php endforeach ?>
								</select>
							</td> -->
				<td style="text-align: right;">統編抬頭</td>
				<td style="text-align: left;"><input type="text" id="companytitle" name="companytitle"
						class="form-control " value="<?php echo $companytitle; ?>" readonly>
					<div id="PointMsgSendname"></div>
				</td>
				<td style="text-align: right;">統編號碼</td>
				<td style="text-align: left;"> <input onchange="SendnameCheck(this.value)" pattern='[0-9]+' type="text"
						id="sendname" name="sendname" class="form-control " value="<?php echo $sendname; ?>" readonly>
					<div id="PointMsgSendname"></div>
				</td>
				<td style="text-align: right;">備註</td>
				<td style="text-align: left;"> <input type="text" id="memo" name="memo" class="form-control "
						value="<?php echo $memo; ?>" readonly>
				</td>
			</tr>
		</table>
		</table>
	</form>
</body>
<script type="text/javascript">

	$(document).ready(function () {
		var Height = Math.floor(($(window).height()) - 320);
		$("#jqGrid").jqGrid({
			url: 'GetTodayMemberList.php',
			datatype: 'json',
			mtype: "GET",
			colModel: [
				{ label: '採檢編號', name: 'sampleid2', width: '110px' },
				{ label: '預約日期', name: 'apdat', hidden: true },
				{ label: '報到日期', name: 'tdat', width: '110px' },
			],
			loadonce: true,
			autowidth: true,
			height: Height,
			rowNum: 1000,
		});
	});
</script>


</html>