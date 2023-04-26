<?php

// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors", "On");
error_reporting(E_ALL);

require '/usr/share/php/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;



$generate_result = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $now = date('Y-m-d H:i');
    $bdaymonth = $_POST["bdaymonth"];
    //echo($bdaymonth);
    //die();
    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_endofday_" . $bdaymonth . ".xlsx";

    $sql = "SELECT sampleid2,receiptid,cname,fname,sendname,twrpturgency,payflag
            FROM covid_trans
            WHERE apdat ='" . $bdaymonth . "' and sampleid2 is not null
            ORDER BY sampleid2";

    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND sampleid2 IS NOT NULL AND twrpturgency = 'normal'");
    if (!$stmt1) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt1->bind_param('s', $bdaymonth);
    if (!$stmt1->execute()) {
        die('Error executing statement: ' . $stmt1->error);
    }

    $stmt1->bind_result($countnormal);
    if ($stmt1->fetch()) {
        echo "Count: " . $countnormal;
    }

    $stmt1->close();

    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND sampleid2 IS NOT NULL AND twrpturgency = 'urgent'");
    if (!$stmt2) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt2->bind_param('s', $bdaymonth);
    if (!$stmt2->execute()) {
        die('Error executing statement: ' . $stmt2->error);
    }

    $stmt2->bind_result($counturgent);
    if ($stmt2->fetch()) {
        echo "Count: " . $counturgent;
    }

    $stmt2->close();

    $stmt3 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND sampleid2 IS NOT NULL AND twrpturgency = 'hiurgent'");
    if (!$stmt3) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt3->bind_param('s', $bdaymonth);
    if (!$stmt3->execute()) {
        die('Error executing statement: ' . $stmt3->error);
    }

    $stmt3->bind_result($counthiurgent);
    if ($stmt3->fetch()) {
        echo "Count: " . $counthiurgent;
    }

    $stmt3->close();


    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                // $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE); //列印橫式

                $style = $sheet->getStyle('A4:J500');
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getBorders()->getAllBorders()->getColor()->setARGB('BLACK');

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');
                $sheet->mergeCells('A4:A5');
                $sheet->mergeCells('B4:B5');
                $sheet->mergeCells('C4:D4');
                $sheet->mergeCells('E4:F4');
                $sheet->mergeCells('G4:H4');
                $sheet->mergeCells('I4:J4');

                $sheet->setCellValue('A1', '麗寶醫事檢驗所');
                $sheet->setCellValue('A2', '日結統計表');
                $sheet->setCellValue('A3', '日期 : ' . $bdaymonth . '');
                $sheet->setCellValue('A4', '檢測項目');
                $sheet->setCellValue('B4', '品號');
                $sheet->setCellValue('C4', '現金');
                $sheet->setCellValue('C5', '人檢次');
                $sheet->setCellValue('D5', '金額');
                $sheet->setCellValue('E4', '刷卡');
                $sheet->setCellValue('E5', '人檢次');
                $sheet->setCellValue('F5', '金額');
                $sheet->setCellValue('G4', '匯款');
                $sheet->setCellValue('G5', '人檢次');
                $sheet->setCellValue('H5', '金額');
                $sheet->setCellValue('I4', '小計');
                $sheet->setCellValue('I5', '人檢次');
                $sheet->setCellValue('J5', '金額');

                // 置中對齊
                $alignment = new Alignment();
                $alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $alignment->setVertical(Alignment::VERTICAL_CENTER);
                $highestColumn = $sheet->getHighestColumn();

                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(32);
                    $sheet->getStyle($col)->getAlignment()->setHorizontal($alignment->getHorizontal())
                        ->setVertical($alignment->getVertical());
                }

                // $sheet->getStyle('F')->getAlignment()->setHorizontal($alignment->getHorizontal())
                //     ->setVertical($alignment->getVertical());
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(15);

                $row = -1;
                $row = $row + 1;
                $sheet->getRowDimension($row)->setRowHeight(20);

                // 字體大小
                $styleA1 = $sheet->getStyle('A1');
                $fontA1 = $styleA1->getFont();
                $fontA1->setSize(30);
                $fontA1->setBold(true); //粗體     

                $styleA2 = $sheet->getStyle('A2');
                $fontA2 = $styleA2->getFont();
                $fontA2->setSize(18);
                $fontA2->setBold(true); //粗體   

                $styleA3 = $sheet->getStyle('A3');
                $fontA3 = $styleA3->getFont();
                $fontA3->setSize(16);

                // 欄位顏色
                $stylecolor1 = $sheet->getStyle('A4:J4');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(14);
                $fontcolor->setColor(new Color(Color::COLOR_WHITE)); //文字顏色

                $sheet->getStyle('A')->getAlignment()->setWrapText(true);
                $sheet->getRowDimension(1)->setRowHeight(40); //列高
                $sheet->getRowDimension(2)->setRowHeight(23);
                $sheet->getRowDimension(3)->setRowHeight(23);

                $rowCount = $sheet->getHighestRow();

                for ($row = 5; $row <= $rowCount; $row += 2) {
                    $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                    $fill = $sheet->getStyle($range)->getFill();

                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getStartColor()->setRGB('EBEBEB');
                }

                mysqli_stmt_bind_result(
                    $stmt,
                    $sampleid2,
                    $receiptid,
                    $cname,
                    $fname,
                    $sendname,
                    $twrpturgency,
                    $payflag,
                );
                $row = 5;

                while (mysqli_stmt_fetch($stmt)) {


                    $row = $row + 1;

                    $PCRhiurgent = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-快速件";
                    $productid_hi = "C0110136";

                    $PCRurgent = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-春節專案快速件";
                    $productid_ur = "C0110151";

                    $PCRnormal = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-春節專案一般件";
                    $productid_no = "C0110150";


                    $sheet->setCellValue('A6', $PCRnormal);
                    $sheet->setCellValue('B6', $productid_no);
                    $sheet->setCellValue('I6', $countnormal);
                    $totalnormal = intval($countnormal) * intval(2500);
                    $sheet->setCellValue('J6', $totalnormal);

                    $sheet->setCellValue('A7', $PCRurgent);
                    $sheet->setCellValue('B7', $productid_ur);
                    $sheet->setCellValue('I7', $counturgent);
                    $totalurgent = intval($counturgent) * intval(3500);
                    $sheet->setCellValue('J7', $totalurgent);

                    $sheet->setCellValue('A8', $PCRhiurgent);
                    $sheet->setCellValue('B8', $productid_hi);
                    $sheet->setCellValue('I8', $counthiurgent);
                    $totalhiurgent = intval($counthiurgent) * intval(4500);
                    $sheet->setCellValue('J8', $totalhiurgent);

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
    header("Location: http://192.168.2.23/download.php?path=" . $file_name);
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
            <h2>日結統計表下載</h2>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
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