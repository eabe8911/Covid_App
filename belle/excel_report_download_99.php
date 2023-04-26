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

$sql = "select uuid,cname,fname,userid,passportid,hicardno,sex,dob,mobile,uemail,address2 from covid_trans where vuser1<>''";

if ($stmt = mysqli_prepare($conn, $sql)) {

    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'uuid');
            $sheet->setCellValue('B1', '中文名');
            $sheet->setCellValue('C1', '英文名');
            $sheet->setCellValue('D1', '身份證號');
            $sheet->setCellValue('E1', '護照號碼');
            $sheet->setCellValue('F1', '健保卡');
            $sheet->setCellValue('G1', '性別');
            $sheet->setCellValue('H1', '生日');
            $sheet->setCellValue('I1', '手機');
            $sheet->setCellValue('J1', 'Email');
            $sheet->setCellValue('K1', '地址2');

            mysqli_stmt_bind_result(
                $stmt,
                $uuid,
                $cname,
                $fname,
                $userid,
                $passportid,
                $hicardno,
                $sex,
                $dob,
                $mobile,
                $uemail,
                $address2
            );
            $row = 1;

            while (mysqli_stmt_fetch($stmt)) {
                $row = $row + 1;
                

                $sheet->setCellValueByColumnAndRow(1, $row, $uuid);
                $sheet->setCellValueByColumnAndRow(2, $row, $cname);
                $sheet->setCellValueByColumnAndRow(3, $row, $fname);
                $sheet->setCellValueByColumnAndRow(4, $row, $userid);
                $sheet->setCellValueByColumnAndRow(5, $row, $passportid);
                $sheet->setCellValueByColumnAndRow(6, $row, $hicardno);
                $sheet->setCellValueByColumnAndRow(7, $row, $sex);
                $sheet->setCellValueByColumnAndRow(8, $row, $dob);
                $sheet->setCellValueByColumnAndRow(9, $row, $mobile);
                $sheet->setCellValueByColumnAndRow(10, $row, $uemail);
                $sheet->setCellValueByColumnAndRow(11, $row, $address2);
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
                <p><a href="../../download.php?path=<?php echo $file_name ?>">客戶資料下載</a></p>
            </h3>
        </div>

</body>

</html>