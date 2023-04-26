<?php

ini_set("display_errors", "on");
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["division"])) {
        if ($_SESSION["division"] > 1) {
            echo '<script language="javascript">alert("您沒有權限訪問喔~即將跳轉回首頁");</script>';
            echo '<script language="javascript">window.location.replace("menu.php");</script>';
        }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="css/menu.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/checkin_modified.css">
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#dialog-confirm").dialog({
                autoOpen: false,
                show: {
                    effect: "shake",
                    duration: 800
                },
                hide: {
                    effect: "fade",
                    duration: 800
                }
            });
            $("#dialog-positive").dialog({
                autoOpen: false,
                show: {
                    effect: "shake",
                    duration: 800
                },
                hide: {
                    effect: "fade",
                    duration: 800
                }
            });


            $("#nav").load("nav.html");
            var pcrid_uuid = document.getElementById("pcrid_uuid");
            var tdat = document.getElementById("tdat");
            var clear = document.getElementById("clear");
            var checkin_x = document.getElementById("checkin_x");
            var result_msg = document.getElementById("result_msg").value;
            var user_name = document.getElementById("user_name").value;
            switch (result_msg) {
                case '1':   // 第一次進入
                    button_checkin(false);
                    button_save(false);
                    pcrid_uuid.focus();
                    break;
                case '2':   // 查詢成功
                    //alert(user_name);
                    // if(user_name == "cindyT") break;
                    if (tdat && tdat.value) {
                        //clear.focus();
                        $("#dialog-confirm").dialog({
                            resizable: false,
                            height: "auto",
                            width: 400,
                            modal: true,
                            buttons: {
                                "  再  次  報   到  ": function () {
                                    checkin_x.click();
                                    $(this).dialog("close");
                                },
                                "  修  改  客  戶  資  料  ": function () {
                                    $(this).dialog("close");
                                    clear_focus();
                                }
                            }
                        });
                        $("#dialog-confirm").dialog("open");
                    } else {
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
                    pcrid_uuid.focus();
                    break;
                case '5':   // 儲存成功
                    pcrid_uuid.focus();
                    break;
                case '8':
                    $("#dialog-positive").dialog({
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            "  我 知 道 了 ": function () {
                                $(this).dialog("close");
                                clear_focus();
                            }
                        }
                    });
                    $("#dialog-positive").dialog("open");
                    break;
                default:
                    break;
            }


            document.getElementById("pcrid_uuid").addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("search").click();
                }
            })
            document.getElementById("userid_passid").addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("search").click();
                }
            })
            document.getElementById("mobile").addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("search").click();
                }
            })

            if ($("#page").val() === "0") {
                button_record(false);
            } else {
                button_record(true);
            }
            selectElement('sex', "<?php echo ($sex); ?>");
            selectElement('twrpturgency', "<?php echo ($twrpturgency); ?>");

            selectElement('testreason', "<?php echo ($testreason); ?>");
            selectElement('testtype', "<?php echo ($testtype); ?>");
        });

        function selectElement(id, valueToSelect) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        };

        // $(document).ready(function(){
        //     $("#checkin_x").click(function(){
        //         if(tdat && tdat.value){
        //             $( "#dialog-confirm" ).dialog({
        //                 resizable: false,
        //                 height: "auto",
        //                 width: 400,
        //                 modal: true,
        //                 buttons: {
        //                     "  再  次  報   到  ": function() {
        //                         $("#form_checkin").submit();
        //                         $( this ).dialog( "close" );
        //                     },
        //                     "  取   消  ": function() {
        //                     $( this ).dialog( "close" );
        //                     clear_focus();
        //                     }
        //                 }
        //             });
        //             $( "#dialog-confirm" ).dialog( "open" );
        //         }
        //     });
        // });

        // document.getElementById("hicardno").addEventListener("keypress", function(event){
        //     if(event.key ==="Enter"){ event.preventDefault(); document.getElementById("search").click(); }
        // })

        function clear_focus() {
            $("#defaultCheck2").click();
        };

        function button_checkin(status) {
            if (status === true) {
                $("#checkin_x").attr('disabled', false);
            } else {
                $("#checkin_x").attr('disabled', true);
            }
        };

        function button_save(status) {
            if (status === true) {
                $("#save").attr('disabled', false);
            } else {
                $("#save").attr('disabled', true);
            }
        };

        function button_record(status) {
            if (status === true) {
                $("#previous_record").attr('disabled', false);
                $("#next_record").attr('disabled', false);
            } else {
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
    <div id="dialog-positive" title="五日之內為陽性">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>請注意此採檢客戶五日內為陽性結果!!!
        </p>
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
                        <input id="printdiv" type="submit" class="btn btn-success" name="print"
                            value="   列            印   " target="_blank">
                    </form>
                </div>


                <form id="form_checkin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                    style="display:inline;">
                    <input type="hidden" id="result_msg" value="<?php echo $result_msg; ?>">
                    <input type="hidden" id="user_name" value="<?php echo $user_name; ?>">
                    <input type="hidden" id="page" value="<?php echo $page; ?>">
                    <input type="hidden" id="page_count" value="<?php echo $page_count; ?>">
                    <input type="hidden" id="uuid" name="uuid" value="<?php echo $uuid; ?>">
                    <input type="hidden" id="check2" name="check2" value="<?php echo $check2; ?>">
                    <div class="form-check form-switch">
                        <br>
                        <input class="form-check-input" type="checkbox" id="defaultCheck2" name="defaultCheck2">
                        <label class="form-check-label" for="defaultCheck2" style="color:#003300;">
                            <span class="ms-1 d-none d-sm-inline">修改資料</span>開關
                        </label>
                    </div>
                    <div></div>
                    <div>
                        <a style="margin:1em;" class="nav-link px-0">
                            <h2>
                                <?php echo $search_result; ?>
                            </h2>
                            <h3>
                                <?php echo $save_result; ?>
                            </h3><span class="d-none d-sm-inline"></span>
                        </a>
                    </div>
                    <div>
                        <input type="submit" id="previous_record" name="previous_record" class="btn btn-secondary"
                            value="上一筆"></input>
                        <label id="recordcount" style="color: black;">
                            <?php echo $recordcount ?>
                        </label>
                        <input type="submit" id="next_record" name="next_record" class="btn btn-secondary"
                            value="下一筆"></input>
                    </div>
                    <br><br>
                    <div class="col-auto">
                        <input type="button" class="btn btn-secondary" onclick="history.back()"
                            value="上 一 頁"></input>&ensp;&ensp;&ensp;
                        <input type="button" class="btn btn-secondary" onclick="window.location.href='menu.php'"
                            value="回 首 頁"></input>
                    </div>
            </div>
        </div>
        <!-- content -->
        <div class="col py-3">
            <!-- <h3>修改客戶資料</h3> -->
            <h5>請輸入身份證，護照號碼或手機號碼查詢預約資料</h5>
            <?php
            if (!empty($input_err)) {
                echo '<div class="alert alert-danger">' . $input_err . '</div>';
            }
            ?>
            <div class="row g-3">

                <div class="col-auto">
                    <label>PCR ID / 快篩 ID</label>
                    <input type="text" id="pcrid_uuid" name="pcrid_uuid" class="form-control"
                        value="<?php echo $pcrid_uuid; ?>">
                </div>

                <div class="col-auto">
                    <label>身分證號 / 護照號碼</label>
                    <input type="text" id="userid_passid" name="userid_passid" class="form-control"
                        value="<?php echo $userid_passid; ?>">
                </div>

                <div class="col-auto">
                    <label>手機號碼 Phone</label>
                    <input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                </div>
            </div>
            <br>

            <div class="col-auto">
                <input type="submit" id="search" name="search" class="btn btn-success"
                    value=" 搜    尋 ">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                <input type="submit" id="clear" name="clear" class="btn btn-success"
                    value=" 清    除 ">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                <input type="submit" id="checkin_x" name="checkin_x" class="btn btn-success"
                    value=" 報   到 ">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                <input type="submit" id="save" name="save" class="btn btn-success" value=" 儲    存 ">
            </div>
            <div id="read" class="row g-3">
                <div class="col-auto" hidden>
                    <label hidden>ID</label>
                </div>
                <input type="text" name="twrpturgency" class="form-control " value="<?php echo $twrpturgency; ?>"
                    hidden>



                <div class="row g-3">
                    <div class="col-auto">
                        <label>PCR ID</label>
                        <input onchange="TesttypeCheck(this.value)" pattern="^Q[0-9]{9}|^QH[0-9]{9}|^QL[0-9]{9}"
                            type="text" :focus id="sampleid2" name="sampleid2" class="form-control"
                            onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid2; ?>">
                    </div>

                    <div class="col-auto">
                        <label>快篩 ID</label>
                        <input onchange="TesttypeCheck(this.value)" pattern="^F[0-9]{9}" type="text" :focus
                            id="sampleid1" name="sampleid1" class="form-control"
                            onkeypress="if (window.event.keyCode==13) return false;" value="<?php echo $sampleid1; ?>">
                    </div>
                    <div class="col-auto">
                        <label>大陸專案</label>
                        <input type="text" id="xmappoint" name="xmappoint" class="form-control"
                            value="<?php echo $xmappoint; ?>">
                    </div>

                    <div id="PointMsgTesttypeCheck"></div>

                    <div class="col-auto">
                        <label>中文姓名</label>
                        <input onchange="CnameCheck(this.value)" pattern="[\u4E00-\u9FFF]+" type="text" id="cname"
                            name="cname" class="form-control " value="<?php echo $cname; ?>">
                        <div id="PointMsgCname"></div>
                    </div>

                    <div class="col-auto">
                        <label>英文姓名</label>
                        <input onchange="EnameCheck(this.value)" type="text" pattern="[a-zA-Z ,-]+" id="fname"
                            name="fname" class="form-control " value="<?php echo $fname; ?>">
                        <div id="PointMsgEname"></div>
                    </div>

                    <!-- <div id="PointMsgIdCardNumber"></div> -->

                    <div class="col-auto">
                        <label>生日</label>
                        <input required type="date" id="dob" name="dob" class="form-control "
                            value="<?php echo $dob; ?>">
                    </div>

                    <div class="col-auto">
                        <label>性別</label>
                        <div class="col-auto">
                            <select id="sex" name="sex" class="form-select" disabled>
                                <option value="男 / Male">男 / Male</option>
                                <option value="女 / Female">女 / Female</option>
                            </select>
                        </div>
                    </div>

                    <div></div>

                    <div class="col-auto">
                        <label>身分證字號</label>
                        <input onchange="IdCardNumberCheck(this.value)" type="text" id="userid1" name="userid1"
                            class="form-control " value="<?php echo $userid1; ?>">
                    </div>

                    <div class="col-auto">
                        <label>護照號碼</label>
                        <input type="text" id="passportid1" name="passportid1" class="form-control "
                            value="<?php echo $passportid1; ?>">
                    </div>

                    <div class="col-md-2">
                        <label>台胞證號碼</label>
                        <input type="text" id="mtpid" name="mtpid" class="form-control " value="<?php echo $mtpid; ?>">
                    </div>

                    <div class="col-auto">
                        <label>健保卡號</label>
                        <input type="text" id="hicardno1" name="hicardno1" class="form-control "
                            value="<?php echo $hicardno1; ?>">
                    </div>
                    <div id="PointMsgIdCardNumber"></div>
                    <div class="col-auto">
                        <label>預約日</label>
                        <input type="date" id="apdat" class="form-control" name="apdat" placeholder="1990-01-01"
                            value="<?php echo $apdat; ?>">
                    </div>
                    <div class="col-auto">
                        <label>報到日</label>
                        <input type="text" id="tdat" name="tdat" class="form-control" value="<?php echo $tdat; ?>">
                    </div>

                    <div class="col-auto">
                        <label>報告時效</label>
                        <div class="col-auto">
                            <select id="twrpturgency" name="twrpturgency" class="form-select" disabled>
                                <option value="normal"> 一 般 件 </option>
                                <option value="urgent"> 急 件 </option>
                                <option value="hiurgent"> 特 急 件 </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-auto">
                        <label>國籍(日本入境檢驗證明)</label>
                        <input type="text" id="nationality" name="nationality" class="form-control "
                            value="<?php echo $nationality; ?>">
                    </div>
                    <div></div>
                    <div class="col-md-4">
                        <label>E-mail</label>
                        <input onchange="EmailCheck(this.value)" type="email" id="email" name="email"
                            class="form-control " value="<?php echo $uemail; ?>">
                        <div id="PointMsgEmail"></div>
                    </div>

                    <div class="col-md-2">
                        <label>手機號碼</label>
                        <input required onchange="MobileCheck(this.value)" pattern='^[0-9]+$' type="text" id="mobile1"
                            name="mobile1" class="form-control" placeholder="0911111111"
                            value="<?php echo $mobile1; ?>">
                        <div id="PointMsgMobile"></div>
                    </div>

                    <div></div>

                    <div class="col-auto">
                        <label>自費篩檢原因</label>
                        <div class="col-auto">
                            <select id="testreason" name="testreason" class="form-select" disabled>
                                <option value="1">1. 因旅外親屬事故或重病等緊急特殊因素入境他國家/地區須檢附檢驗證明之民眾</option>
                                <option value="2">2. 因工作因素須檢附檢驗證明之民眾</option>
                                <option value="3">3. 短期商務人士</option>
                                <option value="4">4. 出國求學須檢附檢驗證明之民眾</option>
                                <option value="5">5. 外國或中國大陸、香港、澳門人士出境</option>
                                <option value="6">6. 相關出境適用對象之眷屬</option>
                                <option value="7">7. 其他</option>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="col-auto">
                        <label>採樣方式</label>
                        <div  class="col-auto">
                            <select id="xmrpturgency" name="xmrpturgency" class="form-select" >
                                <option value="1"> 鼻咽 </option>
                                <option value="2"> 咽喉 </option>
                            </select>
                        </div>
                    </div> -->

                    <div></div>

                    <div class="col-auto">
                        <label>檢測類型</label>
                        <select id="testtype" name="testtype" class="form-select" disabled>
                            <option value="1">快篩 only</option>
                            <option value="2">qPCR only</option>
                            <option value="3">兩者都做</option>
                        </select>
                    </div>
                    <div class="col-auto" hidden>
                        <label>預約類型</label>
                        <input type="text" id="per_type" class="form-control" name="per_type"
                            value="<?php echo $per_type; ?>">
                    </div>

                    <div class="col-auto">
                        <label>送檢單位統一編號</label>
                        <input onchange="SendnameCheck(this.value)" pattern='[0-9]+' type="text" id="sendname"
                            name="sendname" class="form-control " value="<?php echo $sendname; ?>">
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