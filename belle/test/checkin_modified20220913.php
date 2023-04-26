<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (!isset($_SESSION["division"]) || ($_SESSION["division"] > 1)) {
        echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
        echo '<script language="javascript">window.location.replace("menu.php");</script>';
    }
} else {
    header("location: login.php");
}
$user_name = $_SESSION["username"];
$recordcount = "0 / 0";

require_once 'php/checkin_modified_php.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>報到/修改客戶資料</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="css/menu.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/checkin_modified.css">
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $( "#dialog-confirm" ).dialog({
                autoOpen: false,
                show:{
                    effect: "shake",
                    duration: 800
                },
                hide: {
                    effect: "fade",
                    duration: 800
                }
            });

            $("#nav").load("nav.html");
            //$("#userid").focus();
            var userid = document.getElementById("userid");
            var uuid = document.getElementById("uuid");
            // var mobile = document.getElementById("mobile");
            // var hicardno = document.getElementById("hicardno");
            var tdat = document.getElementById("tdat");
            var clear = document.getElementById("clear");
            var checkin_x = document.getElementById("checkin_x");
            var result_msg = document.getElementById("result_msg").value;
            var user_name = document.getElementById("user_name").value;
            switch(result_msg){
                case '1':   // 第一次進入
                    button_checkin(false);
                    uuid.focus();
                    break;
                case '2':   // 查詢成功
                    //alert(user_name);
                    if(user_name == "cindyT") break;
                    if(tdat && tdat.value){
                        //clear.focus();
                        $( "#dialog-confirm" ).dialog({
                            resizable: false,
                            height: "auto",
                            width: 400,
                            modal: true,
                            buttons: {
                                "  再  次  報   到  ": function() {
                                    checkin_x.click();
                                $( this ).dialog( "close" );
                                },
                                "  修  改  客  戶  資  料  ": function() {
                                $( this ).dialog( "close" );
                                clear_focus();
                                }
                            }
                            });
                            $( "#dialog-confirm" ).dialog( "open" );
                    }else{
                        checkin_x.focus();
                    }
                    break;
                case '3':   // 查詢失敗
                    //$("#checkin_x").attr('disabled', true);
                    button_checkin(false);
                    clear.focus();
                    break;
                case '4':   // 報到成功
                    //$("#checkin_x").attr('disabled', false);
                    button_checkin(true);
                    userid.focus();
                    break;
                case '5':   // 儲存成功
                    userid.focus();
                    break;
                default:
                    break;
            }

            document.getElementById("uuid").addEventListener("keypress", function(event){
                if(event.key ==="Enter"){
                    event.preventDefault();
                    document.getElementById("search").click();
                }
            })

            document.getElementById("userid").addEventListener("keypress", function(event){
                if(event.key ==="Enter"){
                    event.preventDefault();
                    document.getElementById("search").click();
                }
            })
            document.getElementById("passportid").addEventListener("keypress", function(event){
                if(event.key ==="Enter"){ event.preventDefault(); document.getElementById("search").click(); }
            })
            document.getElementById("mobile").addEventListener("keypress", function(event){
                if(event.key ==="Enter"){ event.preventDefault(); document.getElementById("search").click(); }
            })

            if($("#page").val() === "0"){
                button_record(false);
            }else{
                button_record(true);
            }

        });

        // document.getElementById("hicardno").addEventListener("keypress", function(event){
        //     if(event.key ==="Enter"){ event.preventDefault(); document.getElementById("search").click(); }
        // })

        function clear_focus(){
            $("#defaultCheck2").click();
        };

        function button_checkin(status){
            if(status === true){
                $("#checkin_x").attr('disabled', false);
            }else{
                $("#checkin_x").attr('disabled', true);
            }
        };

        function button_record(status){
            if(status === true){
                $("#previous_record").attr('disabled', false);
                $("#next_record").attr('disabled', false);
            }else{
                $("#previous_record").attr('disabled', true);
                $("#next_record").attr('disabled', true);
            }
        };

    </script>
</head>

<body>

    <div id="dialog-confirm" title="是否再次報到？">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>已經有報到時間，是否要再次報到？</p>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-success" id="nav"></nav>

    <!-- side navbar -->
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0" style="background-color:#ffffe6;">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <div></div>
                <div id="print_ckin">
                    <form action="../print_inspection.php" method="post">
                        <br>
                        &ensp;&ensp;&ensp;
                        <input id="printdiv" type="submit" class="btn btn-success" name="print" value="   列            印   ">
                    </form>
                </div>


                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline;">
                <input type="hidden" id="result_msg" value="<?php echo $result_msg; ?>">
                <input type="hidden" id="user_name" value="<?php echo $user_name; ?>">
                <input type="hidden" id="page" value="<?php echo $page; ?>">
                <input type="hidden" id="page_count" value="<?php echo $page_count; ?>">
                <div class="form-check form-switch">
                    <br>
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                    <label class="form-check-label" for="defaultCheck2" style="color:#003300;">
                        <span class="ms-1 d-none d-sm-inline">修改資料</span>開關
                    </label>
                </div>
                <div></div>
                <div>
                    <a style="margin:1em;" class="nav-link px-0"><h2><?php echo $search_result; ?></h2><h3><?php echo $save_result; ?></h3><span class="d-none d-sm-inline"></span></a>
                </div>
                <div>
                    <input type="submit" id="previous_record" name="previous_record" class="btn btn-secondary" value="上一筆"></input>
                    <label id="recordcount" style="color: black;"><?php echo $recordcount ?></label>
                    <input type="submit" id="next_record" name="next_record" class="btn btn-secondary" value="下一筆"></input>
                </div>
                <br><br>
                <div class="col-auto">
                    <input type="button" class="btn btn-secondary" onclick="history.back()" value="上 一 頁"></input>&ensp;&ensp;&ensp;
                    <input type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'" value="回 首 頁"></input>
                </div>
            </div>
        </div>
        <!-- content -->
        <div class="col py-3">
            <h3>修改客戶資料</h3>
            <h5>請輸入身份證，護照號碼或手機號碼查詢預約資料</h5>
            <?php
            if (!empty($input_err)) {
                echo '<div class="alert alert-danger">' . $input_err . '</div>';
            }
            ?>
                <div class="row g-3">
                    <div class="col-auto">
                        <label>UUID</label>
                        <input type="text" id="uuid" name="uuid" class="form-control" value="<?php echo $uuid; ?>">
                    </div>
                    <div class="col-auto">
                        <label>身分證號</label>
                        <input type="text" id="userid" name="userid" class="form-control" value="<?php echo $userid; ?>">
                    </div>
                    <div class="col-auto">
                        <label>護照號碼 Passport NO.</label>
                        <input type="text" id="passportid" name="passportid" class="form-control" value="<?php echo $passportid; ?>">
                    </div>
                    <div class="col-auto">
                        <label>手機號碼 Phone</label>
                        <input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                    </div>
                    <!-- <div class="col-auto">
                        <label>健保卡號 NHI Card NO.</label>
                        <input type="text" id="hicordno" name="hicardno" class="form-control" value="<?php echo $hicardno; ?>">
                    </div> -->
                </div>
                <br>


                <div class="col-auto">
                    <input type="submit" id="search" name="search" class="btn btn-success" value=" 搜    尋 ">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                    <input type="submit" id="clear" name="clear" class="btn btn-success" value=" 清    除 ">
                </div>
                <br>
                <div id="read" class="row g-3">
                    <div class="col-auto" hidden>
                        <label hidden>ID</label>
                    </div>
                    <input type="text" name="twrpturgency" class="form-control " value="<?php echo $twrpturgency; ?>" hidden>

                    <div class="row g-3">
                    <div class="col-auto">
                        <label>快篩 ID</label>
                        <input onchange="TesttypeCheck(this.value)" pattern="^F[0-9]{9}" type="text" :focus id="sampleid1" name="sampleid1" class="form-control" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid1; ?>">
                    </div>
                    <div class="col-auto">
                        <label>PCR ID</label>
                        <input onchange="TesttypeCheck(this.value)" pattern="^Q[0-9]{9}|^QH[0-9]{9}|^QL[0-9]{9}" type="text" :focus id="sampleid2" name="sampleid2" class="form-control" onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid2; ?>">
                    </div>
                    <div id="PointMsgTesttypeCheck"></div>

                    <div class="col-auto">
                        <input type="submit" id="checkin_x" name="checkin" class="btn btn-success" value=" 報   到 ">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                    </div>
                    <div class="col-auto">
                        <input type="submit" name="save" class="btn btn-success" value=" 儲    存 ">
                    </div>

                </div>

                <div class="col-auto">
                    <!-- <input type="submit" name="checkin" class="btn btn-success" value="報到"> -->
                </div>
                    <div class="col-auto">
                        <label>預約日</label>
                        <input type="date" id="apdat" class="form-control" name="apdat" placeholder="1990-01-01" value="<?php echo $apdat; ?>">
                    </div>

                    <div class="col-auto">
                        <label>報到日</label>
                        <input type="text" id="tdat" class="form-control" name="tdat" value="<?php echo $tdat; ?>">
                    </div>

                    <div class="col-auto">
                        <label>報告時效性</label>
                        <input type="text" id="twrpturgency" name="twrpturgency" class="form-control " value="<?php echo $twrpturgency; ?>">
                        <!-- <ul>
                            <li>normal: 一般件</li>
                            <li>urgent: 急件</li>
                            <li>hiurgent: 特急件</li>
                        </ul> -->
                    </div>

                    <div class="col-auto">
                        <label>國籍(持日本入境檢驗證明需輸入)</label>
                        <input type="text" id="nationality" name="nationality" class="form-control " value="<?php echo $nationality; ?>">
                    </div>
                    <div></div>
                    <div class="col-auto">
                        <label>中文姓名</label>
                        <input onchange="CnameCheck(this.value)" pattern="[\u4E00-\u9FFF]+" type="text" id="cname" name="cname" class="form-control " value="<?php echo $cname; ?>">
                        <div id="PointMsgCname"></div>
                    </div>

                    <div class="col-auto">
                        <label>英文姓名</label>
                        <input onchange="EnameCheck(this.value)" type="text" pattern="[a-zA-Z ,-]+" id="fname" name="fname" class="form-control " value="<?php echo $fname; ?>">
                        <!-- <input hidden type="text" id="fname" name="fname" class="form-control " value="<?php echo $fname; ?>">
                        <input hidden type="text" id="lname" name="lname" class="form-control " value="<?php echo $lname; ?>"> -->
                        <div id="PointMsgEname"></div>
                    </div>
                    
                    <!-- <div id="PointMsgIdCardNumber"></div> -->

                    <div class="col-auto">
                        <label>生日</label>
                        <input required type="date" id="dob" name="dob" class="form-control " value="<?php echo $dob; ?>">
                    </div>

                    <div class="col-auto">
                        <label>性別</label>
                        <input required onchange="GenderCheck(this.value)" type="text" id="sex" name="sex" class="form-control " value="<?php echo $sex; ?>">
                        <!-- <p>僅限輸入 "男 / Male" ; "女 / Female" ; "NA"</p> -->
                        <div id="PointMsgGender"></div>
                        <!-- <select id="inputGender" class="form-select">
                            <option selected>請選擇</option>
                            <option>男 / Male</option>
                            <option>女 / Female</option>
                        </select> -->
                    </div>

                    <div></div>

                    <div class="col-auto">
                        <label>身分證字號</label>
                        <input onchange="IdCardNumberCheck(this.value)" type="text" id="userid1" name="userid1" class="form-control " value="<?php echo $userid1; ?>">
                    </div>
                    
                    <div class="col-auto">
                        <label>護照號碼</label>
                        <input type="text" id="passportid1" name="passportid1" class="form-control " value="<?php echo $passportid1; ?>">
                    </div>

		            <div class="col-auto">
                        <label>台胞證號碼</label>
                        <input type="text" id="mtpid" name="mtpid" class="form-control " value="<?php echo $mtpid; ?>">
                    </div>
                    
                    <div class="col-auto">
                        <label>健保卡號</label>
                        <input type="text" id="hicardno1" name="hicardno1" class="form-control " value="<?php echo $hicardno1; ?>">
                    </div>
                    
                    <div id="PointMsgIdCardNumber"></div>

                    <div></div>

                    <div class="col-md-6">
                        <label>E-mail</label>
                        <input onchange="EmailCheck(this.value)" type="email" id="email" name="email" class="form-control " value="<?php echo $uemail; ?>">
                        <div id="PointMsgEmail"></div>
                    </div>

                    <div class="col-md-3">
                        <label>手機號碼</label>
                        <input required onchange="MobileCheck(this.value)" pattern='^[0-9]+$' type="text" id="mobile1" name="mobile1" class="form-control" placeholder="0911111111" value="<?php echo $mobile1; ?>">
                        <div id="PointMsgMobile"></div>
                    </div>

                    <div></div>

                    <div class="col-auto">
                        <label>自費篩檢原因</label>

                        <!-- <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                            <label class="form-check-label" for="inlineRadio1">1</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">2</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                            <label class="form-check-label" for="inlineRadio1">3</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">4</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                            <label class="form-check-label" for="inlineRadio1">5</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">6</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">7</label>
                        </div> -->

                        <input required onchange="TestreasonCheck(this.value)" type="text" pattern="[1-7]{1}" id="testreason" name="testreason" class="form-control " value="<?php echo $testreason; ?>">
                        <div id="PointMsgTestreason"></div>
                        <!-- <ul style="width:900px; text-align:justify;">
                            <li>
                                <a>因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾。需檢附 (1) 申請表；(2) 申請原因相關文件， 如電子機票、購票證明或訂票紀錄等</a>
                                <a style="color:red;">請選數字 1 </a>
                            </li>
                            <li>
                                <a>因工作因素須檢附檢驗證明之民眾。需檢附 (1) 申請表；(2) 工作證明文件，如職員證、工作簽證、出差通知書、電子機票、購票證明或訂票紀錄等</a>
                                <a style="color:red;">請選數字 2 </a>
                            </li>
                            <li>
                                <a>短期商務人士。需檢附 (1 )申請表；(2) 申請原因相關文件（如：在臺行程表或防疫計畫書等</a>
                                <a style="color:red;">請選數字 3 </a>
                            </li>
                            <li>
                                <a>出國求學須檢附檢驗證明之民眾。需檢附 (1) 申請表；(2) 就學證明文件，如學生證、學生簽證、入學通知書、電子機票、購票證明或訂票紀錄等</a>
                                <a style="color:red;">請選數字 4 </a>
                            </li>
                            <li>
                                <a>外國或中國大陸、香港、澳門人士出境。需檢附 (1) 申請表；(2) 護照、入臺許可證、電子機票、購票證明或訂票紀錄等</a>
                                <a style="color:red;">請選數字 5 </a>
                            </li>
                            <li>
                                <a>相關出境適用對象之眷屬。需檢附 (1) 申請表；(2) 身分證及相關出境適用對象之關係證明文件，如戶口名簿、戶籍謄本、適用對象之工作、就學證明等文件等</a>
                                <a style="color:red;">請選數字 6 </a>
                            </li>
                            <li>
                                <a>其他</a>
                                <a style="color:red;">請選數字 7 </a>
                            </li>
                        </ul> -->
                    </div>
                    <div></div>
                    
                    <div class="col-auto">
                        <label>檢測類型</label>
                        <input required onchange="TesttypeCheck(this.value)" pattern="[1-3]{1}" type="text" id="testtype" name="testtype" class="form-control " value="<?php echo $testtype; ?>">
                        <div id="PointMsgTesttype"></div>
                        <ul>
                            <li>1: 快篩 only</li>
                            <li>2: qPCR only</li>
                            <li>3: 兩者都做</li>
                        </ul>
                        <!-- <select id="inputTesttype" class="form-select">
                            <option selected>請選擇</option>
                            <option>1: 快篩 only</option>
                            <option>2: qPCR only</option>
                            <option>3: 兩者都做</option>
                        </select> -->
                    </div>
                    <div class="col-auto" hidden>
                        <label>預約類型</label>
                        <input type="text" id="per_type" class="form-control" name="per_type" value="<?php echo $per_type; ?>">
                    </div>

                    <div class="col-auto">
                        <label>送檢單位</label>
                        <input onchange="SendnameCheck(this.value)" pattern='[0-9]+' type="text" id="sendname" name="sendname" class="form-control " value="<?php echo $sendname; ?>">
                        <div id="PointMsgSendname"></div>
                    </div>


                    <div></div>

                </div>


                <br>

            </form>

        </div>
    </div>
</body>
<script src="js/checkin_modified.js"></script>

</html>