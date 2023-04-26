<?php

// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors", "On");
error_reporting(E_ALL);

require '/usr/share/php/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$generate_result = "";

#dictioinary
$Companies = [
    "16653688" => "麗寶生醫",
    "3501109194" => "麗星診所",
    "54022516" => "麗寶百貨"
];


$now = date('Y-m-d H:i');

// connect db
$conn = mysqli_connect("localhost", "libo_user", "xxx");
mysqli_select_db($conn, "libodb");

$file_name = "/var/www/html/xls_reports/" . "covid_" . date("y-m-d") . ".xlsx";

$sql = "SELECT testtype,type,ctzn,userid,passportid,sex,cname,fname,lname
                               ,dob,mobile,uemail,address1,address2,apdat,tdat
                               ,sampleid1,ftest,sampleid2,pcrtest,vuser1
                               ,rdat,emailflag,cdcflag,sendname,uuid,frptflag,qrptflag,hiflag
							   ,hicardno,mobilerpt,mailrpt,hbrpt,hbrptyear,cloudrpt,cloudrptyear,nihrpt
                                FROM covid_trans 
                                WHERE 1=1";
                                //WHERE 1=1 AND apdat=date(now())";//20220127 olive add now date download 


if ($stmt = mysqli_prepare($conn, $sql)) {

    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', '檢測項目');
            $sheet->setCellValue('B1', '檢測類型');
            $sheet->setCellValue('C1', '國籍');
            $sheet->setCellValue('D1', '身份證號');
            $sheet->setCellValue('E1', '護照號碼');
            $sheet->setCellValue('F1', '性別');
            $sheet->setCellValue('G1', '中文名');
            $sheet->setCellValue('H1', 'First Name');
            $sheet->setCellValue('I1', 'Last Name');
            $sheet->setCellValue('J1', '生日');
            $sheet->setCellValue('K1', '手機');
            $sheet->setCellValue('L1', 'Email');
            $sheet->setCellValue('M1', '地址1');
            $sheet->setCellValue('N1', '地址2');
            $sheet->setCellValue('O1', '預約日');
            $sheet->setCellValue('P1', '報到日');
            $sheet->setCellValue('Q1', '快篩ID');
            $sheet->setCellValue('R1', '快篩結果');
            $sheet->setCellValue('S1', 'qPCRID');
            $sheet->setCellValue('T1', 'qPCR結果');
            $sheet->setCellValue('U1', '判讀人員');
            $sheet->setCellValue('V1', '判讀日');
            $sheet->setCellValue('W1', '發送email');
            $sheet->setCellValue('X1', '上傳CDC');
            $sheet->setCellValue('Y1', '送件單位id');
            $sheet->setCellValue('Z1', '送件單位');
            $sheet->setCellValue('AA1', '英文名');
            $sheet->setCellValue('AB1', '報告類型');
            $sheet->setCellValue('AC1', '判讀人員');
            $sheet->setCellValue('AD1', '快篩類型');
            $sheet->setCellValue('AE1', 'qPCR類型');
            $sheet->setCellValue('AF1', 'tempsendname');
            $sheet->setCellValue('AG1', 'UUID');
            $sheet->setCellValue('AH1', '快篩覆核');
            $sheet->setCellValue('AI1', 'qPCR覆核');
            $sheet->setCellValue('AJ1', '台灣健保');
            $sheet->setCellValue('AK1', '健保卡號');
            $sheet->setCellValue('AL1', '同意手機號碼通報');
            $sheet->setCellValue('AM1', '紙本報告郵寄');
            $sheet->setCellValue('AN1', '同意上傳健康存摺');
            $sheet->setCellValue('AO1', '同意健康存摺利用年限');
            $sheet->setCellValue('AP1', '同意上傳醫療資訊雲端');
            $sheet->setCellValue('AQ1', '同意醫療資訊雲端利用年限');
            $sheet->setCellValue('AR1', '通報健保局');
            $sheet->setCellValue('AS1', '報到日時分');
            $sheet->setCellValue('AT1', '判讀日時分');
            $sheet->setCellValue('AU1', '判讀日期');
            $sheet->setCellValue('AV1', '報告日時分');


            mysqli_stmt_bind_result(
                $stmt,
                $testtype,
                $type,
                $ctzn,
                $userid,
                $passportid,
                $sex,
                $cname,
                $fname,
                $lname,
                $dob,
                $mobile,
                $uemail,
                $address1,
                $address2,
                $apdat,
                $tdat,
                $sampleid1,
                $ftest,
                $sampleid2,
                $pcrtest,
                $vuser1,
                $rdat,
                $emailflag,
                $cdcflag,
                $sendname,
                $uuid,
                $frptflag,
                $qrptflag,
                $hiflag,
                $hicardno,
                $mobilerpt,
                $mailrpt,
                $hbrpt,
                $hbrptyear,
                $cloudrpt,
                $cloudrptyear,
                $nihrpt
            );
            $row = 1;

            while (mysqli_stmt_fetch($stmt)) {
                $row = $row + 1;
                if (array_key_exists($sendname, $Companies)) {
                    $company = $Companies[$sendname];
                } else {
                    $company = "個人";
                }

                $sheet->setCellValueByColumnAndRow(1, $row, $testtype);
                $sheet->setCellValueByColumnAndRow(2, $row, $type);
                $sheet->setCellValueByColumnAndRow(3, $row, $ctzn);
                $sheet->setCellValueByColumnAndRow(4, $row, $userid);
                $sheet->setCellValueByColumnAndRow(5, $row, $passportid);
                $sheet->setCellValueByColumnAndRow(6, $row, $sex);
                $sheet->setCellValueByColumnAndRow(7, $row, $cname);
                $sheet->setCellValueByColumnAndRow(8, $row, $fname);
                $sheet->setCellValueByColumnAndRow(9, $row, $lname);
                $sheet->setCellValueByColumnAndRow(10, $row, $dob);
                $sheet->setCellValueByColumnAndRow(11, $row, $mobile);
                $sheet->setCellValueByColumnAndRow(12, $row, $uemail);
                $sheet->setCellValueByColumnAndRow(13, $row, $address1);
                $sheet->setCellValueByColumnAndRow(14, $row, $address2);
                $sheet->setCellValueByColumnAndRow(15, $row, $apdat);
                $sheet->setCellValueByColumnAndRow(16, $row, $tdat);
                $sheet->setCellValueByColumnAndRow(17, $row, $sampleid1);
                $sheet->setCellValueByColumnAndRow(18, $row, $ftest);
                $sheet->setCellValueByColumnAndRow(19, $row, $sampleid2);
                $sheet->setCellValueByColumnAndRow(20, $row, $pcrtest);
                $sheet->setCellValueByColumnAndRow(21, $row, $vuser1);
                $sheet->setCellValueByColumnAndRow(22, $row, $rdat);
                $sheet->setCellValueByColumnAndRow(23, $row, $emailflag);
                $sheet->setCellValueByColumnAndRow(24, $row, $cdcflag);
                $sheet->setCellValueByColumnAndRow(25, $row, $sendname);
                $sheet->setCellValueByColumnAndRow(26, $row, $company);
                $sheet->setCellValueByColumnAndRow(27, $row, $fname . " " . $lname);
                $sheet->setCellValueByColumnAndRow(28, $row, "1");
                $sheet->setCellValueByColumnAndRow(29, $row, "");
                $sheet->setCellValueByColumnAndRow(30, $row, "抗原快篩 / Ag_TC");
                $sheet->setCellValueByColumnAndRow(31, $row, "核酸檢測 / PCR");
                $sheet->setCellValueByColumnAndRow(32, $row, $company);
                $sheet->setCellValueByColumnAndRow(33, $row, $uuid);
                $sheet->setCellValueByColumnAndRow(34, $row, $frptflag);
                $sheet->setCellValueByColumnAndRow(35, $row, $qrptflag);
                $sheet->setCellValueByColumnAndRow(36, $row, $hiflag);
                $sheet->setCellValueByColumnAndRow(37, $row, $hicardno);
                $sheet->setCellValueByColumnAndRow(38, $row, $mobilerpt);
                $sheet->setCellValueByColumnAndRow(39, $row, $mailrpt);
                $sheet->setCellValueByColumnAndRow(40, $row, $hbrpt);
                $sheet->setCellValueByColumnAndRow(41, $row, $hbrptyear);
                $sheet->setCellValueByColumnAndRow(42, $row, $cloudrpt);
                $sheet->setCellValueByColumnAndRow(43, $row, $cloudrptyear);
                $sheet->setCellValueByColumnAndRow(44, $row, $nihrpt);
                if (!empty($tdat)) {
                    $sheet->setCellValueByColumnAndRow(45, $row, explode(":", $tdat)[0] . ":" . explode(":", $tdat)[1]);
                } else {
                    $sheet->setCellValueByColumnAndRow(45, $row, '');
                }
                if (!empty($rdat)) {
                    $sheet->setCellValueByColumnAndRow(46, $row, explode(":", $rdat)[0] . ":" . explode(":", $rdat)[1]);
                } else {
                    $sheet->setCellValueByColumnAndRow(46, $row, '');
                }
                $sheet->setCellValueByColumnAndRow(47, $row, explode(" ", $rdat)[0]);
                $sheet->setCellValueByColumnAndRow(48, $row, $now);
            }
            $writer = new Xlsx($spreadsheet);

            ob_end_clean();
            $writer->save($file_name);
        }
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

$generate_result = "報告產生完畢，請點右列網址下載!";
// echo "<br>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel 報表下載</title>
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
                <div>
                    <a class="nav-link px-0"><?php echo $generate_result; ?> <span class="d-none d-sm-inline"></span></a>
                </div>
                <div>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁"></input>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁"></input>
                    <!-- <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版首頁"></input> -->
                </div>
            </div>
        </div>
        <div class="col py-3">
            <h3>
                <p><a href="../download.php?path=<?php echo $file_name ?>">Download Excel Report file Excel 報表下載</a></p>
            </h3>
        </div>

</body>

</html>