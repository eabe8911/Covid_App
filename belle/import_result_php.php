<?php
require_once "php/log.php";

function check($vuser)
{
    if (isset($_POST["submit_file"])) {
        $vuser = strtoupper(trim($_POST["vuser"]));
        $os = array("admin","olive","iris","dick","mike","sophia","allen","weichih","ivan","leslie","belle");
        $os1 = array("LSC2665!","OLIVE","A225558000","DICK","B122408253","D221222459","G122100926","H123160258","P124237860","LESLIE","BELLE");
        $account = $_SESSION["username"];
        $key = array_search($account, $os);
        if ($vuser != $os1[$key]) {
        // if ($vuser != $_SESSION["confirm_pw"]) {
            echo '<script language="javascript">alert("使用者錯誤，上傳終止。")</script>';
        } else {
            echo "準備上傳<br>";
            send_file();
        }
    }
}

function send_file()
{

    $target_dir = "../pcrxls/upload/";

    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    // echo $target_file . "<br>";
    $uploadOk = 1;
    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // echo $FileType . "<br>";
    // echo $_FILES["file"]["tmp_name"] . "<br>";

    // Allow certain file formats
    if (($FileType != "xlsx") && ($FileType != "xls")) {
        $message1 = "因上傳格式不符。";
        $message = "檔案上傳失敗。" . $message1;
        echo '<script language="javascript">alert("' . $message . '")</script>';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "檔案上傳終止。";
        echo '<script language="javascript">alert("' . $message . '")</script>';
        // if everything is ok, try to upload file
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        $message1 = "檔案 " . htmlspecialchars(basename($_FILES["file"]["name"])) . " 已上傳。<br>";
        $sql_comment=$_SESSION["username"].": ".$message1;
        write_sql($sql_comment);
        echo $message1;
        $path = "/var/www/html/pcrxls/upload/" . basename($_FILES["file"]["name"]);
        call_python("import_file", $path);
    }
}

function StatusCheck($item)
{
    if ($item == "C") {
        $Status = "已覆核";
        return $Status;
    } else if ($item == "Y") {
        $Status = "已寄出";
        return $Status;
    } else if ($item == "S") {
        $Status = "不需操作";
        return $Status;
    } else if ($item == "N") {
        $Status = "尚無資料";
        return $Status;
    } else {
        return $item;
    }
}

function ConfirmCheck($item)
{
    if ($item == "Y") {
        $Status = "<td>結果一致</td>";
        return $Status;
    } else if ($item == "N") {
        $Status = "<td style='color:red;'>不符合</td>";
        return $Status;
    } else {
        $Status = "<td>{$item}</td>";
        return $Status;
    }
}

function upload_vuser($sampleid)
{
    $vuser = $_POST["vuser"];

    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $sampleid = strval($sampleid);

    $sql_general1 = "SELECT uuid,vuser1,vuser2,xlspcrtest2 from libodb.covid_trans 
    WHERE sampleid2 ='{$sampleid}' limit 1;";

    $result_general1 = $conn->query($sql_general1);
    // $result->fetch_assoc();
    while ($row_general1 = $result_general1->fetch_assoc()) {
        $uuid = $row_general1["uuid"];
        $vuser1 = $row_general1["vuser1"];
        $vuser2 = $row_general1["vuser2"];
        $xlspcrtest2 = $row_general1["xlspcrtest2"];

        $sql2 = "update covid_trans set vuser1=?,vuser2=?,qrptflag=? where uuid=?";
        if ($stmt2 = mysqli_prepare($conn, $sql2)) {
            //echo "New record created successfully";

            //$count = $count +1;
            mysqli_stmt_bind_param($stmt2, "sssi", $p1, $p2, $p3, $p4);
            // Set parameters
            if ($vuser1 == "" && $vuser2 == "") {
                $p1 = $vuser;
                $p2 = "";
                $p3 = "";
                $p4 = $uuid;
            } else if ($vuser1 != "" && $vuser1!=$vuser) {
                $p1 = $vuser1;
                $p2 = $vuser;
                if ($xlspcrtest2 == "Y") {
                    $p3 = "C";
                } else {
                    $p3 = "";
                }
                $p4 = $uuid;
            // } else {
            //     $p1 = $vuser1;
            //     $p2 = $vuser;
            //     if ($xlspcrtest2 == "Y") {
            //         $p3 = "C";
            //         echo "Y+C";
            //     } else {
            //         $p3 = "";
            //     }
            //     $p4 = $uuid;
            //     echo 'no empty!<br>';
            }
            $sql_comment=$_SESSION["username"].": update covid_trans set vuser1={$p1},vuser2={$p2},qrptflag={$p3} where uuid={$p4}";
            write_sql($sql_comment);
        }
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt2)) {
            //echo "<h1 style="background-color:hsla(9, 100%, 64%, 0.5);">";
            echo "<h5>";
            echo "判讀結果存檔成功!";
            echo "</h5>";
            $sql_comment="判讀結果存檔成功!";
            write_sql($sql_comment);
        } else {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
            $sql_comment="Error: " . $sql2 . "<br>" . mysqli_error($conn);
            write_sql($sql_comment);
        }
        // Close statement
        mysqli_stmt_close($stmt2);
    }
    mysqli_close($conn);
}


function generate_result($sampleid)
{

    $conn = mysqli_connect("localhost", "libo_user", "xxx");

    mysqli_select_db($conn, "libodb");

    $general_list = array('PCR 結果', '報告輸入時間', '自動判讀結果', 'PCR 檢測 pdf 狀態');

    $sql_general = "SELECT pcrtest,rdat,xlspcrtest2,qrptflag from libodb.covid_trans 
    WHERE sampleid2 ='{$sampleid}' limit 1;";

    $result_general = $conn->query($sql_general);

    if ($result_general->num_rows > 0) {

        echo '<table class="table table-hover" style="word-break: break-all;">';
        echo '<thead><tr><th colspan="9" style="text-align:center; color:#556B2F"></th></tr></thead>';
        echo '<tr>';
        for ($i = 0; $i < count($general_list); $i++) {
            echo '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '</th>';
        }
        echo '</tr>';

        while ($row_general = $result_general->fetch_assoc()) {
            $i = 0;
            echo '<tr>';
            foreach ($row_general as $item) {
                if ($i == 0) {
                    $item = strtolower(trim($item));
                    if (trim($item) != "negative") {
                        echo "<td style='color:red;'>{$item}</td>";
                    } else {
                        echo "<td>{$item}</td>";
                    }
                } else if ($i == 2) {
                    $item = ConfirmCheck($item);
                    echo $item;
                } else if ($i == 3) {
                    $item = StatusCheck($item);
                    echo "<td>{$item}</td>";
                } else {
                    echo "<td>{$item}</td>";
                }
                $i += 1;
            }
            echo '</tr>';
            echo '</table>';

            $sql_comment=$_SESSION["username"].": SELECT pcrtest={$row_general["pcrtest"]},rdat={$row_general["rdat"]},xlspcrtest2={$row_general["xlspcrtest2"]},qrptflag={$row_general["qrptflag"]} from libodb.covid_trans 
            WHERE sampleid2 ='{$sampleid}' limit 1;";
            write_sql($sql_comment);
        }
    } else {
        echo "查無此人。";
    }
    // Close connection
    mysqli_close($conn);
}

function call_python($arg, $arg2)
{
    exec('python3 "import_result_py.py" "' . $arg . '" "' . $arg2 . '"', $output, $return_var);

    // echo "return value is: $return_var" . "<br>";
    // print_r($output);
    if($return_var!=0){
        echo "<h5>部分資料上傳失敗，請重新檢查檔案。</h5>";
    }
    
    foreach ($output as $line) {
        $i = 0;
        $array = explode(" ", $line);
        foreach ($array as $item) {
            if ($i == 1) {
                $sampleid = $item;
            } else {
            }
            $i = $i + 1;
        }
        upload_vuser($sampleid);
        echo '<p style="text-align:center; color:#556B2F">PCR ID: ' . $sampleid . '</p>';
        generate_result($sampleid);
    }
}
