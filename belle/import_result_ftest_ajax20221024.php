<?php
header('Content-Type: application/json');

require_once("php/log.php");

$loginMT = $_POST['MT_ajax'];
$dateinput = $_POST['date_ajax'];

if (isset($_POST['function'])) {
    if ($_POST['function'] == 'selectdate') {

        [$dateinputresult, $returnnumber] = checkdateempty($dateinput);

        if ($dateinputresult != "請輸入日期。") {
            [$checkdata, $returnnumber2] = uploadresult($dateinput, $vuser1_post, $vuser2_post);
        } else {
            $returnnumber2 = 1;
        }

        echo json_encode(array(
            'loginMT' => $loginMT,
            'dateinputresult' => $dateinputresult,
            'checkdata' => $checkdata,
            'returnnumber' => $returnnumber,
            'returnnumber2' => $returnnumber2
        ));
        $sql_comment = $loginMT . $dateinputresult;
        write_sql($sql_comment);
    } else if ($_POST['function'] == 'sendresult') {
        $logtext2 = "";
        $responsetext = "";
        $checknumber = 0;

        $user = strtoupper(trim($_POST["user"]));
        $vuser1_ajax = strtoupper(trim($_POST['vuser1_ajax']));
        $vuser2_ajax = strtoupper(trim($_POST['vuser2_ajax']));
        $nameArr = json_decode($_POST["name"]);
        $uuidresultArr = json_decode($_POST["uuidresult"]);
        $ftestresultArr = json_decode($_POST["ftestresult"]);
        $rdatresultArr = json_decode($_POST["rdatresult"]);
        $vuser1resultArr = json_decode($_POST["vuser1result"]);
        $vuser2resultArr = json_decode($_POST["vuser2result"]);
        $nameforlog = implode(',', $nameArr);
        $ftestforlog = implode(',', $ftestresultArr);
        $rdatforlog = implode(',', $rdatresultArr);
        $os1 = array('LESLIE', 'H123160258', 'P222717661', 'A225558000', 'N225198185', 'P124237860', 'N123478768', 'B122408253', "OLIVE", "BELLE", "LSC2665!", 'D221222459', "G122100926", "R220853399", "A227576211", "N223189697", "V221250467", "F230420563");
        $os2 = array('H123160258', 'P222717661', 'A225558000', 'N225198185', 'P124237860', 'N123478768', 'B122408253', 'D221222459', 'R220853399');

        // 檢查上傳者程序
        $logtext2 = $logtext2 . "上傳資料: " . "登入者: " . $user . "檢測醫檢師: " . $vuser1_ajax . "覆核醫檢師: " . $vuser2_ajax .
            "檢體列: " . $nameforlog . "檢驗結果列: " . $ftestforlog . "報告時間列: " . $rdatforlog;

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

                $conn = mysqli_connect("localhost", "libo_user", "xxx");
                mysqli_select_db($conn, "libodb");

                if (mysqli_connect_errno()) {
                    // $responsetext = $responsetext . "Failed to connect to MySQL: " . mysqli_connect_error();
                    $logtext2 = $logtext2 . "Failed to connect to MySQL: " . mysqli_connect_error();
                    $checknumber = 1;

                } else {

                    for ($i = 0; $i < count($nameArr); $i++) {
                        if (($nameArr[$i] != "")) { /*not allowing empty values and the row which has been removed.*/
                            $sql = "update covid_trans set vuser1= '{$vuser1resultArr[$i]}', vuser2= '{$vuser2resultArr[$i]}',frptflag='C', ftest='{$ftestresultArr[$i]}', rdat='{$rdatresultArr[$i]}' WHERE uuid='{$uuidresultArr[$i]}';";
                            $responsetext = $responsetext . $sql;
                            $logtext2 = $logtext2 . $sql;

                            if (!mysqli_query($conn, $sql)) {
                                die($logtext2 = $logtext2 . mysqli_error($con));
                                $checknumber = 1;
                            }
                        }
                    }
                    mysqli_close($conn);
                    // 上傳完成
                }
            }
        }
        echo json_encode(array(
            'responsetext' => $responsetext,
            'checknumber' => $checknumber,
        ));
        $sql_comment = $user . $logtext2;
        write_sql($sql_comment);
    }
}

function checkdateempty($dateinput)
{
    $subject = trim($dateinput);
    if ($subject == "") {
        $text = "請輸入日期。";
        $returnnumber = 1;
    } else {
        $text = "<div style='color:blue;'>" . $subject . " 的檢體列:<br><br></div>";
        $returnnumber = 0;
        // echo "<script>Autofresh('{$subject}');</script>";
    }
    return [$text, $returnnumber];
}


function uploadresult($dateinput)
{
    $text = "";
    $returnnumber = 0;

    $conn = mysqli_connect("localhost", "libo_user", "xxx");
    mysqli_select_db($conn, "libodb");

    $sql_general = "SELECT sampleid1,ftest,rdat,vuser1,vuser2,uuid FROM libodb.covid_trans 
    WHERE 1=1 and tdat like '%{$dateinput}%' and (testtype='1' or testtype = '3')
											and (ftest='' or ftest is null or ftest='NA') and (sampleid1 not like '') and ((frptflag is null) or(frptflag like '')) ;";
    $result_general = $conn->query($sql_general);

    $row_cnt = $result_general->num_rows;

    // echo $sql_general;

    if ($row_cnt == 0) {
        $text = $text . "<div style='color:blue;'>尚無未完成檢體。</div>";
        $returnnumber = 1;
    } else {
        $general_list = array("檢體編號", '快篩結果', '報告輸入時間', '檢測醫檢師', '覆核醫檢師', " ", 'uuid');
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
            } else if ($i == 6) {
                $text = $text . '<th style="color:#556B2F;font-size:0px;" id="' . $general_list[$i] . '">' . $general_list[$i] . '<br></th>';
            } else {
                $text = $text . '<th style="color:#556B2F" id="' . $general_list[$i] . '">' . $general_list[$i] . '</th>';
            }
        }
        $text = $text . '</tr>';

        while ($row_general = $result_general->fetch_assoc()) {
            // print_r($result_general);
            $i = 0;
            // $text = $text . "<tr>";
            foreach ($row_general as $item) {
                $sampleid = $row_general["sampleid1"];
                // $text = $text . "<tr id='{$sampleid}'>";
                if ($i == 0) {
                    // $sampleid=$item;
                    $text = $text . "<tr id='{$sampleid}'>";
                    $text = $text . "<td>{$item}</td>";
                } else if ($i == 1) {
                    $text = $text .
                        "<td class='ftest{$sampleid}'><div class='form-check'>
                        <input class='form-check-input' type='radio' name='{$sampleid}' value='positive' onchange='resultChange(this.name,this.value)'>
                        <label class='form-check-label'>positive</label></div>
                        <div class='form-check'>
                        <input class='form-check-input' type='radio' name='{$sampleid}' value='negative' checked onchange='resultChange(this.name,this.value)'>
                        <label class='form-check-label'>negative</label>
                        </div><input name='ftest' id='ftest{$sampleid}' value='negative' disabled></td>";
                    // </div><input name='ftest' id='$sampleid' value='negative' disabled></td>";

                } else if ($i == 2) {
                    $text = $text . "<td class='rdat{$sampleid}'><input type='text' class='rdat' disabled></td>";
                } else if ($i == 3) {
                    $text = $text . "<td class='vuser1{$sampleid}'><input type='text' class='vuser1_text' disabled></td>";
                } else if ($i == 4) {
                    $text = $text . "<td class='vuser2{$sampleid}'><input type='text' class='vuser2_text' disabled></td>";
                } else if ($i == 5) {
                    $text = $text . "<td class='uuid{$sampleid}' style='width:0px;'><input style='width:0px;' type='hidden' id='uuid{$sampleid}' disabled value='{$item}'></td>";
                }
                $i += 1;
            }
            $text = $text . "<td width='100px'><a href='javascript:void(0);' class='remCF' onclick='remove(this)'>刪除本列</a></td>";
        }
        $text = $text . '</tr>';
        $text = $text . '</table>';
        $returnnumber = 0;
    }
    // // Close connection
    mysqli_close($conn);

    return [$text, $returnnumber];
}
