<?php

// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors", "On");
error_reporting(E_ALL);

require '/usr/share/php/vendor/autoload.php';
require "class/Positive.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$generate_result = "";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
   
    $now = date('Y-m-d H:i');
    $QueryDate = $_POST["bdaymonth"];
   
    
    $file_name = "/var/www/html/xls_reports/" . "libobio_positive_covid_month_" . date("y-m-d") . ".xlsx";

    $positive = new Positive();
    $responce = $positive->QueryAll($QueryDate);
    // echo ($QueryDate);
    // die();
    if (!empty($responce) ) {
        $spreadsheet = new Spreadsheet();
        //  echo ("OK1");
        //   die();
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

        $row = 1;
        $normal_num = 0;
        $urgent_num = 0;

        foreach($responce as $item){
            $row = $row + 1;
            $sheet->setCellValueByColumnAndRow(1, $row, $item['twrpturgency']);
            $sheet->setCellValueByColumnAndRow(2, $row, $item['userid']);
            $sheet->setCellValueByColumnAndRow(3, $row, $item['passportid']);
            $sheet->setCellValueByColumnAndRow(4, $row, $item['sex']);
            $sheet->setCellValueByColumnAndRow(5, $row, $item['cname']);
            $sheet->setCellValueByColumnAndRow(6, $row, $item['fname']);
            $sheet->setCellValueByColumnAndRow(7, $row, $item['dob']);
            $sheet->setCellValueByColumnAndRow(8, $row, $item['mobile']);
            $sheet->setCellValueByColumnAndRow(9, $row, $item['uemail']);
            $sheet->setCellValueByColumnAndRow(10, $row, $item['address2']);
            $sheet->setCellValueByColumnAndRow(11, $row, $item['apdat']);
            $sheet->setCellValueByColumnAndRow(12, $row, $item['tdat']);
            $sheet->setCellValueByColumnAndRow(13, $row, $item['sampleid2']);
            $sheet->setCellValueByColumnAndRow(14, $row, $item['pcrtest']);
            $sheet->setCellValueByColumnAndRow(15, $row, $item['rdat']);
            $sheet->setCellValueByColumnAndRow(16, $row, $item['hicardno']);                    
            // if (!empty($tdat)) {
            //     $sheet->setCellValueByColumnAndRow(17, $row, explode(":", $tdat)[0] . ":" . explode(":", $tdat)[1]);
            // } else {
            //     $sheet->setCellValueByColumnAndRow(17, $row, '');
            // }
            // if (!empty($rdat)) {
            //     $sheet->setCellValueByColumnAndRow(18, $row, explode(":", $rdat)[0] . ":" . explode(":", $rdat)[1]);
            // } else {
            //     $sheet->setCellValueByColumnAndRow(18, $row, '');
            // }
            // $sheet->setCellValueByColumnAndRow(19, $row, explode(" ", $rdat)[0]);
        }
        // echo (var_dump($spreadsheet));
        // die();
        $writer = new Xlsx($spreadsheet);

        ob_end_clean();
        $writer->save($file_name);
    } else{
        echo ("no data");
    }
        
    

    

    header("Location: /download.php?path=".$file_name);
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
            <h2>麗寶陽性查詢</h2>
             <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <br>
             <input type="date" id="bdaymonth" name="bdaymonth">
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