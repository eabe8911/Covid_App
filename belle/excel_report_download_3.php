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


if($_SERVER["REQUEST_METHOD"] == "POST")
{
   
    $now = date('Y-m-d H:i');
    $bdaymonth = $_POST["bdaymonth"];
    //echo($bdaymonth);
    //die();
    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_month_" . date("y-m-d") . ".xlsx";

    $sql = "SELECT twrpturgency,userid,passportid,sex,cname,
                    fname,dob,mobile,uemail,address2,apdat,tdat,
                    sampleid2,pcrtest,rdat,hicardno
            FROM covid_trans 
            WHERE pcrtest<>'' AND SUBSTR(sampleid2,1,2) <> 'QL' AND SUBSTR(sampleid2,1,2) <> 'QH' AND sampleid2<>''
            AND DATE_FORMAT(tdat, '%Y-%m') ='".$bdaymonth."'
            ORDER BY rdat" ;
                

    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', '時效性');
                $sheet->setCellValue('B1', '身份證號');
                $sheet->setCellValue('C1', '護照號碼');
                $sheet->setCellValue('D1', '性別');
                $sheet->setCellValue('E1', '中文名');
                $sheet->setCellValue('F1', '英文名');
                $sheet->setCellValue('G1', '生日');
                $sheet->setCellValue('H1', '手機');
                $sheet->setCellValue('I1', 'Email');
                $sheet->setCellValue('J1', '地址');
                $sheet->setCellValue('K1', '預約日');
                $sheet->setCellValue('L1', '報到日');
                $sheet->setCellValue('M1', 'qPCRID');
                $sheet->setCellValue('N1', 'qPCR結果');
                $sheet->setCellValue('O1', '判讀日');
                $sheet->setCellValue('P1', '健保卡號');
                $sheet->setCellValue('Q1', '報到日時分');
                $sheet->setCellValue('R1', '判讀日時分');
                $sheet->setCellValue('S1', '判讀日期');


                mysqli_stmt_bind_result(
                    $stmt,
                    $twrpturgency,
                    $userid,
                    $passportid,
                    $sex,
                    $cname,
                    $fname,
                    $dob,
                    $mobile,
                    $uemail,
                    $address2,
                    $apdat,
                    $tdat,
                    $sampleid2,
                    $pcrtest,
                    $rdat,
                    $hicardno  
                );
                $row = 1;




                while (mysqli_stmt_fetch($stmt)) {
                    $row = $row + 1;

                    $sheet->setCellValueByColumnAndRow(1, $row, $twrpturgency);
                    $sheet->setCellValueByColumnAndRow(2, $row, $userid);
                    $sheet->setCellValueByColumnAndRow(3, $row, $passportid);
                    $sheet->setCellValueByColumnAndRow(4, $row, $sex);
                    $sheet->setCellValueByColumnAndRow(5, $row, $cname);
                    $sheet->setCellValueByColumnAndRow(6, $row, $fname);
                    $sheet->setCellValueByColumnAndRow(7, $row, $dob);
                    $sheet->setCellValueByColumnAndRow(8, $row, $mobile);
                    $sheet->setCellValueByColumnAndRow(9, $row, $uemail);
                    $sheet->setCellValueByColumnAndRow(10, $row, $address2);
                    $sheet->setCellValueByColumnAndRow(11, $row, $apdat);
                    $sheet->setCellValueByColumnAndRow(12, $row, $tdat);
                    $sheet->setCellValueByColumnAndRow(13, $row, $sampleid2);
                    $sheet->setCellValueByColumnAndRow(14, $row, $pcrtest);
                    $sheet->setCellValueByColumnAndRow(15, $row, $rdat);
                    $sheet->setCellValueByColumnAndRow(16, $row, $hicardno);                    
                    if (!empty($tdat)) {
                        $sheet->setCellValueByColumnAndRow(17, $row, explode(":", $tdat)[0] . ":" . explode(":", $tdat)[1]);
                    } else {
                        $sheet->setCellValueByColumnAndRow(17, $row, '');
                    }
                    if (!empty($rdat)) {
                        $sheet->setCellValueByColumnAndRow(18, $row, explode(":", $rdat)[0] . ":" . explode(":", $rdat)[1]);
                    } else {
                        $sheet->setCellValueByColumnAndRow(18, $row, '');
                    }
                    $sheet->setCellValueByColumnAndRow(19, $row, explode(" ", $rdat)[0]);
                }
               
                $writer = new Xlsx($spreadsheet);

                ob_end_clean();
                $writer->save($file_name);
            }
        }
    }

    

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //$generate_result = "報告產生完畢，請點右列網址下載!";
    // echo "<br>";
    header("Location: /download.php?path=".$file_name);
    //header("Location:$file_name");
}



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
                </div>
            </div>
        </div>
        <div class="col py-3">
            <h2>麗寶檢測月結下載</h2>
             <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <br>
             <input type="month" id="bdaymonth" name="bdaymonth">
             <br><br>
             <input type="submit" value="下載資料">
            </form> 
        </div>

</body>
<script>
    $(document).ready(function () {
         var time = new Date();
         var month = ("0" + (time.getMonth() + 1)).slice(-2);
         var today = time.getFullYear() + "-" + (month);
         $('#bdaymonth').val(today);
     })
 </script>

</html>