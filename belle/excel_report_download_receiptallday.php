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
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$generate_result = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $now = date('Y-m-d H:i');
    $bdaymonth = $_POST["bdaymonth"];
    //echo($bdaymonth);
    //die();
    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_receiptallday_" . $bdaymonth . ".xlsx";

    $sql = "SELECT sampleid2,receiptid,cname,fname,sendname,twrpturgency,payflag
            FROM covid_trans
            WHERE apdat ='" . $bdaymonth . "' and sampleid2 is not null
            ORDER BY sampleid2";



    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);


                $style = $sheet->getStyle('A2:I500');
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
                $sheet->mergeCells('I4:J5');


                $sheet->setCellValue('A1', '麗寶醫事檢驗所');
                $sheet->setCellValue('A2', '日結統計表');
                $sheet->setCellValue('A3', '日期 : ' . $bdaymonth . '');
                $sheet->setCellValue('A4', '檢測項目');
                $sheet->setCellValue('B4', '品號');
                $sheet->setCellValue('C4', '現金');
                $sheet->setCellValue('E4', '刷卡');
                $sheet->setCellValue('G4', '匯款');
                $sheet->setCellValue('I4', '小計');




                // 置中對齊
                $alignment = new Alignment();
                $alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $alignment->setVertical(Alignment::VERTICAL_CENTER);
                $highestColumn = $sheet->getHighestColumn();

                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                    $sheet->getStyle($col)->getAlignment()->setHorizontal($alignment->getHorizontal())
                        ->setVertical($alignment->getVertical());
                }


                $rowCount = $sheet->getHighestRow();
                for ($row1 = 4; $row1 <= $rowCount; $row1++) {
                    $sheet->getRowDimension($row1)->setRowHeight(15);
                }

                $sheet->getStyle('A')->getAlignment()->setWrapText(true);
                $sheet->getRowDimension(1)->setRowHeight(32);
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(15);

                $row = -1;
                $row = $row + 1;
                $sheet->getRowDimension($row)->setRowHeight(20);

                // 字體大小
                $style = $sheet->getStyle('A1');
                $font = $style->getFont();
                $font->setSize(26);
                $font->setBold(true); //粗體           

                // 欄位顏色
                $stylecolor1 = $sheet->getStyle('A2:F2');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(12);
                $fontcolor->setColor(new Color(Color::COLOR_WHITE)); //文字顏色
                $fontcolor->setBold(true);

                $stylecolor2 = $sheet->getStyle('H2:I2');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor1 = $stylecolor2->getFont();
                $fontcolor1->setSize(12);
                $fontcolor1->setColor(new Color(Color::COLOR_WHITE));
                $fontcolor1->setBold(true);

                $rowCount = $sheet->getHighestRow();

                for ($row = 5; $row <= $rowCount; $row += 2) {
                    $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                    $fill = $sheet->getStyle($range)->getFill();

                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getStartColor()->setRGB('dddddd');
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

                $row = 3;


                while (mysqli_stmt_fetch($stmt)) {

                    if (!empty($cname)) {
                        $name = $cname;
                    } else {
                        $name = $fname;
                    }

                    if (!empty($userid)) {
                        $idnumber = $userid;
                    } else {
                        $idnumber = $passportid;
                    }



                    if (!empty($sampleid2) && $twrpturgency == 'hiurgent') {

                        $PCRtwrpturgency = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-快速件";
                        $productid = "C0110150";

                    } elseif (!empty($sampleid2) && $twrpturgency == 'urgent') {
                        $PCRtwrpturgency = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-春節專案快速件";
                        $productid = "C0110151";

                    } elseif (!empty($sampleid2) && $twrpturgency == 'normal') {
                        $PCRtwrpturgency = "新型冠狀病毒核酸檢測(Covid19 PCR \ntesting)-春節專案一般件";
                        $productid = "C0110136";

                    } else {
                        $productid = "";
                    }
                    $row = $row + 1;



                    $sheet->setCellValueByColumnAndRow(1, $row, $PCRtwrpturgency);
                    $sheet->setCellValueByColumnAndRow(2, $row, $productid);
                    // $sheet->setCellValueByColumnAndRow(3, $row, $idnumber);  
                    $sheet->setCellValueByColumnAndRow(3, $row, );
                    $sheet->setCellValueByColumnAndRow(4, $row, );
                    $sheet->setCellValueByColumnAndRow(5, $row, );



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
    header("Location: /download.php?path=" . $file_name);
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
            <h2>每日工作表下載</h2>
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