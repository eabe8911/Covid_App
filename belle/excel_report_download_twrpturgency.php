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



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $now = date('Y-m-d H:i');
    $bdaymonth = $_POST["bdaymonth"];

    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_twrpturgency_" . $bdaymonth . ".xlsx";

    $sql = "SELECT twrpturgency,sampleid2,cname,fname
            FROM covid_trans 
            WHERE apdat ='" . $bdaymonth . "' and twrpturgency = 'hiurgent'
            ORDER BY sampleid2";

    $sql1 = "SELECT twrpturgency,sampleid2,cname,fname
             FROM covid_trans 
             WHERE apdat ='" . $bdaymonth . "' and twrpturgency = 'urgent'
             ORDER BY sampleid2";

    $sql2 = "SELECT twrpturgency,sampleid2,cname,fname
             FROM covid_trans 
             WHERE apdat ='" . $bdaymonth . "' and twrpturgency = 'normal'
             ORDER BY sampleid2";



    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', '時效性 = 特急件');
                $sheet->setCellValue('B1', 'qPCRID');
                $sheet->setCellValue('C1', '中文名');
                $sheet->setCellValue('D1', '英文名');

                mysqli_stmt_bind_result(
                    $stmt,
                    $twrpturgency,
                    $sampleid2,
                    $cname,
                    $fname,
                );
                $row = 1;
                while (mysqli_stmt_fetch($stmt)) {

                    $row = $row + 1;
                    $sheet->setCellValueByColumnAndRow(1, $row, $twrpturgency);
                    $sheet->setCellValueByColumnAndRow(2, $row, $sampleid2);
                    $sheet->setCellValueByColumnAndRow(3, $row, $cname);
                    $sheet->setCellValueByColumnAndRow(4, $row, $fname);

                }

                $writer = new Xlsx($spreadsheet);

                // ob_end_clean();
                // $writer->save($file_name);
            }
        }
    }

    if ($stmt = mysqli_prepare($conn, $sql1)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('F1', '時效性 = 急件');
                $sheet->setCellValue('G1', 'qPCRID');
                $sheet->setCellValue('H1', '中文名');
                $sheet->setCellValue('I1', '英文名');


                mysqli_stmt_bind_result(
                    $stmt,
                    $twrpturgency,
                    $sampleid2,
                    $cname,
                    $fname,
                );
                $row = 1;
                while (mysqli_stmt_fetch($stmt)) {

                    $row = $row + 1;
                    $sheet->setCellValueByColumnAndRow(6, $row, $twrpturgency);
                    $sheet->setCellValueByColumnAndRow(7, $row, $sampleid2);
                    $sheet->setCellValueByColumnAndRow(8, $row, $cname);
                    $sheet->setCellValueByColumnAndRow(9, $row, $fname);

                }

                $writer = new Xlsx($spreadsheet);


                // $writer->save($file_name);

            }
        }
    }

    if ($stmt = mysqli_prepare($conn, $sql2)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('K1', '時效性= 一般件');
                $sheet->setCellValue('L1', 'qPCRID');
                $sheet->setCellValue('M1', '中文名');
                $sheet->setCellValue('N1', '英文名');

                mysqli_stmt_bind_result(
                    $stmt,
                    $twrpturgency,
                    $sampleid2,
                    $cname,
                    $fname,
                );
                $row = 1;
                while (mysqli_stmt_fetch($stmt)) {

                    $row = $row + 1;
                    $sheet->setCellValueByColumnAndRow(11, $row, $twrpturgency);
                    $sheet->setCellValueByColumnAndRow(12, $row, $sampleid2);
                    $sheet->setCellValueByColumnAndRow(13, $row, $cname);
                    $sheet->setCellValueByColumnAndRow(14, $row, $fname);

                }

                $writer = new Xlsx($spreadsheet);

                // // ob_end_clean();
                // $writer->save($file_name);
            }
        }
    }
    
    $writer->save($file_name);


    mysqli_stmt_close($stmt);
    mysqli_close($conn);


    header("Location: /download.php?path=" . $file_name);

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel 報告時效性表下載</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/search_info.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function () {
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
                    <a class="nav-link px-0">
                        <?php echo $generate_result; ?> <span class="d-none d-sm-inline"></span>
                    </a>
                </div>
                <div>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()"
                        value="回到上一頁"></input>
                    <input style="margin:1em;" type="button" class="btn btn-secondary"
                        onclick="window.location.href='menu.php'" value="回首頁"></input>
                </div>
            </div>
        </div>
        <div class="col py-3">
            <h2>報告時效性下載</h2>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <br>
                <input type="date" id="bdaymonth" name="bdaymonth">
                <br><br>
                <input type="submit" value="下載資料">
            </form>
            <div class="form-group" style='display:inline'>
				<label>查詢結果</label><br>
         
          

                <br>
				<br>
			</div> 
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