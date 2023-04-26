<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
// 2109011710 因更新表單，修改讀取欄位 modified by YH
// 2202161040 因更新表單，修改讀取欄位 modified by olive


ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // header("location: welcome.php");
    // header("location: menu.php");
    //    exit;
} else {
    header("location: login.php");
}

require_once "php/log.php";
// Connect to local db
$conn = mysqli_connect("localhost", "libo_user", "xxx");
mysqli_select_db($conn, "libodb");



// upload files
if (isset($_POST["submit_file"])) {
	// check csv format
	$getDate = date("Y-m-d");
	$target_dir = "test/php/log/";
	$target_file = $target_dir . $getDate.basename($_FILES["file"]["tmp_name"]);
	move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
	$file = $target_dir.$getDate.basename($_FILES["file"]["tmp_name"]);

	// $file = $_FILES["file"]["tmp_name"];

	$file_open = fopen($file, "r");
	$myline = 0;

	$count = 0;
	while (($csv = fgetcsv($file_open, 6000, ",")) !== false) {

		//approval 同意攜帶證件
		if (trim($csv[40]) == "瞭解，並願意提供相關文件") {
			$approval = 'Y';
		} else {
			$approval = 'N';
			continue;
		}
		$myline = $myline + 1;
		if ($myline >= 1) {

			$uuid = $csv[0];
			//echo $myline."<br>";
			//echo $csv[0]." ".$csv[1]." ".$csv[2]." ".$csv[3]." ".$csv[4]." ".$csv[5]."<br>";
			//echo "<br>";
			//$userid = trim($csv[5]);
			//$passportid = trim($csv[25]);

			//residentpermit 是否有居留證
			if (str_replace(" ", "", $csv[18]) == '是/YES') {
				//if (str_replace(" ", "", $csv[15]) == '是/YES') {  //olive
					$residentpermit = "Y";
				} elseif (str_replace(" ", "", $csv[18]) == '否/NO') {
				//} elseif (str_replace(" ", "", $csv[15]) == '否/NO') { //olive
					$residentpermit = "N";
				} else {
				 	$residentpermit = "";
			    }

			//userid 身分證
			if (!empty(trim($csv[16]))) {
                        //if (!empty(trim($csv[13]))) { //olive
				$userid = strtoupper(str_replace(" ", "", $csv[16]));
                //$userid = strtoupper(str_replace(" ", "", $csv[13]));//olive
			} else {
				if($residentpermit == "Y") {
					$userid = str_replace("-", "", str_replace(" ", "", $csv[19]));
					//$hicardno = str_replace("-", "", str_replace(" ", "", $csv[16]));//olive
				}
				else{
				$userid = "";
				}	
			}
		
			
			
			//passportid 護照
			if (!empty(trim($csv[25]))) {
                        //if (!empty(trim($csv[22]))) { //olive
				$passportid = strtoupper(str_replace(" ", "", $csv[25]));
                                //$passportid = strtoupper(str_replace(" ", "", $csv[22]));//olive
			} else {
				$passportid = "";
			}

            //mtpid 台胞證
			if (!empty(trim($csv[11]))) {
                        	$mtpid = strtoupper(str_replace(" ", "", $csv[11]));
                        } else {
				$mtpid = "";
			}
			
			//日本報告國籍欄位
			if (!empty(trim($csv[13]))) {
				$nationality = strtoupper(str_replace(" ", "", $csv[13]));
			} else {
				$nationality = "";
			}

			//apdat 預約日期
			$dd = explode("(", $csv[31]);
            //$dd = explode("(", $csv[9]); //olive
			//echo $dd[0]; echo '<br>';
			$apdat =  date('Y-m-d', strtotime($dd[0]));
			//$sql = "SELECT uuid FROM covid_trans WHERE uuid = ?";
			if (trim($userid) != "") {
				$sql = "SELECT uuid,userid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
																				and    userid = ? 
																				and    apdat=?";
			} elseif (trim($passportid) != "") {
				$sql = "SELECT uuid,passportid,sampleid1,sampleid2 FROM covid_trans WHERE 1=1
																				and    passportid = ? 
																				and    apdat=?";
			}
			//echo $sql;   
			if ($stmt = mysqli_prepare($conn, $sql)) {
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ss", $p2, $p3);

				// Set parameters
				//$p1 = $uuid;
				//$p1 = $userid;
				// 061121 修正可能passport 沒填，檢查時有回傳的bug
				if (trim($userid) != "") {
					$p2 = $userid;
				} elseif (trim($passportid) != "") {
					$p2 = $passportid;
				}
				$p3 = $apdat;

				// Attempt to execute the prepared statement
				if (mysqli_stmt_execute($stmt)) {
					// Store result
					mysqli_stmt_store_result($stmt);

					// Check if username exists, if yes then verify password
					if (mysqli_stmt_num_rows($stmt) == 0) {
						// Bind result variables
						//$uuid = $csv[0];
						// split date from csv

						//sfdat, efdat紀錄系統時間
						$dd = explode(" ", $csv[1]);
						//echo $dd[0]; echo '<br>';
						$sfdat =  date('Y-m-d', strtotime($dd[0]));
						//echo $sfdat; echo '<br>';
						$dd = explode(" ", $csv[2]);
						//echo $dd[0]; echo '<br>';
						$efdat =  date('Y-m-d', strtotime($dd[0]));
						// $efdat = strtotime($csv[2]);

						//$userid =$csv[5];
						// sex 性別
						$sex = $csv[27];
                                                //sex = $csv[24]; //olive

						//cname 中文名
						$cname = trim($csv[24]);
                                                //$cname = trim($csv[21]); //olive

						//fname first name , lname last name 英文名(新版合在一起，統一寫在fname)
						$fname = trim($csv[26]);
                                                //$fname = trim($csv[23]);//olive
						//$lname = trim($csv[9]);
						$lname = "";

						//dob 生日
						$dd = explode(" ", $csv[28]);
                                                //$dd = explode(" ", $cdv[25]);//olive
						//echo $dd[0]; echo '<br>';
						$dob =  date('Y-m-d', strtotime($dd[0]));
						//$dob = strtotime($csv[10]);

						// mobile 手機
						$mobile = str_replace(" ", "", $csv[29]);
                                                //$mobile = str_replace(" ", "", $csv[26]);//olive

						//uemail 顧客信箱
						$uemail = str_replace(" ", "", $csv[31]);
                                                //$uemail = str_replace(" ", "", $csv[28]);//olive

						//modified by YH 20210721
						//$dd=explode(" ",$csv[13]);
						//echo $dd[0]; echo '<br>';
						//$apdat =  date('Y-m-d', strtotime($dd[0]));
						//$apdat = strtotime($csv[13]);

						//ftest 快篩結果
						$ftest = "";

						//pcrtest PCR結果
						$pcrtest = "";

						//smsflag 簡訊flag
						$smsflag = "N";

						//email flag
						$emailflag = "N";


						//$type ="1"; type 報名方式 1:個人 or 2:團體
						if (trim($csv[5]) == '個人自費報名 / Individual Registration') {
                                                //if (trim($csv[6]) == '個人自費報名 / Individual Registration') { //olive
							$type = 1;
							$dd = explode("(", $csv[9]);
                                                        //$dd = explode("(", $csv[9]);//olive
							//echo $dd[0]; echo '<br>';
							$apdat =  date('Y-m-d', strtotime($dd[0]));
							$year = explode("-", $apdat)[0];
							$month = explode("-", $apdat)[1];
							$day = explode("-", $apdat)[2];
							//if (trim(explode(")", $csv[31])[1]) == "上午")
							//{
							//		$no = '01';
							//}
							//elseif (trim(explode(")", $csv[31])[1]) == "下午")
							//{
							//		$no = '02';
							//}
							//else
							//{
							//		$no = '03';
							//}
							//強制上午場 no = 01
							$no = "01";
							$sendname = $year[2] . $year[3] . $month . $day . $no;
						} else {
							$type = 2;
							$sendname = str_replace(" ", "", $csv[6]);
                                                        //$sendname = str_replace(" ", "", $csv[11]); //olive
						}
						//address1 廢欄位 
						$address1 = "";

						// telephone 市話
						$telephone = str_replace(" ", "", $csv[30]);
                                                //$telephone = str_replace(" ", "", $csv[27]); //olive

						//address2 地址
						$address2 = trim($csv[33]);
                                                //$address2 = trim($csv[30]); //olive

						//testtype 檢測類型
						if (str_replace(" ", "", $csv[8]) == '抗原快篩/Ag_TC') {
                                                //if (str_replace(" ", "", $csv[8]) == '抗原快篩 / Ag_TC ;') { //olive
							$testtype = 1;
						} elseif (str_replace(" ", "", $csv[8]) == '核酸檢測/PCR;') {
                                                //} elseif (str_replace(" ", "", $csv[8]) == '核酸檢測 / PCR;') { //olive
							$testtype = 2;
						} else {
							$testtype = 3;
						}

						//ctzn 台灣居民
						if (str_replace(" ", "", $csv[12]) == '是/Yes') {
                                                //if (str_replace(" ", "", $csv[12]) == '是/Yes') { //olive
							$ctzn = 1;
						} else {
							$ctzn = 2;
						}
						//$ctzn = $csv[24];
						//$passportid = $csv[25];

						//快篩編號
						$sampleid1 = "";
						//是否上傳cdc 此欄位目前沒用到
						$cdcflag = "N";

						//testreason 自費篩檢原因
						if (trim($csv[7]) == '因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附(1)申請表；(2)申請原因相關文件， 如電子機票、購票證明或訂票紀錄等') {
						//if (trim($csv[7]) == '因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附(1)申請表；(2)申請原因相關文件， 如電子機票、購票證明或訂票紀錄等') {
							$testreason = '1';
						} elseif (trim($csv[7]) == '因工作因素須檢附檢驗證明之民眾。需檢附(1)申請表；(2)工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '因工作因素須檢附檢驗證明之民眾。需檢附(1)申請表；(2)工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等') {
							$testreason = '2';
						} elseif (trim($csv[7]) == '短期商務人士。需檢附(1)申請表；(2)申請原因相關文件（如： 在臺行程表或防疫計畫書等') {
						//} elseif (trim($csv[7]) == '短期商務人士。需檢附(1)申請表；(2)申請原因相關文件（如： 在臺行程表或防疫計畫書等') {
							$testreason = '3';
						} elseif (trim($csv[7]) == '出國求學須檢附檢驗證明之民眾。需檢附(1)申請表；(2)就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '出國求學須檢附檢驗證明之民眾。需檢附(1)申請表；(2)就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等') {
							$testreason = '4';
						} elseif (trim($csv[7]) == '外國或中國大陸、香港、澳門人士出境。需檢附(1)申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '外國或中國大陸、香港、澳門人士出境。需檢附(1)申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等') {
							$testreason = '5';
						} elseif (trim($csv[7]) == '相關出境適用對象之眷屬。需檢附(1)申請表；(2)身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等') {
						//} elseif (trim($csv[7]) == '相關出境適用對象之眷屬。需檢附(1)申請表；(2)身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等') {
							$testreason = '6';
						} else {
							$testreason = '7';
						}

						//mailrpt 郵寄報告 1:郵寄 2:現場領 3:email
						if (trim($csv[32]) == '需要，請幫我郵寄') {
						//if (trim($csv[29]) == '需要，請幫我郵寄') { //olive
							$mailrpt = "1";
						} elseif (trim($csv[32]) == '我會親自到場領取紙本報告') {
						//} elseif (trim($csv[29]) == '不需要，我會親自到場領取紙本報告') { //olive
							$mailrpt = "2";
						} else {
							$mailrpt = "3";
						}

						//residentpermit 是否有居留證
						if (str_replace(" ", "", $csv[18]) == '是/YES') {
							//if (str_replace(" ", "", $csv[15]) == '是/YES') {  //olive
								$residentpermit = "Y";
							} elseif (str_replace(" ", "", $csv[18]) == '否/NO') {
							//} elseif (str_replace(" ", "", $csv[15]) == '否/NO') { //olive
								$residentpermit = "N";
							} else {
								$residentpermit = "";
							}

						//hicardno 健保卡號
						$hicardno = "";
						if ($ctzn == 1) {
							$hicardno = str_replace("-", "", str_replace(" ", "", $csv[17]));
                            //$hicardno = str_replace("-", "", str_replace(" ", "", $csv[14]));//olive
						}
						// if ($residentpermit == "Y") {
						// 	$hicardno = str_replace("-", "", str_replace(" ", "", $csv[19]));
                        //                                 //$hicardno = str_replace("-", "", str_replace(" ", "", $csv[16]));//olive
						// }

						//hiflag 台灣健保
						if (!empty($hicardno)) {
							$hiflag = 'Y';
						} else {
							$hiflag = 'N';
						}

						//nihrpt 陰性通報健保署
						if ($csv[20] == '同意 / YES') {
						//if ($csv[17] == '同意 / YES') {  //olive
							$nihrpt = "Y";
						} else {
							$nihrpt = "N";
						}

						//mobilerpt 手機上傳健保署
						if ($csv[21] == '同意 / YES') {
						//if ($csv[18] == '同意 / YES') { //olive
							$mobilerpt = "Y";
						} else {
							$mobilerpt = "N";
						}

						//健康存摺和雲端資料合併欄位，同意年限亦同
						//健康存摺利用
						if ($csv[22] == '同意 / YES') {
						//if ($csv[19] == '同意 / YES') { //olive
							$hbrpt = $cloudrpt = "Y";
							$hbrptyear = $cloudrptyear = $csv[23];
                                                        //$hbrptyear = $cloudrptyear = $csv[20];//olive
						} else {
							$hbrpt = $cloudrpt = "N";
							$hbrptyear = $cloudrptyear = "0";
						}


						//健康存摺利用年限
						//$hbrptyear = $csv[40];

						//雲端資料利用
						//if ($csv[39]=='同意 / YES')
						//{
						//	$cloudrpt = "Y";
						//}
						//else 
						//{
						//	$cloudrpt = "N";
						//}

						//雲端資料利用年限
						//$cloudrptyear = trim($csv[45]);


						//vuser1 上傳結果的醫檢師 vuser2覆核結果的醫檢師
						$vuser1 = "";
						$vuser2 = "";

						//sampleid2 PCR編號
						$sampleid2 = "";

						//payflag 是否付款，廢欄位
						$payflag = "N";


						//fuser1 廢欄位
						//$fuser1 = "";
						////tuser1, tuser2 廢欄位
						//$tuser1 ="";
						//$tuser2 ="";

						// fuser1 -> xmappoint 廈門預約 ,tuser1 -> xmapdat 廈門預約日期, tuser2 -> mtpid 台胞證號碼
						//cmobile 大陸手機, xmemail 廈門email
						if (str_replace(" ", "", $csv[34]) == "是/Yes") {
						//if (str_replace(" ", "", $csv[31]) == "是/Yes") { //olive
							$xmappoint = "Y";
							//預約日
							$dd = explode(" ", $csv[35]);
							//$dd = explode(" ", $csv[32]); //olive
							$xmapdat = date('Y-m-d', strtotime($dd[0]));
							$mtpid = str_replace(" ", "", $csv[37]);
                                                        //$mtpid = str_replace(" ", "", $csv[34]); //olive
							$cmobile = str_replace(" ", "", $csv[38]);
							//$cmobile = str_replace(" ", "", $csv[35]); //olive
							if (str_replace(" ", "", $csv[39]) == '') {
							//if (str_replace(" ", "", $csv[36]) == '') { //olive
								$xmemail = $uemail;
							} else {
								$xmemail = str_replace(" ", "", $csv[39]);
                                                                //$xmemail = str_replace(" ", "", $csv[36]); //olive
							}
						} else {
							$xmappoint = "N";
							$xmapdat = $cmobile = $xmemail = '';
						}

						//mysql_query       
						//060921 WillieK add 4 columns, tdat= trans date,
						//                      aphh= appoint hours,
						//                      cfhh= assign hours,
						//                      rdat = report result date      
						//$tdat=0;
						//aphh cfhh 廢欄位 aphh -> twrpturgency cfhh -> xmrpturgency
						//$aphh="";
						//$cfhh="";
						//$rdat=0;

						//twrpturgency 在台檢測報告急迫性
						$cheinese_pattern = "/\p{Han}+/u";
						preg_match($cheinese_pattern, $csv[14], $matches);
                                                //preg_match($cheinese_pattern, $csv[10], $matches); //olive

						if (!empty($matches)) {
							if ($matches[0] == "急件特別診") {
								$twrpturgency = "hiurgent";
								// echo $matches[0]."is 特急件 <br>".$twrpturgency;
                                                         }  elseif ($matches[0] == "急件") {
								$twrpturgency = "urgent";
								// echo $matches[0]."is 急 <br>".$twrpturgency;
							} elseif ($matches[0] == "一般件") {
								$twrpturgency = "normal";
								// echo $matches[0]."is 一般件 <br>".$twrpturgency;
						        }
						} else {
							$twrpturgency = "";
							// print_r($matches);
							// echo "<br>".$twrpturgency;
						}


						//xmrpturgency 廈門檢測報告急迫性
						$cheinese_pattern = "/\p{Han}+/u";
						preg_match($cheinese_pattern, $csv[36], $matches);
                                                ///preg_match($cheinese_pattern, $csv[33], $matches); //olive

						if (!empty($matches)) {
							if ($matches[0] == "急") {
								$xmrpturgency = "urgent";
								// echo $matches[0]."is 急 <br>".$twrpturgency;
							} elseif ($matches[0] == "一般件") {
								$xmrpturgency = "normal";
								// echo $matches[0]."is 一般件 <br>".$twrpturgency;
							}
						} else {
							$xmrpturgency = "";
							// print_r($matches);
							// echo "<br>".$twrpturgency;
						}

						$fpdfflag = $pcrpdfflag = $xlspcrtest2 = '';

						$sql2 = "INSERT INTO covid_trans 
								VALUES (0,'$sfdat','$efdat','$userid','$sex','$cname',
                                   '$fname','$lname','$dob','$mobile','$uemail','$apdat',
                                   '$ftest','$pcrtest','$smsflag','$emailflag','$type',
                                   '$telephone','$address2','$testtype','$ctzn','$passportid',
                                   '$sampleid1','$cdcflag','$sampleid2','$residentpermit','$xmapdat','$mtpid',
                                   '$vuser1','$vuser2','$payflag','$xmappoint',null,'$twrpturgency','$xmrpturgency',null,'$sendname',null,null,
								   '$hicardno','$testreason','$mobilerpt','$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt', 
								   '$cmobile','$xmemail','$approval', '$address1', '$hiflag', '$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2')";
						// mysqli_stmt_close($stmt);
						//echo $sql2;
						if (mysqli_query($conn, $sql2)) {
							//echo "New record created successfully";
							$count = $count + 1;
							$sql_comment = $_SESSION["username"] . ": " . $sql2;
                            write_sql($sql_comment);
						} else {
							echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
							$sql_comment = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
							write_sql($sql_comment);
						}
					}
					// sql row count >0, if the data did not been used. The row can delete.
					else {
						mysqli_stmt_bind_result($stmt, $uuid, $userid, $sampleid1, $sampleid2);
						//echo $uuid, $userid,$sampleid1,$sampleid2."<br>";
						while (mysqli_stmt_fetch($stmt)) {
							if (empty(trim($sampleid1)) && empty(trim($sampleid2))) {
								$sql3 = "delete from covid_trans where uuid=?";
								//echo $sql3."<br>";
								if ($stmt3 = mysqli_prepare($conn, $sql3)) {
									mysqli_stmt_bind_param($stmt3, "i", $p10);
									$p10 = $uuid;
									mysqli_stmt_execute($stmt3);
									$sql_comment = $_SESSION["username"] . ": " . $sql3;
                                    write_sql($sql_comment);
								}
								mysqli_stmt_close($stmt3);
							}
						}
						//reinsert

						//sfdat, efdat紀錄系統時間
						$dd = explode(" ", $csv[1]);
						//echo $dd[0]; echo '<br>';
						$sfdat =  date('Y-m-d', strtotime($dd[0]));
						//echo $sfdat; echo '<br>';
						$dd = explode(" ", $csv[2]);
						//echo $dd[0]; echo '<br>';
						$efdat =  date('Y-m-d', strtotime($dd[0]));
						// $efdat = strtotime($csv[2]);

						//$userid =$csv[5];
						// sex 性別
						$sex = $csv[27];
                                                //$sex = $csv[24]; //olive

						//cname 中文名
						$cname = trim($csv[24]);
                                                //$cname = trim($csv[21]); //olive

						//fname first name , lname last name 英文名(新版合在一起，統一寫在fname)
						$fname = trim($csv[26]);
						//$fname = trim($csv[23]); //olive
						//$lname = trim($csv[9]);
						$lname = "";

						//dob 生日
						$dd = explode(" ", $csv[28]);
						//$dd = explode(" ", $csv[25]);
						//echo $dd[0]; echo '<br>';
						$dob =  date('Y-m-d', strtotime($dd[0]));
						//$dob = strtotime($csv[10]);

						// mobile 手機
						$mobile = str_replace(" ", "", $csv[29]);
                                                //$mobile = str_replace(" ", "", $csv[26]);

						//uemail 顧客信箱
						$uemail = str_replace(" ", "", $csv[31]);
                                                //$uemail = str_replace(" ", "", $csv[28]);

						//modified by YH 20210721
						//$dd=explode(" ",$csv[13]);
						//echo $dd[0]; echo '<br>';
						//$apdat =  date('Y-m-d', strtotime($dd[0]));
						//$apdat = strtotime($csv[13]);

						//ftest 快篩結果
						$ftest = "";

						//pcrtest PCR結果
						$pcrtest = "";

						//smsflag 簡訊flag
						$smsflag = "N";

						//email flag
						$emailflag = "N";


						//$type ="1"; type 報名方式 1:個人 or 2:團體
						if (trim($csv[5]) == '個人自費報名 / Individual Registration') {
						//if (trim($csv[6]) == '個人自費報名 / Individual Registration') { //olive
							$type = 1;
							$dd = explode("(", $csv[12]);
							//$dd = explode("(", $csv[9]); //olive
							//echo $dd[0]; echo '<br>';
							$apdat =  date('Y-m-d', strtotime($dd[0]));
							$year = explode("-", $apdat)[0];
							$month = explode("-", $apdat)[1];
							$day = explode("-", $apdat)[2];
							//if (trim(explode(")", $csv[31])[1]) == "上午")
							//{
							//		$no = '01';
							//}
							//elseif (trim(explode(")", $csv[31])[1]) == "下午")
							//{
							//		$no = '02';
							//}
							//else
							//{
							//		$no = '03';
							//}
							//強制上午場 no = 01
							$no = "01";
							$sendname = $year[2] . $year[3] . $month . $day . $no;
						} else {
							$type = 2;
							$sendname = str_replace(" ", "", $csv[6]);
                                                        //$sendname = str_replace(" ", "", $csv[11]); //olive

						}
						//address1 廢欄位 
						$address1 = "";

						// telephone 市話
						$telephone = str_replace(" ", "", $csv[30]);
                                                //$telephone = str_replace(" ", "", $csv[27]); //olive

						//address2 地址
						$address2 = trim($csv[33]);
                                                //$address2 = trim($csv[30]);

						//testtype 檢測類型
						if (str_replace(" ", "", $csv[8]) == '抗原快篩/Ag_TC') {
						//if (str_replace(" ", "", $csv[8]) == '抗原快篩/Ag_TC') { //olive
							$testtype = 1;
						} elseif (str_replace(" ", "", $csv[8]) == '核酸檢測/PCR;') {
						//} elseif (str_replace(" ", "", $csv[8]) == '核酸檢測/PCR;') {
							$testtype = 2;
						} else {
							$testtype = 3;
						}

						//ctzn 台灣居民
						if (str_replace(" ", "", $csv[15]) == '是/Yes') {
						//if (str_replace(" ", "", $csv[12]) == '是/Yes') {
							$ctzn = 1;
						} else {
							$ctzn = 2;
						}
						//$ctzn = $csv[24];
						//$passportid = $csv[25];

						//快篩編號
						$sampleid1 = "";
						//是否上傳cdc 此欄位目前沒用到
						$cdcflag = "N";

						//testreason 自費篩檢原因
						if (trim($csv[7]) == '因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附(1)申請表；(2)申請原因相關文件， 如電子機票、購票證明或訂票紀錄等') {
						//if (trim($csv[7]) == '因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附(1)申請表；(2)申請原因相關文件， 如電子機票、購票證明或訂票紀錄等') {
							$testreason = '1';
						} elseif (trim($csv[7]) == '因工作因素須檢附檢驗證明之民眾。需檢附(1)申請表；(2)工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '因工作因素須檢附檢驗證明之民眾。需檢附(1)申請表；(2)工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等') {
							$testreason = '2';
						} elseif (trim($csv[7]) == '短期商務人士。需檢附(1)申請表；(2)申請原因相關文件（如： 在臺行程表或防疫計畫書等') {
						//} elseif (trim($csv[7]) == '短期商務人士。需檢附(1)申請表；(2)申請原因相關文件（如： 在臺行程表或防疫計畫書等') {
							$testreason = '3';
						} elseif (trim($csv[7]) == '出國求學須檢附檢驗證明之民眾。需檢附(1)申請表；(2)就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '出國求學須檢附檢驗證明之民眾。需檢附(1)申請表；(2)就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等') {
							$testreason = '4';
						} elseif (trim($csv[7]) == '外國或中國大陸、香港、澳門人士出境。需檢附(1)申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等') {
						//} elseif (trim($csv[7]) == '外國或中國大陸、香港、澳門人士出境。需檢附(1)申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等') {
							$testreason = '5';
						} elseif (trim($csv[7]) == '相關出境適用對象之眷屬。需檢附(1)申請表；(2)身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等') {
						//} elseif (trim($csv[7]) == '相關出境適用對象之眷屬。需檢附(1)申請表；(2)身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等') {
							$testreason = '6';
						} else {
							$testreason = '7';
						}

						//mailrpt 郵寄報告 1:郵寄 2:現場領 3:email
						if (trim($csv[32]) == '需要，請幫我郵寄') {
						//if (trim($csv[29]) == '需要，請幫我郵寄') { //olive
							$mailrpt = "1";
						} elseif (trim($csv[32]) == '不需要，我會親自到場領取紙本報告') {
						//} elseif (trim($csv[29]) == '不需要，我會親自到場領取紙本報告') { //olive
							$mailrpt = "2";
						} else {
							$mailrpt = "3";
						}

						//residentpermit 是否有居留證
						if ($csv[18] == '是 / YES') {
						//if ($csv[15] == '是 / YES') {
							$residentpermit = "Y";
						} elseif ($csv[18] == '否 / NO') {
						//} elseif ($csv[15] == '否 / NO') {
							$residentpermit = "N";
						} else {
							$residentpermit = "";
						}

						//hicardno 健保卡號 or 居留證號
						if ($ctzn == 1) {
							$hicardno = str_replace("-", "", str_replace(" ", "", $csv[17]));
                                                        //$hicardno = str_replace("-", "", str_replace(" ", "", $csv[14])); //olive
						}
						// if ($residentpermit = "Y") {
						// 	$hicardno = str_replace("-", "", str_replace(" ", "", $csv[19]));
                        //                                 //$hicardno = str_replace("-", "", str_replace(" ", "", $csv[16])); //olive
						// }
						//hiflag 台灣健保
						if (!empty($hicardno)) {
							$hiflag = 'Y';
						} else {
							$hiflag = 'N';
						}

						//nihrpt 陰性通報健保署
						if ($csv[20] == '同意 / YES') {
						//if ($csv[17] == '同意 / YES') { //olive
							$nihrpt = "Y";
						} else {
							$nihrpt = "N";
						}

						//mobilerpt 手機上傳健保署
						if ($csv[21] == '同意 / YES') {
						//if ($csv[18] == '同意 / YES') { //olive
							$mobilerpt = "Y";
						} else {
							$mobilerpt = "N";
						}

						//健康存摺和雲端資料合併欄位，同意年限亦同
						//健康存摺利用
						if ($csv[22] == '同意 / YES') {
						//if ($csv[19] == '同意 / YES') { //olive
							$hbrpt = $cloudrpt = "Y";
							$hbrptyear = $cloudrptyear = $csv[23];
                                                        //$hbrptyear = $cloudrptyear = $csv[20]; //olive
						} else {
							$hbrpt = $cloudrpt = "N";
							$hbrptyear = $cloudrptyear = "0";
						}


						//健康存摺利用年限
						//$hbrptyear = $csv[40];

						//雲端資料利用
						//if ($csv[39]=='同意 / YES')
						//{
						//	$cloudrpt = "Y";
						//}
						//else 
						//{
						//	$cloudrpt = "N";
						//}

						//雲端資料利用年限
						//$cloudrptyear = trim($csv[45]);


						//vuser1 上傳結果的醫檢師 vuser2覆核結果的醫檢師
						$vuser1 = "";
						$vuser2 = "";

						//sampleid2 PCR編號
						$sampleid2 = "";

						//payflag 是否付款，廢欄位
						$payflag = "N";


						//fuser1 廢欄位
						//$fuser1 = "";
						////tuser1, tuser2 廢欄位
						//$tuser1 ="";
						//$tuser2 ="";

						// fuser1 -> xmappoint 廈門預約 ,tuser1 -> xmapdat 廈門預約日期, tuser2 -> mtpid 台胞證號碼
						//cmobile 大陸手機, xmemail 廈門email
						if (str_replace(" ", "", $csv[34]) == "是/Yes") {
						//if (str_replace(" ", "", $csv[31]) == "是/Yes") { //olive
							$xmappoint = "Y";
							//預約日
							$dd = explode(" ", $csv[35]);
							//$dd = explode(" ", $csv[32]);//olive
							$xmapdat = date('Y-m-d', strtotime($dd[0]));
							$mtpid = str_replace(" ", "", $csv[37]);
							//$mtpid = str_replace(" ", "", $csv[34]);//olive
							$cmobile = str_replace(" ", "", $csv[38]);
							//$cmobile = str_replace(" ", "", $csv[35]);//olive
							if (str_replace(" ", "", $csv[39]) == '') {
							//if (str_replace(" ", "", $csv[36]) == '') { //olive
								$xmemail = $uemail;
							} else {
								$xmemail = str_replace(" ", "", $csv[39]);
                                                                //$xmemail = str_replace(" ", "", $csv[36]); olive
							}
						} else {
							$xmappoint = "N";
							$xmapdat = $cmobile = $xmemail = '';
						}

						//mysql_query       
						//060921 WillieK add 4 columns, tdat= trans date,
						//                      aphh= appoint hours,
						//                      cfhh= assign hours,
						//                      rdat = report result date      
						//$tdat=0;
						//aphh cfhh 廢欄位 aphh -> twrpturgency cfhh -> xmrpturgency
						//$aphh="";
						//$cfhh="";
						//$rdat=0;

						//twrpturgency 在台檢測報告急迫性
						$cheinese_pattern = "/\p{Han}+/u";
						preg_match($cheinese_pattern, $csv[14], $matches);
                                                //preg_match($cheinese_pattern, $csv[10], $matches); //olive

						if (!empty($matches)) {
                                                       if ($matches[0] == "急件特別診") {
								$twrpturgency = "hiurgent";
								// echo $matches[0]."is 特急件 <br>".$twrpturgency;
                                                       }  elseif ($matches[0] == "急件") {
								$twrpturgency = "urgent";
								// echo $matches[0]."is 急 <br>".$twrpturgency;
							} elseif ($matches[0] == "一般件") {
								$twrpturgency = "normal";
								// echo $matches[0]."is 一般件 <br>".$twrpturgency;
						        }
                                                 } else {
							$twrpturgency = "";
							// print_r($matches);
							// echo "<br>".$twrpturgency;
						}

						//xmrpturgency 廈門檢測報告急迫性
						$cheinese_pattern = "/\p{Han}+/u";
						preg_match($cheinese_pattern, $csv[36], $matches);
                                                //preg_match($cheinese_pattern, $csv[33], $matches);//olive

						if (!empty($matches)) {
							if ($matches[0] == "急") {
								$xmrpturgency = "urgent";
								// echo $matches[0]."is 急 <br>".$twrpturgency;
							} elseif ($matches[0] == "一般件") {
								$xmrpturgency = "normal";
								// echo $matches[0]."is 一般件 <br>".$twrpturgency;
							}
						} else {
							$xmrpturgency = "";
							// print_r($matches);
							// echo "<br>".$twrpturgency;
						}

						$fpdfflag = $pcrpdfflag = $nationality = $xlspcrtest2 = "";

						$sql2 = "INSERT INTO covid_trans 
								VALUES (0,'$sfdat','$efdat','$userid','$sex','$cname',
                                   '$fname','$lname','$dob','$mobile','$uemail','$apdat',
                                   '$ftest','$pcrtest','$smsflag','$emailflag','$type',
                                   '$telephone','$address2','$testtype','$ctzn','$passportid',
                                   '$sampleid1','$cdcflag','$sampleid2','$residentpermit','$xmapdat','$mtpid',
                                   '$vuser1','$vuser2','$payflag','$xmappoint',null,'$twrpturgency','$xmrpturgency',null,'$sendname',null,null,
								   '$hicardno','$testreason','$mobilerpt','$mailrpt','$hbrpt','$hbrptyear','$cloudrpt','$cloudrptyear','$nihrpt', 
								   '$cmobile','$xmemail','$approval', '$address1', '$hiflag', '$fpdfflag', '$pcrpdfflag', '$nationality', '$xlspcrtest2')";
						if (mysqli_query($conn, $sql2)) {
							//echo "New record created successfully";
							$count = $count + 1;
							$sql_comment = $_SESSION["username"].": ".$sql2;
                            write_sql($sql_comment);
						} else {
							echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
							$sql_comment = "Error: " . $sql2 . "<br>" . mysqli_error($conn);
                            write_sql($sql_comment);
						}
					}
				}
			}
		}
	}
}

mysqli_close($conn);
echo "total uploaded rows:" . $count . "<br>";
//echo "Uploaded.";
//echo "<br>"; 
?>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="js/d3.min.js" charset="utf-8"></script>
</head>

<body>
	<div>
		<input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁"></input>
		<input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁"></input>
		<input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版首頁"></input>

	</div>
</body>

</html>