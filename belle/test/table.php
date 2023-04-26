<?php
require_once 'php/table_output.php';

if (!isset($_SESSION)) {
    session_start();
}

date_default_timezone_set("Asia/Taipei");
$today = getdate();
date("Y-m-d H:i:s");  //日期格式化
$year = date("Y", strtotime("first day of previous month"));
$month = date("m", strtotime("first day of previous month"));
$day = $today["mday"];  //日
$startdate = $year . "-" . $month . "-" . $day;
$date1 = new DateTime($year . "-" . $month . "-" . $day . " 00:00:00");
$searchdate = $date1->format("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <span class="card-header" style="width: 18rem;display:inline-block;">
        <h5 style="color:#006400;">網頁使用說明連結: </h5>

        <button class="btn btn-light" type="button">
            <a href="https://libobio0.sharepoint.com/:b:/s/msteams_33ef86-99-/EX0pOKnqUoJCvsmThNwFM-cBLxg70AGgltAlLR_z1fL-PQ?e=tBbxfF" target="_blank">前往連結 (版本: 2022/01/01)</a>
        </button>
    </span>
    <span class="card-header" id="headingOne" style="width: 18rem;display:inline-block;">
        <h5 style="color:#006400;">待完成檢體</h5>
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#table" aria-expanded="false" aria-controls="table">
            顯示待完成檢體
        </button>
    </span>
    <span class="card-header" id="headingTwo" style="width: 18rem;display:inline-block;">
        <h5 style="color:#006400;">已完成檢體</h5>
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#table1" aria-expanded="false" aria-controls="table1">
            顯示已完成檢體
        </button>
    </span>
    <!-- <div class="card-header" style="width: 18rem;">
        <h5 style="color:#006400;">網頁使用說明連結: </h5>
        <button class="btn btn-light" type="button">
            <a href="https://libobio0.sharepoint.com/:b:/s/msteams_33ef86-99-/Eb0eJoJvCKlArg2z-UHUxoYBTRbFV2sl-VbVz-AgXgVqVA?e=dT3uCm" target="_blank">前往連結 (版本: 2021/12/10)</a>
        </button>
    </div>
    <div class="card-header" id="headingOne" style="width: 18rem;">
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#table" aria-expanded="false" aria-controls="table">
            待完成檢體
        </button>
    </div> -->

    <div id="table" class="collapse" aria-labelledby="headingOne">
        <div class="card card-body">
            <h5 style="color:#006400;">待完成檢體列</h5>
            <?php
            echo "<div>顯示範圍: {$startdate} 迄今</div>";
            $table_list = array('SQL_ID', '中文姓名', '英文姓名', '身分證字號', '檢測類型', '快篩 ID', 'PCR ID', '預約日期', '報到日期', '快篩結果', 'PCR 結果', '快篩 pdf ', 'PCR 檢測 pdf ', '快篩 pdf 狀態', 'PCR 檢測 pdf 狀態');
            search_table3("SELECT uuid, cname, fname, userid, testtype,sampleid1, sampleid2, apdat, tdat, ftest, pcrtest, fpdfflag, pcrpdfflag, frptflag, qrptflag from libodb.covid_test
        where tdat > '{$searchdate}' and ((testtype='1' and (fpdfflag='' or frptflag='')) or (testtype='2' and (pcrpdfflag='' or qrptflag='')) or (testtype='3' and ((fpdfflag='' or frptflag='') or (pcrpdfflag='' or qrptflag='')))) ORDER by (apdat);", $table_list);
            ?>
        </div>
    </div>
    <!-- <div class="card-header" id="headingTwo" style="width: 18rem;">
        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#table1" aria-expanded="false" aria-controls="table1">
            已完成檢體
        </button>
    </div> -->
    <div id="table1" class="collapse" aria-labelledby="headingTwo">
        <div class="card card-body">
            <h5 style="color:#006400;">已完成檢體列</h5>
            <?php
            echo "<div>顯示範圍: {$startdate} 迄今</div>";
            $table_list = array('SQL_ID', '中文姓名', '英文姓名', '身分證字號', '檢測類型', '快篩 ID', 'PCR ID', '預約日期', '報到日期', '快篩結果', 'PCR 結果', '快篩 pdf ', 'PCR 檢測 pdf ', '快篩 pdf 狀態', 'PCR 檢測 pdf 狀態', 'pdf 連結');
            search_table4("SELECT uuid, cname, fname, userid, testtype,sampleid1, sampleid2, apdat, tdat, ftest, pcrtest, fpdfflag, pcrpdfflag, frptflag, qrptflag from libodb.covid_test
        where tdat > '{$searchdate}' and ((testtype='1' and ftest !='' and (fpdfflag!='' or frptflag!='')) or (testtype='2' and pcrtest !='' and (pcrpdfflag!='' or qrptflag!='')) or (testtype='3' and (ftest !=''and (fpdfflag!='' or frptflag!='') or (pcrtest !='' and(pcrpdfflag!='' or qrptflag!='')))))ORDER by (tdat) DESC ;", $table_list);
            ?>
        </div>
    </div>
</body>
<script>
    var el = document.getElementById("text");
    var text = el.textContent || el.innerText;
    var ell = document.getElementById("text1");
    var text1 = document.createTextNode(text);
    ell.appendChild(text1);
    d3.selectAll("tr").style("font-size", "13px");
    d3.selectAll("td").style("font-size", "13px");
</script>

</html>