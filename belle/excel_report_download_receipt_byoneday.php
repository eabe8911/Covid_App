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

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_receipt_byoneday_" . $bdaymonth . ".xlsx";

    $sql = "SELECT sampleid2,receiptid,cname,fname,sendname,twrpturgency,payflag
            FROM covid_trans
            WHERE apdat ='" . $bdaymonth . "' and tdat is not null
            ORDER BY sampleid2";

    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'normal' AND payflag <> '4' ");
    $stmt1->bind_param('s', $bdaymonth);
    $stmt1->execute();
    $stmt1->bind_result($countnormal);
    $stmt1->fetch();
    $stmt1->close();

    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'urgent' AND payflag <> '4'");
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

    $stmt3 = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'hiurgent' AND payflag <> '4'");
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

    $cashnormal = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'normal' AND payflag = '2'");
    $cashnormal->bind_param('s', $bdaymonth);
    $cashnormal->execute();
    $cashnormal->bind_result($count_cashnor);
    $cashnormal->fetch();
    $cashnormal->close();

    $cashurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'urgent' AND payflag = '2'");
    $cashurgent->bind_param('s', $bdaymonth);
    $cashurgent->execute();
    $cashurgent->bind_result($count_cashur);
    $cashurgent->fetch();
    $cashurgent->close();

    $cashhiurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'hiurgent' AND payflag = '2'");
    $cashhiurgent->bind_param('s', $bdaymonth);
    $cashhiurgent->execute();
    $cashhiurgent->bind_result($count_cashhi);
    $cashhiurgent->bind_result($count_cashhi);
    $cashhiurgent->fetch();
    $cashhiurgent->close();

    $cardnormal = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'normal' AND payflag = '3'");
    $cardnormal->bind_param('s', $bdaymonth);
    $cardnormal->execute();
    $cardnormal->bind_result($count_cardnor);
    $cardnormal->fetch();
    $cardnormal->close();

    $cardurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'urgent' AND payflag = '3'");
    $cardurgent->bind_param('s', $bdaymonth);
    $cardurgent->execute();
    $cardurgent->bind_result($count_cardur);
    $cardurgent->fetch();
    $cardurgent->close();

    $cardhiurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'hiurgent' AND payflag = '3'");
    $cardhiurgent->bind_param('s', $bdaymonth);
    $cardhiurgent->execute();
    $cardhiurgent->bind_result($count_cardhi);
    $cardhiurgent->fetch();
    $cardhiurgent->close();

    $trannormal = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'normal' AND payflag = '5'");
    $trannormal->bind_param('s', $bdaymonth);
    $trannormal->execute();
    $trannormal->bind_result($count_trannor);
    $trannormal->fetch();
    $trannormal->close();

    $tranurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'urgent' AND payflag = '5'");
    $tranurgent->bind_param('s', $bdaymonth);
    $tranurgent->execute();
    $tranurgent->bind_result($count_tranur);
    $tranurgent->fetch();
    $tranurgent->close();

    $tranhiurgent = $conn->prepare("SELECT COUNT(*) FROM covid_trans WHERE apdat = ? AND tdat IS NOT NULL AND twrpturgency = 'hiurgent' AND payflag = '5'");
    $tranhiurgent->bind_param('s', $bdaymonth);
    $tranhiurgent->execute();
    $tranhiurgent->bind_result($count_tranhi);
    $tranhiurgent->fetch();
    $tranhiurgent->close();

    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                // $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE); //列印橫式

                $style = $sheet->getStyle('A4:J15');
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getBorders()->getAllBorders()->getColor()->setARGB('BLACK');

                $style1 = $sheet->getStyle('A16:J16');
                $style1->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style1->getBorders()->getAllBorders()->getColor()->setARGB('WHITE');

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');
                $sheet->mergeCells('A4:A5');
                $sheet->mergeCells('B4:B5');
                $sheet->mergeCells('C4:D4');
                $sheet->mergeCells('E4:F4');
                $sheet->mergeCells('G4:H4');
                $sheet->mergeCells('I4:J4');
                $sheet->mergeCells('A16:B16');

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
                $sheet->setCellValue('A16', '合計');

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

                $alignment = $sheet->getStyle('A6:A15')->getAlignment();
                $alignment->setHorizontal(Alignment::HORIZONTAL_LEFT);

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
                $sheet->getRowDimension($row)->setRowHeight(30);

                // set font 
                $styleA1 = $sheet->getStyle('A1');
                $fontA1 = $styleA1->getFont();
                $fontA1->setSize(30);
                $fontA1->setBold(true);
                $styleA2 = $sheet->getStyle('A2');
                $fontA2 = $styleA2->getFont();
                $fontA2->setSize(18);
                $fontA2->setBold(true);
                $styleA3 = $sheet->getStyle('A3');
                $fontA3 = $styleA3->getFont();
                $fontA3->setSize(16);

                // set column color
                $stylecolor1 = $sheet->getStyle('A4:J4');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(14);
                //set text color
                $fontcolor->setColor(new Color(Color::COLOR_WHITE));

                // set column color
                $stylecolor1 = $sheet->getStyle('A16:J16');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(14);
                //set text color
                $fontcolor->setColor(new Color(Color::COLOR_WHITE));
                $stylecolor1->getBorders()->getAllBorders()->getColor()->setARGB('WHITE');



                //set row height
                $sheet->getStyle('A')->getAlignment()->setWrapText(true);
                $sheet->getRowDimension(1)->setRowHeight(40);
                $sheet->getRowDimension(2)->setRowHeight(23);
                $sheet->getRowDimension(3)->setRowHeight(23);
                $sheet->getRowDimension(14)->setRowHeight(30);
                $sheet->getRowDimension(15)->setRowHeight(30);

                $sheet->getStyle('J6:J15')->getNumberFormat()->setFormatCode('#,##0');

                for ($row = 5; $row <= $rowCount; $row += 2) {
                    $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                    $fill = $sheet->getStyle($range)->getFill();

                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getStartColor()->setRGB('EBEBEB');
                }

                $rowCount = $sheet->getHighestRow();

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
                    $sheet->setCellValue('C6', $count_cashnor);
                    $cash_totalnormal = intval($count_cashnor) * intval(2500);
                    $sheet->setCellValue('D6', $cash_totalnormal);
                    $sheet->setCellValue('E6', $count_cardnor);
                    $card_totalnormal = intval($count_cardnor) * intval(2500);
                    $sheet->setCellValue('F6', $card_totalnormal);
                    $sheet->setCellValue('G6', $count_trannor);
                    $tran_totalnormal = intval($count_trannor) * intval(2500);
                    $sheet->setCellValue('H6', $tran_totalnormal);
                    $sheet->setCellValue('I6', $countnormal);
                    $totalnormal = intval($countnormal) * intval(2500);
                    $sheet->setCellValue('J6', $totalnormal);

                    $sheet->setCellValue('A7', $PCRurgent);
                    $sheet->setCellValue('B7', $productid_ur);
                    $sheet->setCellValue('C7', $count_cashur);
                    $cash_totalurgent = intval($count_cashur) * intval(3500);
                    $sheet->setCellValue('D7', $cash_totalurgent);
                    $sheet->setCellValue('E7', $count_cardur);
                    $tran_totalurgent = intval($count_cardur) * intval(3500);
                    $sheet->setCellValue('F7', $tran_totalurgent);
                    $sheet->setCellValue('G7', $count_trannor);
                    $tran_totalnormal = intval($count_trannor) * intval(3500);
                    $sheet->setCellValue('H7', $tran_totalnormal);
                    $sheet->setCellValue('I7', $counturgent);
                    $totalurgent = intval($counturgent) * intval(3500);
                    $sheet->setCellValue('J7', $totalurgent);

                    $sheet->setCellValue('A8', $PCRhiurgent);
                    $sheet->setCellValue('B8', $productid_hi);
                    $sheet->setCellValue('C8', $count_cashhi);
                    $cash_totalhiurgent = intval($count_cashhi) * intval(4500);
                    $sheet->setCellValue('D8', $cash_totalhiurgent);
                    $sheet->setCellValue('E8', $count_cardhi);
                    $card_totalhiurgent = intval($count_cardhi) * intval(4500);
                    $sheet->setCellValue('F8', $card_totalhiurgent);
                    $sheet->setCellValue('G8', $count_tranhi);
                    $tran_totalhiurgent = intval($count_tranhi) * intval(4500);
                    $sheet->setCellValue('H8', $tran_totalhiurgent);
                    $sheet->setCellValue('I8', $counthiurgent);
                    $totalhiurgent = intval($counthiurgent) * intval(4500);
                    $sheet->setCellValue('J8', $totalhiurgent);

                    $sheet->setCellValue('A9', "新型冠狀病毒抗原快篩檢測(Covid19 Ag_TC testing)");
                    $sheet->setCellValue('B9', "C0110137");
                    $sheet->setCellValue('A10', "新型冠狀病毒核酸檢測(Covid19 PCR testing)-補款");
                    $sheet->setCellValue('B10', "C0110165");
                    $sheet->setCellValue('A11', "新型冠狀病毒核酸檢測(Covid19 PCR testing)-檢測前報告增修服務費用");
                    $sheet->setCellValue('B11', "C0110169");
                    $sheet->setCellValue('A12', "新型冠狀病毒核酸檢測(Covid19 PCR testing)-檢測後報告修改服務費用-一般件");
                    $sheet->setCellValue('B12', "C0110170");
                    $sheet->setCellValue('A13', "新型冠狀病毒核酸檢測(Covid19 PCR testing)-檢測後報告修改服務費用-緊急件");
                    $sheet->setCellValue('B13', "C0110171");
                    $sheet->setCellValue('A14', "黴漿菌檢測服務-急件");
                    $sheet->setCellValue('B14', "C0110168");
                    $sheet->setCellValue('A15', "黴漿菌檢測服務-一般件");
                    $sheet->setCellValue('B15', "C0110167");
                    // 計算總和
                    $total_c = $sheet->getCell('C16')->setValue('=SUM(C6:C15)')->getCalculatedValue();
                    $sheet->setCellValue('C16', $total_c);
                    $total_cash_money = $sheet->getCell('D16')->setValue('=SUM(D6:D15)')->getCalculatedValue();
                    $sheet->setCellValue('D16', $total_cash_money);
                    $total_card = $sheet->getCell('E16')->setValue('=SUM(E6:E15)')->getCalculatedValue();
                    $sheet->setCellValue('E16', $total_card);
                    $total_card_money = $sheet->getCell('F16')->setValue('=SUM(F6:F15)')->getCalculatedValue();
                    $sheet->setCellValue('F16', $total_card_money);
                    $total_tran = $sheet->getCell('G16')->setValue('=SUM(G6:G15)')->getCalculatedValue();
                    $sheet->setCellValue('G16', $total_tran);
                    $total_tran_money = $sheet->getCell('H16')->setValue('=SUM(H6:H15)')->getCalculatedValue();
                    $sheet->setCellValue('H16', $total_tran_money);
                    $total = $sheet->getCell('I16')->setValue('=SUM(I6:I15)')->getCalculatedValue();
                    $sheet->setCellValue('I16', $total);
                    $total_money = $sheet->getCell('J16')->setValue('=SUM(J6:J15)')->getCalculatedValue();
                    $sheet->setCellValue('J16', $total_money);
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