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

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_data_checkin" . $bdaymonth . ".xlsx";

    $sql = "SELECT twrpturgency,userid,passportid,sex,cname,
                    fname,dob,mobile,uemail,sendname,apdat,tdat,
                    sampleid2
            FROM covid_trans
            WHERE apdat ='" . $bdaymonth . "' and sampleid2 is not null and tdat is not null
            ORDER BY sampleid2";


    if ($stmt = mysqli_prepare($conn, $sql)) {

        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) > 0) {

                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);

                $style = $sheet->getStyle('A2:K500');
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getBorders()->getAllBorders()->getColor()->setARGB('BLACK');

                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:C3');
                $sheet->mergeCells('D2:D3');
                $sheet->mergeCells('E2:E3');
                $sheet->mergeCells('F2:F3');
                $sheet->mergeCells('G2:H2');
                $sheet->mergeCells('I2:K2');
          

                $sheet->setCellValue('A1', '麗寶醫事檢驗所 COVID-19 採檢 每日工作表(' . $bdaymonth . ')');
                $sheet->setCellValue('A2', '檢測條碼');
                $sheet->setCellValue('B2', '客戶姓名');
                $sheet->setCellValue('C2', '狀況說明');
                $sheet->setCellValue('D2', '採檢編號');
                $sheet->setCellValue('E2', '檢測項目');
                $sheet->setCellValue('F2', '收費金額');
                $sheet->setCellValue('G2', '收費方式');
                $sheet->setCellValue('G3', '現金');
                $sheet->setCellValue('H3', '刷卡');
                $sheet->setCellValue('I2', '改件');
                $sheet->setCellValue('I3', '一般件');
                $sheet->setCellValue('J3', '急件');
                $sheet->setCellValue('K3', '特急件');


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
                    $sheet->getRowDimension($row1)->setRowHeight(18);
                }

                // $sheet->getStyle('C')->getAlignment()->setHorizontal($alignment->getHorizontal())
                //     ->setVertical($alignment->getVertical()); //自動調整欄寬
                $sheet->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $sheet->getColumnDimension('B')->setWidth(18.5);
                $sheet->getColumnDimension('C')->setWidth(28);
                $sheet->getColumnDimension('D')->setWidth(10);
                $sheet->getColumnDimension('E')->setWidth(17);
                $sheet->getColumnDimension('F')->setWidth(10);
                $sheet->getColumnDimension('G')->setWidth(9);
                $sheet->getColumnDimension('H')->setWidth(9);
                $sheet->getColumnDimension('I')->setWidth(9);
                $sheet->getColumnDimension('J')->setWidth(9);
                $sheet->getColumnDimension('K')->setWidth(9);
                $row = -1;
                $row = $row + 1;

                // 字體大小
                $style = $sheet->getStyle('A1');
                $font = $style->getFont();
                $font->setSize(26);
                $font->setBold(true); //粗體           

                // 欄位顏色
                $stylecolor1 = $sheet->getStyle('A2:K2');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(12);
                $fontcolor->setColor(new Color(Color::COLOR_WHITE)); //文字顏色
                $fontcolor->setBold(true);

                // $stylecolor2 = $sheet->getStyle('H2:J2');
                // $fill = $stylecolor1->getFill();
                // $fill->setFillType(Fill::FILL_SOLID);
                // $fill->getStartColor()->setARGB('BLACK');
                // $fontcolor1 = $stylecolor2->getFont();
                // $fontcolor1->setSize(12);
                // $fontcolor1->setColor(new Color(Color::COLOR_WHITE));
                // $fontcolor1->setBold(true);

                $rowCount = $sheet->getHighestRow();

                // for ($row = 4; $row <= $rowCount; $row += 2) {

                //     if ($twrpturgency == 'hiurgent'){
                //         $fill->setFillType(Fill::FILL_SOLID);
                //         $fill->getStartColor()->setRGB('FFFF00');

                //     }else{
                //         $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                //         $fill = $sheet->getStyle($range)->getFill();
                //         $fill->setFillType(Fill::FILL_SOLID);
                //         $fill->getStartColor()->setRGB('dddddd');
                //     }
                // }

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
                    $sendname,
                    $apdat,
                    $tdat,
                    $sampleid2,

                );

                if ($twrpturgency == 'hiurgent') {
                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getColor()->setRGB('FFFF00');

                } else {
                    for ($row = 4; $row <= $rowCount; $row += 2) {

                        $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                        $fill = $sheet->getStyle($range)->getFill();
                        $fill->setFillType(Fill::FILL_SOLID);
                        $fill->getStartColor()->setRGB('dddddd');
                    }
                }

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



                    $row = $row + 1;

                    if (!empty($sampleid2) && $twrpturgency == 'hiurgent') {

                        $PCRtwrpturgency = "核酸急件(特急件)";
                        $price = "4,500";
                        if ($PCRtwrpturgency == "核酸急件(特急件)") {
                            $styleArray = [
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'FFFF00',
                                    ],
                                ],
                            ];
                            $sheet->getStyle('D' . $row . ':E' . $row)->applyFromArray($styleArray);

                        }


                    } elseif (!empty($sampleid2) && $twrpturgency == 'urgent') {
                        $PCRtwrpturgency = "核酸急件(急件)";
                        $price = "3,500";

                    } elseif (!empty($sampleid2) && $twrpturgency == 'normal') {
                        $PCRtwrpturgency = "核酸急件(一般)";
                        $price = "2,500";

                    } else {
                        $price = "";
                    }






                    $PCRid = substr($sampleid2, 7);

                    $sheet->setCellValueByColumnAndRow(1, $row, $sampleid2);
                    $sheet->setCellValueByColumnAndRow(2, $row, $name);
                    $sheet->setCellValueByColumnAndRow(3, $row, $sendname);
                    $sheet->setCellValueByColumnAndRow(4, $row, $PCRid);
                    $sheet->setCellValueByColumnAndRow(5, $row, $PCRtwrpturgency);
                    $sheet->setCellValueByColumnAndRow(6, $row, $price);



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
            <h2>每日確認報到工作表下載</h2>
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