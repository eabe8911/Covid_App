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
    <title>輸入快篩報告</title>
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
            $.ajax({
                url: 'import_result_ftest_ajax.php',
                data: {
                    function: "selectdate",
                    date_ajax: $('#date').val(),
                    MT_ajax: $('#login_MT').val(),
                },
                type: 'POST',
                dataType: 'json',
                success: function(msg) {
                    let checkdata = msg['checkdata'];
                    document.getElementById('table').innerHTML = checkdata;
                    let loginMT = msg['loginMT'];
                    document.getElementById("display").innerHTML = loginMT;
                    let dateinputresult = msg['dateinputresult'];
                    document.getElementById('text').innerHTML = dateinputresult;
                    let returnnumber = msg['returnnumber'];
                    let returnnumber2 = msg['returnnumber2'];
                    if (returnnumber == 0 && returnnumber2 == 0) {
                        document.getElementById('butsave').removeAttribute('hidden');
                        document.getElementById('start').setAttribute('hidden', true);
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
        row = $('#ftest' + tagname)
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
            // console.log(lastRowId);
            var idArr = [];
            var trs = document.getElementsByTagName("tr");
            for (var i = 0; i < trs.length; i++) {
                idArr.push(trs[i].id);
            }
            var name = new Array();
            var uuidresult = new Array();
            var ftestresult = new Array();
            var rdatresult = new Array();
            var vuser1result = new Array();
            var vuser2result = new Array();
            for (var i = 0; i <= idArr.length - 1; i++) {
                if (idArr[i] != "") {
                    name.push(idArr[i]);
                    uuidresult.push($("#" + idArr[i] + " .uuid" + idArr[i] + " #uuid" + idArr[i]).val());
                    ftestresult.push($("#" + idArr[i] + " .ftest" + idArr[i] + " #ftest" + idArr[i]).val());
                    rdatresult.push($("#" + idArr[i] + " .rdat" + idArr[i] + " .rdat").val());
                    vuser1result.push($("#" + idArr[i] + " .vuser1" + idArr[i] + " .vuser1_text").val());
                    vuser2result.push($("#" + idArr[i] + " .vuser2" + idArr[i] + " .vuser2_text").val());
                }
            }
            var sendName = JSON.stringify(name);
            var senduuidresult = JSON.stringify(uuidresult);
            var sendftestresult = JSON.stringify(ftestresult);
            var sendrdatresult = JSON.stringify(rdatresult);
            var sendvuser1result = JSON.stringify(vuser1result);
            var sendvuser2result = JSON.stringify(vuser2result);

            form_data.append('name', sendName);
            form_data.append('uuidresult', senduuidresult);
            form_data.append('ftestresult', sendftestresult);
            form_data.append('rdatresult', sendrdatresult);
            form_data.append('vuser1result', sendvuser1result);
            form_data.append('vuser2result', sendvuser2result);


            var text = "";
            for (var i = 0; i < name.length; i++) {
                text = text + "檢體編號: " + name[i] + " ";
                // text = text + "UUID: " + uuidresult[i] + " ";
                text = text + "檢測結果: " + ftestresult[i] + " ";
                text = text + "上傳時間: " + rdatresult[i] + " ";
                text = text + "檢測醫檢師: " + vuser1result[i] + " ";
                text = text + "覆核醫檢師: " + vuser2result[i] + " ";
                text = text + "\n";
            }
            var textpositive = "";
            for (var i = 0; i < ftestresult.length; i++) {
                if (ftestresult[i] == "positive") {
                    textpositive = textpositive + "請確認以下檢體編號為陽性?\n";
                    textpositive = textpositive + "檢體編號: " + name[i] + " ";
                    textpositive = textpositive + "檢測結果: " + ftestresult[i] + " ";
                    textpositive = textpositive + "\n\n";
                }
            }

            if (confirm("請確認上傳結果無誤: \n" + text) == true) {
                if (textpositive != "") {
                    if (confirm(textpositive) == true) {
                        $.ajax({
                            url: "import_result_ftest_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(msg) {
                                if (msg["checknumber"] != 1) {
                                    if (alert('上傳成功!')) {} else window.location.reload();
                                } else {
                                    // alert("確認上傳");
                                    let data = msg['responsetext'];
                                    alert(data); /* alerts the response from php.*/
                                }
                            }
                        });
                    } else {
                        alert("取消上傳");
                    }
                } else {
                    if (confirm("本次所有結果皆為陰性?") == true) {
                        $.ajax({
                            url: "import_result_ftest_ajax.php",
                            type: "post",
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(msg) {
                                if (msg["checknumber"] != 1) {
                                    if (alert('上傳成功!')) {} else window.location.reload();
                                } else {
                                    // alert("確認上傳");
                                    let data = msg['responsetext'];
                                    alert(data); /* alerts the response from php.*/
                                }
                            }
                        });
                    } else {
                        alert("取消上傳");
                    }
                }

            } else {
                alert("取消上傳");
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
                    <!-- !-- <input style="margin:1em;" type="button" class="btn btn-secondary" onclick="window.location.href='menu_version1.html'" value="回舊版目錄"> --> -->
                </div>
            </div>
        </div>
        <div class="col py-3" style="padding-left:15px;">
            <form style="display:inline;" id="start">
                <input type="text" id="login_MT" value="<?php echo $_SESSION["confirm_pw"] ?>" disabled hidden>
                <div class="col-6 col-sm-3">
                    <label>請選擇欲輸入結果的日期</label>
                    <input type="date" id="date" name="date" class="form-control">
                </div>
                <h2><font color="red">如有單一報告結果修改需求，請用「修改報告」功能</font></h2>
	        <h2><font color="red">如有單一報告結果修改或批次報告結果重新上傳，請第一時間通知營業處重新產生報告</font></h2>
                <br>
                <div class="col-auto">
                    <input type="submit" name="search" class="btn btn-success" value="搜尋">
                </div>
            </form>
            <br>
            <div id="text"></div>
            <div id="table"></div>
            <div id="display" style="color:red" hidden></div>
            <form id='form1' name='form1' method='post' enctype="multipart/form-data">
                <input type='button' name='save' class='btn btn-success' value='上傳' id='butsave' hidden>
            </form>
        </div>
    </div>
</body>

</html>