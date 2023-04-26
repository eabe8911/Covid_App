<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // $os = array('leslie', "olive", "belle", "admin");
    // if (in_array($_SESSION["username"], $os)) {
    if ($_SESSION["division"]==0) {
    } else {
        echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
        echo '<script language="javascript">window.location.replace("menu.php");</script>';
    }
} else {
    header("location: login.php");
}

$text = "";

function load_text($file_path, $subject, $text)
{
    if (file_exists($file_path)) {
        $fp = fopen($file_path, "r");
        $str = fread($fp, filesize($file_path)); //指定讀取大小，這裡把整個檔案內容讀取出來
        // echo $subject." 的記錄檔:<br>";
        $text = "<div style='color:blue;'>" . $subject . " 的記錄檔:<br><br></div>" . str_replace("\r\n", "<br />", $str);
    } else {
        $text = "查無檔案。";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {

    $subject = trim($_POST["date"]);
    if ($subject == "") {
        $text = "請輸入日期。";
    } else {
        $files = glob("php/log/*.txt");
        $file_path = "";
        foreach ($files as $filename) {
            // if (str_contains($filename, $subject)) {
            if (strpos($filename, $subject) == 8) {
                $file_path = $filename;
            }
            if ($file_path != "" || file_exists($file_path)) {
                $fp = fopen($file_path, "r");
                $str = fread($fp, filesize($file_path)); //指定讀取大小，這裡把整個檔案內容讀取出來
                $text = "<div style='color:blue;'>" . $subject . " 的記錄檔:<br><br></div>" . str_replace("\r\n", "<br />", $str);
            } else {
                $text = "查無檔案。";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL 紀錄查詢</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/menu.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/checkin_modified.css">
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

    <div class="col py-3" style="padding-left:15px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline;">
            <div class="col-6 col-sm-3">
                <label>請輸入欲查詢的日期</label>
                <input type="date" name="date" class="form-control" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $date; ?>">
            </div>
            <br>
            <div class="col-auto">
                <input type="submit" name="search" class="btn btn-success" value="搜尋">
                <input type="submit" name="clear" class="btn btn-success" value="清除">
            </div>
        </form>
        <br>
        <div name="log_record"><?php echo $text; ?></div>
    </div>
</body>

</html>