<?php
// Initialize the session
// For libo covid 06/07/21 WillieK
// debug on
ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    if (($_SESSION["division"] == 0) || ($_SESSION["division"] == 1)) {
    } else {
        echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
        echo '<script language="javascript">window.location.replace("menu.php");</script>';
    }
} else {
    header("location: login.php");
}

require_once "download_zip.php"

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上傳/下載資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/generate_report.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function() {
            $("#nav").load("nav.html");
        });
    </script>
</head>
<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('#importfile').submit(function(e) {
            e.preventDefault();
            // var formData = $('#file').prop('files')[0];
            // var formData = new FormData();
            // console.log(formData);
            $.ajax({
                url: 'import_file1.php',
                data: new FormData(this),
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(msg) {
                    console.log(msg);
                    // let checkdata = msg['checkdata'];
                    // document.getElementById('table').innerHTML = checkdata;
                    let responsetext = msg['text'];
                    document.getElementById("responsetext").innerHTML = responsetext;
                    // let dateinputresult = msg['dateinputresult'];
                    // document.getElementById('text').innerHTML = dateinputresult;
                },
                error: function() {
                    console.log('error')
                }
            });
        });
    });
</script> -->

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0" style="background-color:#ffffe6;">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <div></div>
                <div>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁">
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁">
                    <!-- <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版目錄"> -->
                </div>
            </div>
        </div>
        <div class="col py-3" style="margin:1em;">

            <!-- <h3 style="color:#008000;"> Step 1. 請先下載檢查程式執行 MS Form 問卷資料 EXCEL 檢查 。</h3>
            <div>
                <a class="btn btn-success" href="excel2sql_check_v3.exe">下載</a>
                <br> <br> <br>
                <p>使用說明與注意事項
                <ul>
                    <li>MS Form 問卷資料檔案格式僅能使用 "xlsx"，不可使用 csv。</li>
                    <li>MS Form 問卷資料檔名請命名為 "麗寶醫事檢驗所-Covid19 自費檢測服務預約系統 1"</li>
                    <li>請將 MS Form 問卷資料檔案與 exe 程式放於同一位置 (同一資料夾或直接放置於桌面)</li>
                    <li>點擊兩下 exe 程式即可自動開啟檔案於桌面進行資料檢查 (若無法自動開啟檔案請確認檔案名稱、格式是否符合前兩項之條件)</li>
                    <li>資料有疑問、需確認的欄位會以黃底標示</li>
                </ul>
                </p>
            </div>

            <br><br><br> -->


            <h3 style="color:#008000;"> Step 1-1. 請將確認後的 Win Form 預約資料，另存 CSV 後上傳。</h3>
            <div>
                <form method="post" action="import_file_winform.php" enctype="multipart/form-data" target="_blank">
                    <input type="file" name="file" />
                    <input type="submit" class="btn btn-success" name="submit_file" value="提交" />
                </form>
            </div>
            <!-- <div id="responsetext"></div> -->

            <br><br><br>

            <h3 style="color:#008000;"> Step 1-2. 請將診所檔案，另存 CSV 後上傳。</h3>
            <div>
                <form method="post" action="import_file_clinic.php" enctype="multipart/form-data" target="_blank">
                    <input type="file" name="file" />
                    <input type="submit" class="btn btn-success" name="submit_file" value="提交" />
                </form>
            </div>
            <!-- <div id="responsetext"></div> -->

            <br><br><br>

            <h3 style="color:#008000;"> Step 1-3. 請將李天鐸檔案，另存 CSV 後上傳。</h3>
            <div>
                <!-- <div class="col-6 col-sm-3">
                        <label>請輸入欲檢測的日期</label>
                        <input type="date" name="ysred_date" class="form-control" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $date; ?>">
                    </div>
                    <br> -->
                <form method="post" action="import_file_ysred.php" enctype="multipart/form-data" target="_blank">
                    <input type="file" name="file" />
                    <input type="submit" class="btn btn-success" name="submit_file" value="提交" />
                </form>
            </div>
            <!-- <div id="responsetext"></div> -->

            <br><br><br>


            <!-- <h3> 防疫旅館資料 excel 檔上傳。</h3>
            <div>
                <form method="post" action="import_excel.php" enctype="multipart/form-data">
                    <input type="file" name="excel_file" />
                    <div>
                        <label>請輸入要上傳的 excel 為第幾頁 (預設第一張表單 (數字 1) ，若為第二張表單請輸入數字 2，依此類推)</label>
                        <br>
                        <input type="text" id="page" name="page" value="<?php $page = "";
                                                                        echo $page; ?>">
                        <input type="submit" class="btn btn-success" name="submit_excel" value="提交" />
                    </div>
                </form>
            </div> -->
            <hr>
            <!-- <h3 style="color:#4B0082;">防疫旅館檢驗 MS Form EXCEL TEMPLATE 檔下載。</h3>
            <a class="btn btn-success" href="麗寶醫事檢驗所-Covid19 自費檢測服務預約系統 1.xlsx">下載</a>
            <br><br><br>
            <p>使用說明與注意事項
            <ul>
                <li>點選上面連結下載 MS Form EXCEL 模板</li>
                <li>EXCEL TEMPLATE 檔欄位不可擅自增減或更動</li>
                <li>橘色欄位表示需填寫</li>
                <li>填寫範例請見各欄位註解</li>
                <li>資料填完後再以檢查程式覆驗資料填寫是否有誤，後續程序請見上方 Step 1. </li>
            </ul>
            </p> -->
            <br>
            <h3 style="color:#4B0082;">防疫旅館檢驗 zip 檔下載。</h3>
            <div>
                <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" style="display:inline;">
                    <div class="col-6 col-sm-3">
                        <label>請輸入欲下載壓縮檔的日期</label>
                        <input type="date" name="zipfile_date" class="form-control" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $date; ?>">
                    </div>
                    <br>
                    <div class="col-auto">
                        <input type="submit" name="search" class="btn btn-success" value="搜尋">
                        <input type="submit" name="clear" class="btn btn-success" value="清除">
                    </div>
                </form>
                <br>
                <div name="zip_file_download"><?php echo $today; ?></div>
            </div>
        </div>
    </div>
</body>

</html>