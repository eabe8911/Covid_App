<?php

// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors", "On");
error_reporting(E_ALL);

// require '/usr/share/php/vendor/autoload.php';
require 'C:\Users\tina.xue\Documents\Tina\appoint-back\vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;


$generate_result = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $now = date('Y-m-d H:i');
    $bdaymonth = $_POST["bdaymonth"];
    //echo($bdaymonth);
    //die();
    // $conn = mysqli_connect("localhost", "libo_user", "xxx");
    $conn = mysqli_connect("maxcheng.tw:3307", "root", ",-4,4p-2");
    mysqli_select_db($conn, "libodb");

    $file_name = "/var/www/html/xls_reports/" . "libobio_covid_name_" . $bdaymonth . ".xlsx";
    $sql = "SELECT cname, sampleid2, fname
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



                $style = $sheet->getStyle('A3:B500');
                $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $style->getBorders()->getAllBorders()->getColor()->setARGB('BLACK');
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');
                $sheet->setCellValue('A1', '麗寶醫事檢驗所');
                $sheet->setCellValue('A2', 'COVID-19 採檢來賓表(' . $bdaymonth . ')');
                $sheet->setCellValue('A3', '客戶姓名(Name)');
                $sheet->setCellValue('B3', '採檢編號(qPCRID)');

                $sheet->getRowDimension(1)->setRowHeight(40);

                $rowCount = $sheet->getHighestRow();
                for ($row1 = 4; $row1 <= $rowCount; $row1++) {
                    $sheet->getRowDimension($row1)->setRowHeight(30);
                }





                // 置中對齊
                $alignment = new Alignment();
                $alignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $alignment->setVertical(Alignment::VERTICAL_CENTER);
                $highestColumn = $sheet->getHighestColumn();

                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(45);
                    $sheet->getStyle($col)->getAlignment()->setHorizontal($alignment->getHorizontal())
                        ->setVertical($alignment->getVertical());
                }
                // $row = -1;
                // $row = $row + 1;

                // 字體大小
                $style = $sheet->getStyle('A1');
                $font = $style->getFont();
                $font->setSize(28);
                $font->setBold(true); //粗體

                $style = $sheet->getStyle('A2');
                $font = $style->getFont();
                $font->setSize(20);
                $font->setBold(true); //粗體

                // 欄位顏色
                $stylecolor1 = $sheet->getStyle('A3');
                $fill = $stylecolor1->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor1->getFont();
                $fontcolor->setSize(20);
                $fontcolor->setColor(new Color(Color::COLOR_WHITE));
                $fontcolor->setBold(true);

                $stylecolor2 = $sheet->getStyle('B3');
                $fill = $stylecolor2->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setARGB('BLACK');
                $fontcolor = $stylecolor2->getFont();
                $fontcolor->setSize(20);
                $fontcolor->setColor(new Color(Color::COLOR_WHITE));
                $fontcolor->setBold(true);

                //跳行顏色
                $rowCount = $sheet->getHighestRow();

                for ($row = 5; $row <= $rowCount; $row += 2) {
                    $range = 'A' . $row . ':' . $sheet->getHighestColumn() . $row;
                    $fill = $sheet->getStyle($range)->getFill();

                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getStartColor()->setRGB('dddddd');
                }





                mysqli_stmt_bind_result(
                    $stmt,
                    $cname,
                    $sampleid2,
                    $fname,

                );


                $row = 3;


                while (mysqli_stmt_fetch($stmt)) {

                    // $replacementcname = 'O';
                    $newCname = mb_substr($cname, 0, 1) . 'O' . mb_substr($cname, 2, mb_strlen($cname) - 2, 'UTF-8');

                    $replacement = '•';
                    $newFname = substr_replace($fname, $replacement, 1, 1);



                    if (!empty($cname)) {
                        $name = $newCname;

                    } else {
                        $name = $newFname;
                    }


                    $PCRid = substr($sampleid2, 7);
                    // $PCRname = substr($name,0,1)."X".substr($name,2);

                    $row = $row + 1;
                    $sheet->setCellValueByColumnAndRow(1, $row, $name);
                    $sheet->setCellValueByColumnAndRow(2, $row, $PCRid);

                    $stylecolor2 = $sheet->getStyle('A4:B500');
                    $fontcolor = $stylecolor2->getFont();
                    $fontcolor->setSize(20);
                    $fontcolor->setBold(true);

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
    <title>客戶預約編號表下載</title>
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
            <h2>客戶預約編號下載</h2>
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