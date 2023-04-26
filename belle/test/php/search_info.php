<?php
// 開始撰寫查詢功能

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
} else {
    header("location: login.php");
}

require_once "whoareU.php";

// Define variables and initialize with empty values
$stmt = $uuid = "";
$testtype = $fpdfflag = $pcrpdfflag = $valid_date = $apdat = $sampleid1_pdf = $sampleid2_pdf = $ftest = $pcrtest = "";
if ($uuid == "") {
    $sampleid1 = $sampleid2 = " ";
    $sampleid1_err = $sampleid2_err = $sampleid_err = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {

    $sampleid1 = trim($_POST["sampleid1"]);
    $sampleid2 = trim($_POST["sampleid2"]);
}

function search_result($sampleid1, $sampleid2)
{
    if (!empty($sampleid1) && empty($sampleid2)) {
        $sql_element = "sampleid1";
        generate_result($sql_element, $sampleid1);
    } else if (!empty($sampleid2) && empty($sampleid1)) {
        $sql_element = "sampleid2";
        generate_result($sql_element, $sampleid2);
    } else {
        $sampleid1_err = "請輸入快篩 ID。";
        $sampleid2_err = "請輸入 PCR ID。";
        echo $sampleid1_err . '<br>';
        echo $sampleid2_err;
    }
};

function generate_result($sql_element, $sampleid)
{

    

    $conn = mysqli_connect("localhost", "libo_user", "xxx");

    mysqli_select_db($conn, "libodb");
    // 基本資訊
    $general_list = array('中文姓名', '身分證字號', '性別', '英文姓名', '出生年月日', '手機', 'E-mail', '住址');

    $sql_general = "SELECT cname, userid, sex, fname, lname, dob, mobile, uemail, address2,address1 from libodb.covid_test 
    WHERE {$sql_element} ='{$sampleid}' limit 1;";

    $result_general = $conn->query($sql_general);

    if ($result_general->num_rows > 0) {

        echo '<table class="table table-hover" style="word-break: break-all;">';
        echo '<thead><tr><th colspan="9" style="text-align:center; color:#556B2F">基本資料</th></tr></thead>';
        echo '<tr>';
        for ($i = 0; $i < count($general_list); $i++) {
            echo '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '</th>';
        }
        echo '</tr>';

        while ($row_general = $result_general->fetch_assoc()) {
            $i = 0;
            foreach ($row_general as $item) {
                if ($i == 3) {
                    $fname = '';
                    $fname = $item;
                }else if ($i==9){
                    $room_number=$item;
                } 
                $i += 1;
            }
            $i = 0;
            echo '<tr>';
            foreach ($row_general as $item) {
                if ($i == 3) {
                } else if ($i == 4) {
                    $ename = $item . ' ' . $fname;
                    echo "<td>{$ename}</td>";
                } else if ($i==8){
                    $address = $item . ' ' . $room_number;
                    echo "<td>{$address}</td>";
                }else if ($i==9){

                }  else {
                    echo "<td>{$item}</td>";
                }
                $i += 1;
            }
        }
        echo '</tr>';
        echo '</table>';
    } else {
        echo "查無此人。";
        $testtype = "";
    }

    // 預約資訊

    $test_list = array('預約日期', '報到日期', '護照號碼', '健保卡號或居留證號', '檢測類型', '快篩 ID', 'PCR ID');

    $sql_test = "SELECT apdat, tdat, passportid, hicardno, testtype,sampleid1, sampleid2 from libodb.covid_test 
    WHERE {$sql_element} ='{$sampleid}' limit 1;";

    $result_test = $conn->query($sql_test);

    if ($result_test->num_rows > 0) {

        echo '<table class="table table-hover" style="word-break: break-all;">';
        echo '<thead><tr><th colspan="9" style="text-align:center; color:#556B2F">預約資訊</th></tr></thead>';
        echo '<tr>';
        for ($i = 0; $i < count($test_list); $i++) {
            echo '<th style="color:#556B2F" id="' . $test_list[$i] . '">' . $test_list[$i] . '</th>';
        }
        echo '</tr>';

        while ($row_test = $result_test->fetch_assoc()) {
            $i = 0;

            echo '<tr>';
            foreach ($row_test as $item) {
                if ($i == 0) {
                    $apdat = date('Y-m-d', strtotime($item));
                    // echo $apdat;
                    echo "<td>{$item}</td>";
                } else if ($i == 1) {
                    $valid_date = date('Y-m-d', strtotime($item));
                    // echo $valid_date;
                    echo "<td>{$item}</td>";
                } else if ($i == 2) {
                    if ($item == "") {
                        $item = "NA";
                    } else {
                        $item = $item;
                    }
                    echo "<td>{$item}</td>";
                } else if ($i == 4) {
                    $testtype = $item;
                    // echo $testtype;
                    echo "<td>{$item}</td>";
                } else if ($i == 5) {
                    $sampleid1_pdf = $item;
                    echo "<td>{$item}</td>";
                } else if ($i == 6) {
                    $sampleid2_pdf = $item;
                    echo "<td>{$item}</td>";
                } else {
                    echo "<td>{$item}</td>";
                }
                $i += 1;
            }
        }
        echo '</tr>';
        echo '</table>';
    } else {
    }

    // 檢驗資訊

    $table_list = array('快篩結果', 'PCR 結果', '檢測醫檢師', '覆核醫檢師', '快篩 pdf ', 'PCR 檢測 pdf ', '快篩 pdf 狀態', 'PCR 檢測 pdf 狀態', '自動判讀結果 (開發中)','國籍');

    $sql = "SELECT ftest, pcrtest, vuser1, vuser2, fpdfflag, pcrpdfflag, frptflag, qrptflag, xlspcrtest2, nationality
    from libodb.covid_test WHERE {$sql_element} ='{$sampleid}' limit 1;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table table-hover" style="word-break: break-all;">';
        echo '<thead><tr><th colspan="9" style="text-align:center;color:#556B2F">檢驗資訊</th></tr></thead>';

        echo '<tr>';
        for ($i = 0; $i < count($table_list)-1; $i++) {
            echo '<th style="color:#556B2F" id="' . $table_list[$i] . '">' . $table_list[$i] . '</th>';
        }
        echo '</tr>';

        while ($row = $result->fetch_assoc()) {
            $i = 0;

            echo '<tr>';
            foreach ($row as $item) {
                if ($i == 0) {
                    $ftest = trim($item);
                    if (trim($item) == "positive") {
                        echo "<td style='color:red;'>{$item}</td>";
                    } else {
                        echo "<td>{$item}</td>";
                    }
                } else if ($i == 1) {
                    $pcrtest = trim($item);
                    if (trim($item) == "positive") {
                        echo "<td style='color:red;'>{$item}</td>";
                    } else {
                        echo "<td>{$item}</td>";
                    }
                } else if ($i == 2) {
                    $item = whoareU($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 3) {
                    $item = whoareU($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 4) {
                    $fpdfflag = trim($item);
                    $item = pdf_StatusCheck($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 5) {
                    $pcrpdfflag = trim($item);
                    $item = pdf_StatusCheck($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 6) {
                    $frptflag = trim($item);
                    $item = StatusCheck($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 7) {
                    $qrptflag = trim($item);
                    $item = StatusCheck($item);
                    echo "<td>{$item}</td>";
                } else if ($i == 8) {
                    $item = ConfirmCheck($item);
                    echo $item;
                } else if ($i==9) {
                    $nationality=$item;
                }else {
                    echo "<td>{$item}</td>";
                }
                $i += 1;
            }
            echo '</tr>';
            echo '</table>';
        }
    } else {
    }

    //判斷 pdf 產生與否
    if ($testtype == "1") {
        if ($fpdfflag == "Y") {
            echo "<br>";
            echo '<h5 style="justify-content: center; display: flex; color:#556B2F">快篩 pdf (寄件檔案中的 pdf 僅保留第一頁)</h5>';
            $subject = "{$sampleid1_pdf}";
            $pattern = "/{$subject}_[0-9a-zA-Z]+.pdf/";
            $files = glob("pdf_reports/{$valid_date}/*.pdf");
            foreach ($files as $filename) {
                preg_match($pattern, $filename, $matches);
                if (!empty($matches)){
                    $pdf_name=$matches[0];
                }
            }
            echo "<div class='container-fluid'><iframe src='pdf_reports/{$valid_date}/{$pdf_name}' width='100%' height='500'></iframe></div>";
            echo '<div style="justify-content: center; display: flex;"><form id="email_ftestpdfreport"><input type="submit" name="send_email_ftest" id="sendftest_email" class="btn btn-success" value="' . $sampleid1_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
        } else {
            echo "快篩 pdf 尚未產生";
            echo "<br>";
        }
    } else if ($testtype == "2") {
        if ($pcrpdfflag == "Y") {
            echo "<br>";
            echo '<h5 style="justify-content: center; display: flex; color:#556B2F">PCR pdf (寄件檔案中的 pdf 僅保留第一頁)</h5>';
            $subject = "{$sampleid2_pdf}";
            $pattern = "/{$subject}_[0-9a-zA-Z]+.pdf/";
            $files = glob("pdf_reports/{$valid_date}/*.pdf");
            foreach ($files as $filename) {
                preg_match($pattern, $filename, $matches);
                if (!empty($matches)){
                    $pdf_name=$matches[0];
                }
            }
            echo "<div class='container-fluid'><iframe src='pdf_reports/{$valid_date}/{$pdf_name}' width='100%' height='500'></iframe></div>";
            echo '<div style="justify-content: center; display: flex;">
            <form id="email_pcrtestpdfreport"><input type="submit" name="send_email_pcrtest" id="sendpcrtest_email" class="btn btn-success" value="' . $sampleid2_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
            if ($nationality!=""){
                echo '<br><h5 style="justify-content: center; display: flex;color:#556B2F">日本入境檢驗證明 尚無寄信功能</h5>';
                echo "<div class='container-fluid'><iframe src='pdf_reports/Japanese_report/{$valid_date}/{$sampleid}_Japanese_report.pdf' width='100%' height='500'></iframe></div>";
                // echo '<div style="justify-content: center; display: flex;"><form action="email_pcrtestpdfreport.ph" method="post"><input type="submit" name="send_email" id="send_email" class="btn btn-success" value="' . $sampleid2_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="../../pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
                echo '<div style="justify-content: center; display: flex;"><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/Japanese_report/'.$valid_date.'/'.$sampleid.'_Japanese_report.pdf" download>Download </a></button></div>';
            }
        } else {
            echo "PCR pdf 尚未產生";
            echo "<br>";
        }
    } else if ($testtype == "3") {
        if ($fpdfflag == "Y") {
            echo "<br>";
            echo '<h5 style="justify-content: center; display: flex;color:#556B2F">快篩 pdf (寄件檔案中的 pdf 僅保留第一頁)</h5>';
            $subject = "{$sampleid1_pdf}";
            $pattern = "/{$subject}_[0-9a-zA-Z]+.pdf/";
            $files = glob("pdf_reports/{$valid_date}/*.pdf");
            foreach ($files as $filename) {
                preg_match($pattern, $filename, $matches);
                if (!empty($matches)){
                    $pdf_name=$matches[0];
                }
            }
            echo "<div class='container-fluid'><iframe src='pdf_reports/{$valid_date}/{$pdf_name}' width='100%' height='500'></iframe></div>";
            echo '<div style="justify-content: center; display: flex;"><form id="email_ftestpdfreport"><input type="submit" name="send_email_ftest" id="sendftest_email" class="btn btn-success" value="' . $sampleid1_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
        } else {
            echo "快篩 pdf 尚未產生";
            echo "<br>";
        }
        if ($pcrpdfflag == "Y") {
            echo "<br>";
            echo '<h5 style="justify-content: center; display: flex;color:#556B2F">PCR pdf (寄件檔案中的 pdf 僅保留第一頁)</h5>';
            $subject = "{$sampleid2_pdf}";
            $pattern = "/{$subject}_[0-9a-zA-Z]+.pdf/";
            $files = glob("pdf_reports/{$valid_date}/*.pdf");
            foreach ($files as $filename) {
                preg_match($pattern, $filename, $matches);
                if (!empty($matches)){
                    $pdf_name=$matches[0];
                }
            }
            echo "<div class='container-fluid'><iframe src='pdf_reports/{$valid_date}/{$pdf_name}' width='100%' height='500'></iframe></div>";
            echo '<div style="justify-content: center; display: flex;">
            <form id="email_pcrtestpdfreport"><input type="submit" name="send_email_pcrtest" id="sendpcrtest_email" class="btn btn-success" value="' . $sampleid2_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
            if ($nationality!=""){
                echo '<br><h5 style="justify-content: center; display: flex;color:#556B2F">日本入境檢驗證明 尚無寄信功能</h5>';
                echo "<div class='container-fluid'><iframe src='pdf_reports/Japanese_report/{$valid_date}/{$sampleid}_Japanese_report.pdf' width='100%' height='500'></iframe></div>";
                // echo '<div style="justify-content: center; display: flex;"><form action="email_pcrtestpdfreport.ph" method="post"><input type="submit" name="send_email" id="send_email" class="btn btn-success" value="' . $sampleid2_pdf . ', Send E-mail"></form><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="../../pdf_reports/'.$valid_date.'/'.$subject.'.pdf" download>Download </a></button></div>';
                echo '<div style="justify-content: center; display: flex;"><button style="margin-left: 5px;" class="btn btn-success" type="button"><a style="color:white;" href="pdf_reports/Japanese_report/'.$valid_date.'/'.$sampleid.'_Japanese_report.pdf" download>Download </a></button></div>';
            }
        } else {
            echo "PCR pdf 尚未產生";
            echo "<br>";
        }
    } else {
    }

    //判斷檢驗狀態 
    if ($testtype == "1") {
        if ($fpdfflag == "Y" && $frptflag != "") {
            echo "<script>";
            echo "document.getElementById('progress_100').removeAttribute('hidden');";
            echo "</script>";
        } else if ($ftest != "" && $fpdfflag != "" && $frptflag == "") {
            echo "<script>";
            echo "document.getElementById('progress_75').removeAttribute('hidden');";
            echo "</script>";
        } else if ($ftest != "" && $fpdfflag == "" && $frptflag == "") {
            echo "<script>";
            echo "document.getElementById('progress_50').removeAttribute('hidden');";
            echo "</script>";
        } else if (($valid_date!="1970-01-01") &&$ftest == "") {
            echo "<script>";
            echo "document.getElementById('progress_25').removeAttribute('hidden');";
            echo "</script>";
        } else {
            echo "<script>";
            echo "document.getElementById('progress_0').removeAttribute('hidden');";
            echo "</script>";
        }
    } else if ($testtype == "2") {
        if ($pcrpdfflag == "Y" && $qrptflag != "") {
            echo "<script>";
            echo "document.getElementById('progress_100').removeAttribute('hidden');";
            echo "</script>";
        } else if ($pcrtest != "" && $pcrpdfflag != "" && $qrptflag == "") {
            echo "<script>";
            echo "document.getElementById('progress_75').removeAttribute('hidden');";
            echo "</script>";
        } else if ($pcrtest != "" && $pcrpdfflag == "" && $qrptflag == "") {
            echo "<script>";
            echo "document.getElementById('progress_50').removeAttribute('hidden');";
            echo "</script>";
        // } else if (($valid_date!="" && $valid_date > date("Y-m-d H:i:s") &&$pcrtest == "") ){
        } else if (($valid_date!="1970-01-01") &&$pcrtest == ""){
            echo "<script>";
            echo "document.getElementById('progress_25').removeAttribute('hidden');";
            echo "</script>";
        } else {
            echo "<script>";
            echo "document.getElementById('progress_0').removeAttribute('hidden');";
            echo "</script>";
        }
    } else if ($testtype == "3") {
        if (($fpdfflag == "Y" && $frptflag != "") && ($pcrpdfflag == "Y" && $qrptflag != "")) {
            echo "<script>";
            echo "document.getElementById('progress_100').removeAttribute('hidden');";
            echo "</script>";
        } else if (($ftest != "" && $fpdfflag != "" && $frptflag == "") || ($pcrtest != "" && $pcrpdfflag != "" && $qrptflag == "")) {
            echo "<script>";
            echo "document.getElementById('progress_75').removeAttribute('hidden');";
            echo "</script>";
        } else if (($ftest != "" && $fpdfflag == "" && $frptflag == "") || ($pcrtest != "" && $pcrpdfflag == "" && $qrptflag == "")) {
            echo "<script>";
            echo "document.getElementById('progress_50').removeAttribute('hidden');";
            echo "</script>";
        } else if ((($valid_date!="1970-01-01") &&$ftest == "") ||(($valid_date!="1970-01-01") &&$pcrtest == "")) {
            echo "<script>";
            echo "document.getElementById('progress_25').removeAttribute('hidden');";
            echo "</script>";
        } else {
            echo "<script>";
            echo "document.getElementById('progress_0').removeAttribute('hidden');";
            echo "</script>";
        }
    } else {
    }
    require_once ("php/log.php");

    $sql_comment=$_SESSION["username"].": ".$sql_general." ".$sql_test." ".$sql;
	write_sql($sql_comment);

    // Close connection
    mysqli_close($conn);
}
