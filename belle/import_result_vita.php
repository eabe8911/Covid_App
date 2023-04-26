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
    // $os = array("admin", "olive", "iris", "dick", "mike", "sophia", "allen", "weichih", "ivan", "leslie", "belle");
    // if (in_array($_SESSION["username"], $os)) {
    if (($_SESSION["division"] == 0) || ($_SESSION["division"] == 2)) {
    } else {
        echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
        echo '<script language="javascript">window.location.replace("menu.php");</script>';
    }
} else {
    header("location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上傳 PCR 結果</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script>
        $(function() {
            $("#nav").load("nav.html");
        });
    </script>
</head>
<script type="text/javascript">
    $(document).ready(function() {
        $('#start').submit(function(e) {
            e.preventDefault();
            var file_data = $('#upload_file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('function', "uploadfile");
            form_data.append('file', file_data);
            form_data.append('MT_ajax', $('#login_MT').val());
            $.ajax({
                url: 'import_result_vita_ajax.php',
                type: 'POST',
                contentType: false,
                processData: false,
                data: form_data,
                success: function(msg) {
                    let responsetext = msg['responsetext'];
                    document.getElementById('text').innerHTML = responsetext;
                    let loginMT = msg['loginMT'];
                    document.getElementById("display").innerHTML = loginMT;
                    let dataarray = msg['dataarray'];
                    document.getElementById('table').innerHTML = dataarray;
                    let returnnumber = msg['returnnumber'];
                    if (returnnumber == 0) {
                        document.getElementById('butsave').removeAttribute('hidden');
                        document.getElementById('table').setAttribute('hidden', true);
                    }

                },
                error: function() {
                    alert('error')
                }
            });
        });
    });

    function showvuser1(str) {
        if (str.length > 0) {
            var a = $('#uploadvuser1').val();
            $(".vuser1_text").val(a);
        } else {
            $(".vuser1_text").val("");
        }
    };

    function showvuser2(str) {
        if (str.length > 0) {
            var b = $('#uploadvuser2').val();
            $(".vuser2_text").val(b);
        } else {
            $(".vuser2_text").val("");
        }
    };
    showvuser1("");
    showvuser2("");

    function resultChange(str, str2) {
        var tagname = str;
        var result = str2;
        row = $('#pcrtest' + tagname)
        if (result == "positive") {
            row.val(result).css('color', 'red');
        } else {
            row.val(result).css('color', 'black');
        }

    };

    function updatetime() {
        var d = new Date(),
            finalDate = d.toISOString().split('T')[0] + ' ' + d.toTimeString().split(' ')[0];
        $(".rdat").val(finalDate);
        $(".rdat_2").val(finalDate);

    }
    setInterval(updatetime, 5000);

    function remove(el) {
        var element = el;
        element.parentNode.parentNode.parentNode.removeChild(element.parentNode.parentNode)
    }

    $(document).ready(function() {
        $("#clear").click(function() {
            window.location.reload();
        });
    });

    $(document).ready(function() {
        $("#butsave").click(function() {
            var form_data = new FormData();
            form_data.append('function', "sendresult");

            user = document.getElementById("display").textContent;

            form_data.append('user', user);
            form_data.append('vuser1_ajax', $('#uploadvuser1').val());
            form_data.append('vuser2_ajax', $('#uploadvuser2').val());

            var lastRowId = $('#table1 tr:last').attr("id");
            var idArr = [];
            var trs = document.getElementsByTagName("tr");
            for (var i = 0; i < trs.length; i++) {
                idArr.push(trs[i].id);
            }
            var name = new Array();
            var pcrresult = new Array();
            var rdatresult = new Array();
            var vuser1result = new Array();
            var vuser2result = new Array();

            for (var i = 0; i <= idArr.length - 1; i++) {
                if (idArr[i] != "") {
                    name.push(idArr[i]);
                    pcrresult.push($("#" + idArr[i] + " .pcrtest" + idArr[i] + " #pcrtest" + idArr[i]).val());
                    rdatresult.push($("#" + idArr[i] + " .rdat" + idArr[i] + " .rdat").val());
                    vuser1result.push($("#" + idArr[i] + " .vuser1" + idArr[i] + " .vuser1_text").val());
                    vuser2result.push($("#" + idArr[i] + " .vuser2" + idArr[i] + " .vuser2_text").val());
                }
            }
            var sendName = JSON.stringify(name);
            var sendpcrresult = JSON.stringify(pcrresult);
            var sendrdatresult = JSON.stringify(rdatresult);
            var sendvuser1result = JSON.stringify(vuser1result);
            var sendvuser2result = JSON.stringify(vuser2result);

            form_data.append('name', sendName);
            form_data.append('pcrresult', sendpcrresult);
            form_data.append('rdatresult', sendrdatresult);
            form_data.append('vuser1result', sendvuser1result);
            form_data.append('vuser2result', sendvuser2result);


            var text = "";
            for (var i = 0; i < name.length; i++) {
                text = text + "檢體編號: " + name[i] + " ";
                text = text + "檢測結果: " + pcrresult[i] + " ";
                text = text + "上傳時間: " + rdatresult[i] + " ";
                text = text + "檢測醫檢師: " + vuser1result[i] + " ";
                text = text + "覆核醫檢師: " + vuser2result[i] + " ";
                text = text + "\n";
            }
            var textpositive = "";
            for (var i = 0; i < pcrresult.length; i++) {
                if (pcrresult[i] == "positive") {
                    textpositive = textpositive + "請確認以下檢體編號為陽性?\n";
                    textpositive = textpositive + "檢體編號: " + name[i] + " ";
                    textpositive = textpositive + "檢測結果: " + pcrresult[i] + " ";
                    textpositive = textpositive + "\n\n";
                }
            }

            if (confirm("請確認上傳結果無誤: \n" + text) == true) {
                if (textpositive != "") {
                    if (confirm(textpositive) == true) {
                        $.ajax({
                            url: "import_result_vita_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(msg) {
                                alert("確認上傳");
                                let data = msg['responsetext'];
                                alert(data); /* alerts the response from php.*/
                                if (msg["text2"] !== undefined) {
                                    alert("上傳內容包含已有核發報告之檢體，請確認黃色填充欄位，是否要覆蓋檢驗結果、或是點選'刪除此列'刪除該列。\n上傳完成後，請通知營業部重新產生該筆報告。");
                                    let responsetext = msg['text2'];
                                    document.getElementById('text2').innerHTML = responsetext;
                                    document.getElementById('butsave2').removeAttribute('hidden');
                                    document.getElementById('butsave').setAttribute('hidden', true);
                                    document.getElementById('text').setAttribute('hidden', true);
                                } else if (msg["checknumber"]!=1) {
                                    if (alert('上傳成功!')) {} else window.location.reload();
                                }
                                // console.log(msg);
                                document.getElementById('start').setAttribute('hidden', true);
                            },
                            error: function() {
                                alert('error')
                            }
                        });
                    } else {
                        alert("取消上傳");
                        window.location.reload();
                    }
                } else {
                    if (confirm("本次所有結果皆為陰性?") == true) {
                        $.ajax({
                            url: "import_result_vita_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(msg) {
                                alert("確認上傳");
                                let data = msg['responsetext'];
                                alert(data); /* alerts the response from php.*/
                                if (msg["text2"] !== undefined) {
                                    alert("上傳內容包含已有核發報告之檢體，請確認黃色填充欄位，是否要覆蓋檢驗結果、或是點選'刪除此列'刪除該列。\n上傳完成後，請通知營業部重新產生該筆報告。");
                                    let responsetext = msg['text2'];
                                    document.getElementById('text2').innerHTML = responsetext;
                                    document.getElementById('butsave2').removeAttribute('hidden');
                                    document.getElementById('butsave').setAttribute('hidden', true);
                                    document.getElementById('text').setAttribute('hidden', true);
                                } else if (msg["checknumber"]!=1) {
                                    if (alert('上傳成功!')) {} else window.location.reload();
                                }
                                // console.log(msg);
                                document.getElementById('start').setAttribute('hidden', true);
                            },
                            error: function() {
                                alert('error')
                            }

                        });
                    } else {
                        alert("取消上傳");
                        window.location.reload();
                    }
                }
            } else {
                alert("取消上傳");
                window.location.reload();
            }

        });
    });

    $(document).ready(function() {
        $("#butsave2").click(function(e) {
            var form_data2 = new FormData();
            form_data2.append('function', "sendconfirmresult");
            var lastRowId = $('#table2 tr:last').attr("id");
            var idArr_2 = [];
            var trs = document.getElementById("table2").getElementsByTagName("tr");
            for (var i = 0; i < trs.length; i++) {
                idArr_2.push(trs[i].id);
            }
            var name_2 = new Array();
            var uuidresult_2 = new Array();
            var pcrresult_2 = new Array();
            var rdatresult_2 = new Array();
            var vuser1result_2 = new Array();
            var vuser2result_2 = new Array();
            for (var i = 0; i <= idArr_2.length - 1; i++) {
                if (idArr_2[i] != "") {
                    name_2.push(idArr_2[i]);
                    uuidresult_2.push($("#" + idArr_2[i] + " .uuid" + idArr_2[i] + "_2 #uuid" + idArr_2[i] + "_2").val());
                    pcrresult_2.push($("#" + idArr_2[i] + " .pcrtest" + idArr_2[i] + "_2 #pcrtest" + idArr_2[i] + "_2").val());
                    rdatresult_2.push($("#" + idArr_2[i] + " .rdat" + idArr_2[i] + "_2 .rdat_2").val());
                    vuser1result_2.push($("#" + idArr_2[i] + " .vuser1" + idArr_2[i] + "_2 .vuser1_text").val());
                    vuser2result_2.push($("#" + idArr_2[i] + " .vuser2" + idArr_2[i] + "_2 .vuser2_text").val());
                }
            }
            var sendName = JSON.stringify(name_2);
            var senduuidresult = JSON.stringify(uuidresult_2);
            var sendpcrresult = JSON.stringify(pcrresult_2);
            var sendrdatresult = JSON.stringify(rdatresult_2);
            var sendvuser1result = JSON.stringify(vuser1result_2);
            var sendvuser2result = JSON.stringify(vuser2result_2);

            form_data2.append('name', sendName);
            form_data2.append('uuidresult', senduuidresult);
            form_data2.append('pcrresult', sendpcrresult);
            form_data2.append('rdatresult', sendrdatresult);
            form_data2.append('vuser1result', sendvuser1result);
            form_data2.append('vuser2result', sendvuser2result);

            var text = "";
            for (var i = 0; i < name_2.length; i++) {
                text = text + "檢體編號: " + name_2[i] + " ";
                // text = text + "UUID: " + uuidresult_2[i] + " ";
                text = text + "檢測結果: " + pcrresult_2[i] + " ";
                text = text + "上傳時間: " + rdatresult_2[i] + " ";
                text = text + "檢測醫檢師: " + vuser1result_2[i] + " ";
                text = text + "覆核醫檢師: " + vuser2result_2[i] + " ";
                text = text + "\n";
            }
            var textpositive = "";
            for (var i = 0; i < pcrresult_2.length; i++) {
                if (pcrresult_2[i] == "positive") {
                    textpositive = textpositive + "請確認以下檢體編號為陽性?\n";
                    textpositive = textpositive + "檢體編號: " + name_2[i] + " ";
                    textpositive = textpositive + "檢測結果: " + pcrresult_2[i] + " ";
                    textpositive = textpositive + "\n\n";
                }
            }

            if (confirm("請確認上傳結果無誤: \n" + text) == true) {
                if (textpositive != "") {
                    if (confirm(textpositive) == true) {
                        $.ajax({
                            url: "import_result_vita_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data2,
                            success: function(msg) {
                                // let data = msg['responsetext'];
                                if (alert('上傳成功!')) {} else window.location.reload();
                            },
                            error: function(msg) {
                                alert('error')
                            }
                        });
                    } else {
                        alert("取消上傳");
                        window.location.reload();
                    }
                } else {
                    if (confirm("本次所有結果皆為陰性?") == true) {
                        $.ajax({
                            url: "import_result_vita_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data2,
                            success: function(msg) {
                                // let data = msg['responsetext'];
                                if (alert('上傳成功!')) {} else window.location.reload();
                            },
                            error: function(msg) {
                                alert('error');
                            }

                        });
                    } else {
                        alert("取消上傳");
                        window.location.reload();
                    }
                }
            } else {
                alert("取消上傳");
                window.location.reload();
            }

        });
    });
</script>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>

    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0" style="background-color:#ffffe6;">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <div></div>
                <div>
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="history.back()" value="回到上一頁">
                    <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回首頁">
                    <br>
                    <input style="margin:1em;" type="reset" id="clear" class="btn btn-success" value="清除">
                </div>
            </div>
        </div>
        <div class="col py-3" style="padding-left:15px;">
            <form style="display:inline;" id="start">
                <input type="text" id="login_MT" value="<?php echo $_SESSION["confirm_pw"] ?>" disabled hidden>
                <input class="form-control" name="file" type="file" id="upload_file" style="width:18rem;">
                <label for="formFile" class="form-label" id="upload_file_text"><br>請在上方將 USB 中 "Results" 資料夾的當日檢驗結果 txt 檔案上傳。 (檔名範例: 211231_21G08017.txt，表示 2021 年 12 月 31 日的檢驗結果 txt 檔。)</label>
                <h2><font color="red">如有單一報告結果修改需求，請用「修改報告」功能</font></h2>
	        <h2><font color="red">如有單一報告結果修改或批次報告結果重新上傳，請第一時間通知營業處重新產生報告</font></h2>
                                <br>
                <div class="col-auto">
                    <input type="submit" name="search" class="btn btn-success" value="上傳檔案">
                </div>
            </form>
            <br>
            <div id="display" style="color:red" hidden></div>
            <div id="text"></div>
            <div id="text2"></div>
            <div id="table"></div>
            <form id='form1' name='form1' method='post' enctype="multipart/form-data">
                <input type='button' name='save' class='btn btn-success' value='上傳結果' id='butsave' hidden>
            </form>
            <form id='form2' name='form2' method='post' enctype="multipart/form-data">
                <input type='submit' name='submit' class='btn btn-success' value='確認結果' id='butsave2' hidden>
            </form>
        </div>
    </div>


</body>

</html>