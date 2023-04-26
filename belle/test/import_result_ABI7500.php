<?php

header('Content-Type: application/json');

require_once("php/log.php");

$loginMT = $_POST['MT_ajax'];

if (isset($_POST['function'])) {
    if ($_POST['function'] == 'uploadfile') {
        $logtext = "";
        $responsetext = "";

        $target_dir = "php/log/";

        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        if (($FileType != "xlsx") && ($FileType != "xls")) {
            $responsetext = $responsetext . "因上傳格式不符，檔案上傳失敗。";
            $logtext = $logtext . "因上傳格式不符，檔案上傳失敗。";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk != 0) {
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            $responsetext = $responsetext . "檔案 " . htmlspecialchars(basename($_FILES["file"]["name"])) . " 已上傳。<br>";
            $logtext = $logtext . "檔案 " . htmlspecialchars(basename($_FILES["file"]["name"])) . " 已上傳。<br>";

            // $path = "/var/www/html/pcrxls/upload/" . basename($_FILES["file"]["name"]);
            $path = "/var/www/html/belle/test/php/log/" . basename($_FILES["file"]["name"]);

            $arg = "import_file";
            $arg2 = $path;

            exec('python3 "check_result.py" "' . $arg . '" "' . $arg2 . '"', $output, $return_var);

            $QCarray = array();
            $samplearray = array();
            $resultarray = array();
            $resultdemoarray = array('error', 'positive', 'inconclusive', 'negative', 'invalid');

            for ($j = 0; $j < count($output); $j++) {
                if ($output[$j] == "PC_and_NTC_PASS" || $output[$j] == "QC_Failure") {
                    array_push($QCarray, $output[$j]);
                } else if ($output[$j][0] == "Q") {
                    array_push($samplearray, $output[$j]);
                } else if (in_array($output[$j], $resultdemoarray)) {
                    array_push($resultarray, $output[$j]);
                }
            }
            $responsetext = $responsetext . uploadresult($QCarray, $samplearray, $resultarray);

            $outputlog = implode(',', $output);
            // print_r($output);

            $logtext = $logtext . $outputlog;
        }

        echo json_encode(array(
            'loginMT' => $loginMT,
            'responsetext' => $responsetext,
        ));
        $sql_comment = $loginMT . $logtext;
        write_sql($sql_comment);

    }else if ($_POST['function'] == 'sendresult') {
        $logtext2 = "";
        $responsetext = "";
        $text2 = "";
        $checknumber = 0;
        $user = strtoupper(trim($_POST["user"]));
        $vuser1_ajax = strtoupper(trim($_POST['vuser1_ajax']));
        $vuser2_ajax = strtoupper(trim($_POST['vuser2_ajax']));
        $nameArr = json_decode($_POST["name"]);
        $pcrresultArr = json_decode($_POST["pcrresult"]);
        $rdatresultArr = json_decode($_POST["rdatresult"]);
        $vuser1resultArr = json_decode($_POST["vuser1result"]);
        $vuser2resultArr = json_decode($_POST["vuser2result"]);
        $nameforlog = implode(',', $nameArr);
        $pcrforlog = implode(',', $pcrresultArr);
        $rdatforlog = implode(',', $rdatresultArr);
        $os1 = array('LESLIE', 'H123160258', 'P222717661', 'A225558000', 'N225198185', 'P124237860', 'N123478768', 'B122408253', "OLIVE", "BELLE", "LSC2665!", 'D221222459', "G122100926");
        $os2 = array('H123160258', 'P222717661', 'A225558000', 'N225198185', 'P124237860', 'N123478768', 'B122408253', 'D221222459');
        $confirm_array = array();

        // 檢查上傳者程序
        $logtext2 = $logtext2 . "上傳資料: " . "登入者: " . $user . "檢測醫檢師: " . $vuser1_ajax . "覆核醫檢師: " . $vuser2_ajax .
            "檢體列: " . $nameforlog . "檢驗結果列: " . $pcrforlog . "報告時間列: " . $rdatforlog;
        if ($user != $vuser1_ajax && $user != $vuser2_ajax) {
            $responsetext = "檢測醫檢師及覆核醫檢師皆不是登入者，上傳終止";
            $logtext2 = $logtext2 . "檢測醫檢師及覆核醫檢師皆不是登入者，上傳終止";
            $checknumber = 1;
        } else if ($vuser1_ajax == ""  || $vuser2_ajax == "") {
            $responsetext = "檢測醫檢師或是覆核醫檢師未輸入，上傳終止。";
            $logtext2 = $logtext2 . "檢測醫檢師或是覆核醫檢師未輸入，上傳終止。";
            $checknumber = 1;
        } else if ($vuser1_ajax == $vuser2_ajax) {
            $responsetext = "檢測醫檢師和覆核醫檢師資料相同，上傳終止。";
            $logtext2 = $logtext2 . "檢測醫檢師和覆核醫檢師資料相同，上傳終止。";
            $checknumber = 1;
        } else {

            $checkpoint = 0;
            if (in_array($vuser1_ajax, $os1) && in_array($vuser2_ajax, $os2)) {
                $checkpoint = 0;
            } else if (in_array($vuser1_ajax, $os1)) {
                $responsetext = $responsetext . "覆核醫檢師尚未有簽名檔，報告無法產出，上傳終止。\n";
                $logtext2 = $logtext2 . "覆核醫檢師尚未有簽名檔，報告無法產出，上傳終止。";
                $checkpoint = 1;
                $checknumber = 1;
            } else {
                $responsetext = $responsetext . "覆核醫檢師尚未有簽名檔，報告無法產出或資料庫中無此檢測醫檢師，上傳終止\n";
                $logtext2 = $logtext2 . "覆核醫檢師尚未有簽名檔，報告無法產出或資料庫中無此檢測醫檢師，上傳終止。";
                $checkpoint = 1;
                $checknumber = 1;
            }

            if ($checkpoint == 0) {
                // 檢查完成，開始上傳
                // Connect to local db
                $conn = mysqli_connect("localhost", "libo_user", "xxx");
                mysqli_select_db($conn, "libodb");

                if (mysqli_connect_errno()) {
                    $responsetext = $responsetext . "Failed to connect to MySQL: " . mysqli_connect_error();
                    $logtext2 = $logtext2 . "Failed to connect to MySQL: " . mysqli_connect_error();
                } else {
                    $sqlcheckpoint = 0;
                    $uuidArr = array();
                    for ($i = 0; $i < count($nameArr); $i++) {
                        if (($nameArr[$i] != "")) { /*not allowing empty values and the row which has been removed.*/
                            $sql = "SELECT uuid, pcrtest, sampleid2, tdat, rdat, qrptflag, testtype FROM covid_test WHERE 1=1 and sampleid2='{$nameArr[$i]}'";
                            $logtext2 = $logtext2 . $sql;

                            if (!mysqli_query($conn, $sql)) {
                                die($logtext2 = $logtext2 .'Error: ' . mysqli_error($conn));
                                $checknumber = 1;
                            }

                            $result_general = $conn->query($sql);
                            if ($result_general->num_rows > 0) {
                                while ($row_general = $result_general->fetch_assoc()) {
                                    $a = 0;
                                    $barcode = "";
                                    foreach ($row_general as $item) {
                                        if ($a == 0) {
                                            array_push($uuidArr, $item);
                                        } else if ($a == 1) {
                                            // if ($item == "negative" || $item == "positive") {
                                            //     $responsetext = $responsetext . "這個人已經有結果了~~~。\n";
                                            // }
                                        } else if ($a == 2) {
                                            $barcode = $item;
                                        } else if ($a == 3) {
                                            if ($item == NULL) {
                                                $responsetext = $responsetext . $barcode . " 此檢體編號並無報到資料。\n";
                                                $logtext2 = $logtext2 . $barcode . " 此檢體編號並無報到資料。";
                                                $sqlcheckpoint = 1;
                                                $checknumber = 1;
                                            }
                                        } else if ($a == 5) {
                                            if ($item == 'C' || $item == 'Y') {
                                                $responsetext = $responsetext . $barcode . " 此檢體編號檢驗結果已發出。\n";
                                                $logtext2 = $logtext2 . $barcode . " 此檢體編號檢驗結果已發出。";
                                                $sqlcheckpoint = 2;
                                                $checknumber = 1;
                                                array_push($confirm_array, 1);
                                            } else {
                                                array_push($confirm_array, 0);
                                            }
                                        } else if ($a == 6) {
                                            if ($item == '1') {
                                                $responsetext = $responsetext . $barcode . " 此檢體編號並無操作 PCR。\n";
                                                $logtext2 = $logtext2 . $barcode . " 此檢體編號並無操作 PCR。";
                                                $sqlcheckpoint = 1;
                                                $checknumber = 1;
                                            }
                                        }
                                        $a += 1;
                                    }
                                }
                            } else {
                                array_push($uuidArr, "");
                                array_push($confirm_array, 1);
                                $responsetext = $responsetext .$nameArr[$i]. "查無此人。\n";
                                $logtext2 = $logtext2 . $nameArr[$i]." 查無此人。";
                                $sqlcheckpoint = 1;
                                $checknumber = 2;
                            }
                        }
                    }
                    if ($sqlcheckpoint == 0) {
                        $responsetext = $responsetext . "檢查SQL資料庫成功，開始更新。\n";
                        $logtext2 = $logtext2 . " 檢查SQL資料庫成功，開始更新。";

                        for ($i = 0; $i < count($nameArr); $i++) {
                            if (($nameArr[$i] != "")) { /*not allowing empty values and the row which has been removed.*/
                                $sql1 = "update covid_test set vuser1= '{$vuser1resultArr[$i]}', vuser2= '{$vuser2resultArr[$i]}',qrptflag='C', pcrtest='{$pcrresultArr[$i]}', rdat='{$rdatresultArr[$i]}' WHERE uuid='{$uuidArr[$i]}';";
                                $logtext2 = $logtext2 . $sql1;

                                if (!mysqli_query($conn, $sql1)) {
                                    die($logtext2 = $logtext2 . 'Error: ' . mysqli_error($conn));
                                    $checknumber = 1;
                                }
                            }
                        }
                    } else if ($sqlcheckpoint == 2) {
                        $responsetext = $responsetext . "進入覆核程序。\n";
                        $logtext2 = $logtext2 . "進入覆核程序。";
                        $general_list = array("檢體編號", 'PCR 結果', '報告輸入時間', '檢測醫檢師', '覆核醫檢師', " ", 'uuid');
                        $text2 = $text2 . '<table class="table table-hover" style="word-break: break-all"; id="table2" name="table2">';
                        $text2 = $text2 . '<thead><tr><th colspan="9" style="text-align:center; color:#556B2F"></th></tr></thead>';
                        $text2 = $text2 . '<tr>';
                        for ($i = 0; $i < count($general_list); $i++) {
                            if ($i == 1) {
                                $text2 = $text2 . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br></th>';
                            } else if ($i == 3) {
                                $text2 = $text2 . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br></th>';
                            } else if ($i == 4) {
                                $text2 = $text2 . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br></th>';
                            } else if ($i == 6) {
                                $text2 = $text2 . '<th style="color:#556B2F;font-size:0px;" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br></th>';
                            } else {
                                $text2 = $text2 . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '</th>';
                            }
                        }
                        $text2 = $text2 . '</tr>';
                        for ($j = 0; $j < count($nameArr); $j++) {
                            $sampleid = $nameArr[$j];
                            if ($confirm_array[$j] == 1) {
                                $text2 = $text2 . "<tr id='{$sampleid}'>";
                                $text2 = $text2 . "<td style='background-color:yellow;' >{$sampleid}</td>";
                                if ($pcrresultArr[$j] == "negative") {
                                    $text2 = $text2 . "<td style='background-color:yellow;' class='pcrtest{$sampleid}_2'><input type='text' style='color:black;' id='pcrtest{$sampleid}_2' value='{$pcrresultArr[$j]}' disabled></td>";
                                } else {
                                    $text2 = $text2 . "<td style='background-color:yellow;' class='pcrtest{$sampleid}_2'><input type='text' style='color:red;' id='pcrtest{$sampleid}_2' value='{$pcrresultArr[$j]}' disabled></td>";
                                }
                                $text2 = $text2 . "<td style='background-color:yellow;' class='rdat{$sampleid}_2'><input type='text' class='rdat_2' disabled></td>";
                                $text2 = $text2 . "<td style='background-color:yellow;' class='vuser1{$sampleid}_2'><input type='text' class='vuser1_text' disabled value='{$vuser1resultArr[$j]}'></td>";
                                $text2 = $text2 . "<td style='background-color:yellow;' class='vuser2{$sampleid}_2'><input type='text' class='vuser2_text' disabled value='{$vuser2resultArr[$j]}'></td>";
                                $text2 = $text2 . "<td style='background-color:yellow;' width='100px'><a href='javascript:void(0);' class='remCF' onclick='remove(this)'>刪除本列</a></td>";
                                $text2 = $text2 . "<td style='background-color:yellow;width:0px;' class='uuid{$sampleid}_2'><input style='background-color:yellow;width:0px;' type='hidden' id='uuid{$sampleid}_2' disabled value='{$uuidArr[$j]}'></td>";
                            } else {
                                $text2 = $text2 . "<tr id='{$sampleid}'>";
                                $text2 = $text2 . "<td style='color:blue;'>{$sampleid}</td>";
                                if ($pcrresultArr[$j] == "negative") {
                                    $text2 = $text2 . "<td class='pcrtest{$sampleid}_2'><input type='text' style='color:black;' id='pcrtest{$sampleid}_2' value='{$pcrresultArr[$j]}' disabled></td>";
                                } else {
                                    $text2 = $text2 . "<td class='pcrtest{$sampleid}_2'><input type='text' style='color:red;' id='pcrtest{$sampleid}_2' value='{$pcrresultArr[$j]}' disabled></td>";
                                }
                                $text2 = $text2 . "<td class='rdat{$sampleid}_2'><input type='text' class='rdat_2' disabled></td>";
                                $text2 = $text2 . "<td class='vuser1{$sampleid}_2'><input type='text' class='vuser1_text' disabled value='{$vuser1resultArr[$j]}'></td>";
                                $text2 = $text2 . "<td class='vuser2{$sampleid}_2'><input type='text' class='vuser2_text' disabled value='{$vuser2resultArr[$j]}'></td>";
                                $text2 = $text2 . "<td width='100px'><a href='javascript:void(0);' class='remCF' onclick='remove(this)'>刪除本列</a></td>";
                                $text2 = $text2 . "<td class='uuid{$sampleid}_2' style='width:0px;'><input style='width:0px;' type='hidden' id='uuid{$sampleid}_2' disabled value='{$uuidArr[$j]}'></td>";
                            }
                        }

                        $text2 = $text2 . '</tr>';
                        $text2 = $text2 . '</table>';
                    } else {
                        $responsetext = $responsetext . "發生錯誤，上傳失敗\n";
                        $logtext2 = $logtext2 . "發生錯誤，上傳失敗";
                        $checknumber = 1;
                    }
                }
                mysqli_close($conn);
                // 上傳完成
            }
        }
        if ($text2 != "") {
            echo json_encode(array(
                'text2' => $text2,
                'responsetext' => $responsetext,
                'checknumber' => $checknumber,
            ));
        } else {
            echo json_encode(array(
                'responsetext' => $responsetext,
                'checknumber' => $checknumber,
            ));
        }
        $sql_comment = $logtext2;
        write_sql($sql_comment);

    } else if ($_POST['function'] == 'sendconfirmresult') {
        $logtext3="";
        $responsetext2 = "";
        $nameArr = json_decode($_POST["name"]);
        $uuidresultArr = json_decode($_POST["uuidresult"]);
        $pcrresultArr = json_decode($_POST["pcrresult"]);
        $rdatresultArr = json_decode($_POST["rdatresult"]);
        $vuser1resultArr = json_decode($_POST["vuser1result"]);
        $vuser2resultArr = json_decode($_POST["vuser2result"]);

        $conn = mysqli_connect("localhost", "libo_user", "xxx");
        mysqli_select_db($conn, "libodb");

        for ($i = 0; $i < count($nameArr); $i++) {
            if (($nameArr[$i] != "")) { /*not allowing empty values and the row which has been removed.*/
                $sql2 = "update covid_test set vuser1= '{$vuser1resultArr[$i]}', vuser2= '{$vuser2resultArr[$i]}',qrptflag='C', pcrtest='{$pcrresultArr[$i]}', rdat='{$rdatresultArr[$i]}' WHERE uuid='{$uuidresultArr[$i]}';";
                $responsetext2 = $responsetext2 . $sql2 . "\n";
                $logtext3 = $logtext3 . "覆核結果: " . $sql2;

                if (!mysqli_query($conn, $sql2)) {
                    die($logtext3 = $logtext3 . 'Error: ' . mysqli_error($conn));
                }
            }
        }

        mysqli_close($conn);

        echo json_encode(array(
            'responsetext' => $responsetext2,
        ));
        
        $sql_comment = $logtext3;
        write_sql($sql_comment);
    }
}


function uploadresult($QCarray, $samplearray, $resultarray)
{
    $text = "";
    $QCarray_len = "a" . strval(count($QCarray));
    $samplearray_len = "a" . strval(count($samplearray));
    $resultarray_len = "a" . strval(count($resultarray));
    $confirmarray = array();

    if ($QCarray_len == $samplearray_len && $QCarray_len == $resultarray_len) {
        
        $general_list = array("檢體編號", 'PCR 結果', '報告輸入時間', '檢測醫檢師', '覆核醫檢師', " ");
        $text = $text . '<table class="table table-hover" style="word-break: break-all"; id="table1" name="table1">';
        $text = $text . '<thead><tr><th colspan="9" style="text-align:center; color:#556B2F"></th></tr></thead>';
        $text = $text . '<tr>';
        for ($i = 0; $i < count($general_list); $i++) {
            if ($i == 1) {
                $text = $text . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br>default result: negative</th>';
            } else if ($i == 3) {
                $text = $text . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br><input type="text" id="uploadvuser1" onkeyup="showvuser1(this.value)"></th>';
            } else if ($i == 4) {
                $text = $text . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br><input type="text" id="uploadvuser2" onkeyup="showvuser2(this.value)"</th>';
            } else {
                $text = $text . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '</th>';
            }
        }
        $text = $text . '</tr>';

        for ($j = 0; $j < count($QCarray); $j++) {
            if (($QCarray[$j] == "PC_and_NTC_PASS") && (($resultarray[$j] == "negative") || ($resultarray[$j] == "positive"))) {
                array_push($confirmarray, 0);
            }else{
                array_push($confirmarray, 1);
            }
        }
        for ($j = 0; $j < count($QCarray); $j++) {
            if ($confirmarray[$j]==0){
                $sampleid = $samplearray[$j];
                $text = $text . "<tr id='{$sampleid}'>";
                $text = $text . "<td>{$sampleid}</td>";
                if ($resultarray[$j] == "negative") {
                    $default_result = "negative";
                    $text = $text . "<td class='pcrtest{$sampleid}'><div class='form-check'>
                    <input class='form-check-input' type='radio' name='{$sampleid}' value='positive' onchange='resultChange(this.name,this.value)'>
                    <label class='form-check-label'>positive</label></div>
                    <div class='form-check'>
                    <input class='form-check-input' type='radio' name='{$sampleid}' value='negative' checked onchange='resultChange(this.name,this.value)'>
                    <label class='form-check-label'>negative</label>
                    </div><input name='pcrtest' id='pcrtest{$sampleid}' value='{$default_result}' disabled'></td>";
                } else if ($resultarray[$j] == "positive") {
                    $default_result = "positive";
                    $text = $text . "<td class='pcrtest{$sampleid}'><div class='form-check'>
                    <input class='form-check-input' type='radio' name='{$sampleid}' value='positive' checked onchange='resultChange(this.name,this.value)'>
                    <label class='form-check-label'>positive</label></div>
                    <div class='form-check'>
                    <input class='form-check-input' type='radio' name='{$sampleid}' value='negative' onchange='resultChange(this.name,this.value)'>
                    <label class='form-check-label'>negative</label>
                    </div><input name='pcrtest' id='pcrtest{$sampleid}' value='{$default_result}' disabled style='color:red;'></td>";
                }

                $text = $text . "<td class='rdat{$sampleid}'><input type='text' class='rdat' disabled></td>";
                $text = $text . "<td class='vuser1{$sampleid}'><input type='text' class='vuser1_text' disabled></td>";
                $text = $text . "<td class='vuser2{$sampleid}'><input type='text' class='vuser2_text' disabled></td>";
                $text = $text . "<td width='100px'><a href='javascript:void(0);' class='remCF' onclick='remove(this)'>刪除本列</a></td>";
            }
        
        }
        $text = $text . '</tr>';
        $text = $text . '</table>';
    } else {
        $text = "檢驗結果有缺漏，或 QC 未通過。請檢查原始數據";
    }

    return $text;
}
